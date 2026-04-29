<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreAMCInvoiceRequest
 *
 * Validates data for creating a new AMC invoice.
 */

class StoreAMCInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('amc-invoices create') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array 
     */
    public function rules(): array
    {
        return [
            'project_id'   => ['required', 'integer', 'exists:projects,id'],
            'description'  => ['nullable', 'string', 'max:500'],
            'invoice_date' => ['required', 'date'],
            'due_date'     => ['required', 'date', 'after_or_equal:invoice_date'],
        ];
    }
}