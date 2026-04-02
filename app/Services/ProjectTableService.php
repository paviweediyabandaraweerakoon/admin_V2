<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ProjectTableService
{
    public function getTableData(array $requestData): array
    {
        $user = Auth::user();
        $search = $requestData['search']['value'] ?? null;
        $start = (int) ($requestData['start'] ?? 0);
        $length = (int) ($requestData['length'] ?? 10);
        
        $columns = ['id', 'project_name', 'customer_id', 'status', 'initial_value', 'launch_date'];
        $order_column = $columns[$requestData['order'][0]['column'] ?? 0] ?? 'id';
        $order_dir = $requestData['order'][0]['dir'] ?? 'desc';

        $query = Project::with('customer');
        $recordsTotal = Project::count();

        // Global Search logic
        $query->searchData($search);
        $recordsFiltered = $query->count();

        $projects = $query->tableData($order_column, $order_dir, $start, $length)->get();

        $data = [];
        foreach ($projects as $project) {
            $edit_btn = $user?->can('projects edit')
                ? "<i title='Edit' class='fas fa-edit mr-3 cursor-pointer text-primary' onclick='edit(this)' data-id='{$project->id}' data-name='".e($project->project_name)."' data-customer='{$project->customer_id}' data-status='{$project->status}' data-value='{$project->initial_value}' data-launch='".($project->launch_date ? $project->launch_date->format('Y-m-d') : "")."'></i>"
                : "";

            $url = "/projects/{$project->id}";
            $delete_btn = $user?->can('projects delete')
                ? "<i title='Delete' class='fas fa-trash-alt cursor-pointer text-danger' onclick=\"FormOptions.deleteRecord('{$project->id}','{$url}','dataTable')\"></i>"
                : "";

            $data[] = [
                e($project->project_name),
                e($project->customer?->company_name ?? 'N/A'),
                '<span class="badge badge-info">' . ucfirst(e($project->status)) . '</span>',
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