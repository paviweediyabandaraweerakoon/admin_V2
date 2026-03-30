<?php
namespace App\Http\Responses;

use App\Models\Menu;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract{
    public function toResponse($request)
    {
        $url = Auth::user()->landing_page;
        return $request->wantsJson()
            ? response()->json(['two_factor' => false])
            : redirect(route($url));
    }
}
