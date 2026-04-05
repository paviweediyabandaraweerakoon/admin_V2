<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('customers edit') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:128'],
            'phone'        => ['nullable', 'string', 'max:32'],
            'country'      => ['nullable', 'string', 'max:64'],
            'status'       => ['required', 'boolean'],
        ];
    }
}