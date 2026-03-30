<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UserExpired
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
        $last_login = new Carbon(($user->last_login) ?? $user->created_at);
        if (Carbon::now()->diffInDays($last_login) >= config('auth.user_expires_days')) {
            $user->enabled =0;
            $user->save();

            session()->flash('message', 'Your session has expired because your account is disabled.');
            session()->flash('alert-type', 'error');

            return  redirect('/');
        }
        return $next($request);
    }
}
