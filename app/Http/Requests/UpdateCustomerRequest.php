<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateCustomerRequest
 *
 * Validates data for updating an existing customer.
 */
class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('customers edit') ?? false;
    }

    public function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:128'],
            'phone' => ['nullable', 'string', 'max:32'],
            'country' => ['nullable', 'string', 'max:64'],
            'status' => ['required', 'boolean'],
        ];
    }
}