<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateProjectRequest
 *
 * Validates data for updating an existing project.
 */
class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('projects edit') ?? false;
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['sometimes', 'required', 'exists:customers,id'],
            'project_name' => ['sometimes', 'required', 'string', 'max:128'],
            'description' => ['sometimes', 'nullable', 'string'],
            'initial_value' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'status' => ['sometimes', 'nullable', 'string', 'max:32'],
            'amc_percentage' => ['sometimes', 'nullable', 'numeric', 'between:0,99.99'],
            'amc_durations_month' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'launch_date' => ['sometimes', 'nullable', 'date'],
        ];
    }
}
