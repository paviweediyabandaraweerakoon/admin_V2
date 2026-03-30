<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\PasswordValidationRules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Services\PasswordPolicyService;

class ExpiredPasswordController extends Controller
{
    use PasswordValidationRules;

    public function expired()
    {
        return view('auth.passwords.expire');
    }

    public function postExpired(Request $request)
    {
        $user = \Auth::user();

        //      Checking current password
        if (!Hash::check($request->current_password, $request->user()->password)) {
            session()->flash('message', 'Current password is not correct');
            session()->flash('alert-type', 'error');
            return redirect()->back();
        }

        $fields = [
            'password' =>  $this->passwordRules($user),
        ];

        $validator = Validator::make($request->all(), $fields)->validate();

        $request->user()->update([
            'password' => Hash::make($request->password),
            'password_changed_at' => Carbon::now()->toDateTimeString()
        ]);

        $pc = new PasswordPolicyService($request->user());
        $pc->passwordChangeProcess();

        return redirect()->to('/home')->with(['status' => 'Password changed successfully']);
    }

}
