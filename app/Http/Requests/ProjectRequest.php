<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ProjectRequest
 *
 * Merged Request for creating and updating Projects.
 * Validates fields for ProjectController store/update methods.
 * @package App\Http\Requests
 */
class ProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'customer_id'         => 'required|exists:customers,id',
            'project_name'        => 'required|string|max:128',
            'description'         => 'nullable|string',
            'initial_value'       => 'nullable|numeric|min:0',
            'status'              => 'sometimes|string|max:32',
            'amc_percentage'      => 'nullable|numeric|between:0,99.99',
            'amc_durations_month' => 'nullable|integer|min:0',
            'launch_date'         => 'nullable|date',
        ];
    }
}