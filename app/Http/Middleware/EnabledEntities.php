<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnabledEntities
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
        if (!$request->user()->enabled) {
            Auth::logout();
            session()->flash('message', 'Your session has expired because your account is disabled.');
            session()->flash('alert-type', 'error');
            return redirect()->to('/login');
        }
        return $next($request);
    }
}
