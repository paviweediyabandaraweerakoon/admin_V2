<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreProjectRequest
 *
 * Validates data for creating a new project.
 */
class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('projects create') ?? false;
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'exists:customers,id'],
            'project_name' => ['required', 'string', 'max:128'],
            'description' => ['nullable', 'string'],
            'initial_value' => ['nullable', 'numeric', 'min:0'],
            'status' => ['nullable', 'string', 'max:32'],
            'amc_percentage' => ['nullable', 'numeric', 'between:0,99.99'],
            'amc_durations_month' => ['nullable', 'integer', 'min:1'],
            'launch_date' => ['nullable', 'date'],
        ];
    }
}
