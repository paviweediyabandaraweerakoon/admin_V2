<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PasswordExpired
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        $password_changed_at = new Carbon(($user->password_changed_at) ? $user->password_changed_at : $user->created_at);

        if (Carbon::now()->diffInDays($password_changed_at) >= config('auth.password_expires_days')) {
            session()->flash('message', 'Your password has expired. Please change it.');
            session()->flash('alert-type', 'error');
            return redirect()->route('password.expired');
        }
        return $next($request);
    }
}
