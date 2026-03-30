<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Fortify;
use App\Models\User;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        $this->app->singleton(
            \Laravel\Fortify\Contracts\LoginResponse::class,
            \App\Http\Responses\LoginResponse::class
        );

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        Fortify::loginView(function () {
            return view('auth.login');
        });
//        Fortify::registerView(function () {
//            return view('auth.register');
//        });
        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.passwords.email');
        });
        Fortify::resetPasswordView(function ($request) {
            return view('auth.passwords.reset', ['request' => $request]);
        });
        Fortify::verifyEmailView(function () {
            return view('auth.verify');
        });

        Fortify::authenticateUsing(function ($request) {
            $user = User::where('email', $request->email)->first();
            $invalidLoginCount = config('auth.invalid_login_count');
            if (!empty($user)) {
                if ($user && Hash::check($request->password, $user->password)) {
                    if ($user->wrong_attempts >= $invalidLoginCount) {
                        session()->flash('message', 'Your account has been blocked due to too many wrong login attempts.Please contact system administrator');
                        session()->flash('alert-type', 'error');
                        return false;
                    }
                    if (!$user->enabled) {
                        session()->flash('message', 'Your session has expired because your account is disabled.');
                        session()->flash('alert-type', 'error');
                        return false;
                    }
                    if (Carbon::now()->diffInDays($user->last_login) >= config('auth.user_expires_days')) {

                        $user->forceFill([
                            'enabled' => 0
                        ])->save();

                        session()->flash('message', 'Your session has expired because your account is disabled.');
                        session()->flash('alert-type', 'error');
                        return false;
                    }
                    if (Carbon::now()->diffInDays($user->password_changed_at) >= config('auth.password_expires_minimum_days')) {
                        session()->flash('message', 'Your password will be expire.Please change it');
                        session()->flash('alert-type', 'error');
                    }
                    $user->forceFill([
                        'last_login' => Carbon::now()
                    ])->save();
                    return $user;
                } else {
                    $user->forceFill([
                        'wrong_attempts' => $user->wrong_attempts + 1
                    ])->save();

                    $message = ($user->wrong_attempts >= $invalidLoginCount) ?
                        "Your account has been blocked due to too many wrong login attempts.Please contact system administrator" :
                        "You have made {$user->wrong_attempts} unsuccessful attempt(s). After $invalidLoginCount wrong attempts, your account will be disabled.";
                    session()->flash('message', $message);
                    session()->flash('alert-type', 'error');

                    return false;
                }
            }
        });
    }
}
