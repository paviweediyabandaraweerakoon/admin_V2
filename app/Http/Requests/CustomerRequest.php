<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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
        'status'       => ['required', 'integer', 'in:0,1'],
    ];
    }
}
