<?php

namespace App\Actions\Fortify;

use App\Rules\ValidatePassword;
use Infinitypaul\LaravelPasswordHistoryValidation\Rules\NotFromPasswordHistory;
use App\Rules\MinimumAge;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array
     */
    protected function passwordRules($user)
    {

        return [
            'required',
            'string',
            (new ValidatePassword)->requireUppercase()->requireNumeric()->requireSpecialCharacter()->requireLowercase(),
            'confirmed' ,
            new NotFromPasswordHistory($user),
            new MinimumAge($user)
        ];
    }
}
