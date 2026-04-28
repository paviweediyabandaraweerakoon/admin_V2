<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\AMCInvoice;
/**
 * Class UpdateAMCInvoiceRequest
 *
 * Validates the request data for updating an AMC invoice.
 */

class UpdateAMCInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('amc-invoices edit') ?? false;
    }

    public function rules(): array
    {
        return [
            'invoice_no'   => ['required', 'string', 'max:50'],
            'description'  => ['nullable', 'string', 'max:500'],
            'invoice_date' => ['required', 'date'],
            'status'       => ['required', 'in:pending,paid,cancelled'],
            'paid_at'      => ['nullable', 'date'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $status = $this->status;
            $paidAt = $this->paid_at;

            if ($status === AMCInvoice::STATUS_PAID && empty($paidAt)) {
                $validator->errors()->add(
                    'paid_at',
                    'Payment date is required when status is Paid.'
                );
            }

            if ($status !== AMCInvoice::STATUS_PAID && !empty($paidAt)) {
                $validator->errors()->add(
                    'paid_at',
                    'Payment date allowed only when status is Paid.'
                );
            }
        });
    }
}