<?php

namespace App\Actions\Fortify;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\ResetsUserPasswords;
use Carbon\Carbon;
use App\Services\PasswordPolicyService;

class ResetUserPassword implements ResetsUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and reset the user's forgotten password.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    public function reset($user, array $input)
    {
        Validator::make($input, [
            'password' =>  $this->passwordRules($user),
        ])->validate();

        $user->forceFill([
            'password' => Hash::make($input['password']),
            'wrong_attempts' => 0,
            'password_changed_at' => Carbon::now()->toDateTimeString()
        ])->save();

        $pc = new PasswordPolicyService($user);
        $pc->passwordChangeProcess();
    }
}
