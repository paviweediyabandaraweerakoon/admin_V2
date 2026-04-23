<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;

/**
 * Class ProjectTableService
 *
 * Service class to handle the retrieval and formatting of project data for DataTables.
 */
class ProjectTableService
{
    /**
     * Delete a project record.
     */
    public function deleteProject(Project $project): bool
    {
        return (bool) $project->delete();
    }

    public function getTableData(array $requestData): array
    {
        $user = Auth::user();
        $search = $requestData['search']['value'] ?? null;
        $start = (int) ($requestData['start'] ?? 0);
        $length = (int) ($requestData['length'] ?? 10);
        
        $columns = ['id', 'project_name', 'customer_id', 'status', 'initial_value', 'launch_date'];
        $order_column = $columns[$requestData['order'][0]['column'] ?? 0] ?? 'id';
        $order_dir = $requestData['order'][0]['dir'] ?? 'desc';

        // Eager load customer relationship and apply search filter using the SearchableTrait
        $query = Project::with('customer')
            ->searchData($search,
                ['project_name', 'status', 'initial_value'],
                ['customer' => ['company_name']]
            );

        $recordsTotal = Project::count();
        $recordsFiltered = $query->count();

        $projects = $query->tableData($order_column, $order_dir, $start, $length)->get();

        $data = [];
        foreach ($projects as $project) {
            $url = "/projects/{$project->id}";

            // Edit button with permission check
            $edit_btn = $user?->can('projects edit')
                ? "<a class='project-edit-btn text-primary py-0 px-1'
                    data-id='{$project->id}' 
                    data-url='{$url}' 
                    data-project_name='".e($project->project_name)."' 
                    data-customer_id='{$project->customer_id}' 
                    data-status='{$project->status}' 
                    data-initial_value='{$project->initial_value}' 
                    data-launch_date='".($project->launch_date ? $project->launch_date->format('Y-m-d') : "")."'>
                        <i class='far fa-edit tx-16'></i>
                    </a>"
                : "";

            // Delete button with permission check

            $delete_btn = $user?->can('projects delete')
                ? "<a class='project-delete-btn text-danger py-0 px-1 mg-l-5'
                    data-id='{$project->id}'
                    data-url='{$url}'
                    data-name='".e($project->project_name)."'>
                    <i class='far fa-trash-alt tx-16'></i>
                </a>"
                : "";

            $data[] = [
                e($project->project_name),
                e($project->customer?->company_name ?? 'N/A'),
                $project->status
                    ? '<span class="badge badge-success">Active</span>'
                    : '<span class="badge badge-danger">Inactive</span>',
                number_format((float)$project->initial_value, 2),
                $project->launch_date ? $project->launch_date->format('Y-m-d') : '-',
                $edit_btn . $delete_btn
            ];
        }

        return [
            "draw" => intval($requestData['draw'] ?? 0),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data
        ];
    }
}