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

        // Eager load customer relationship and apply search filter
        $query = Project::with('customer')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('project_name', 'like', "%{$search}%")
                      ->orWhere('status', 'like', "%{$search}%")
                      ->orWhereHas('customer', function ($qc) use ($search) {
                          $qc->where('company_name', 'like', "%{$search}%");
                      });
                });
            });

        $recordsTotal = Project::count();
        $recordsFiltered = $query->count();

        $projects = $query->tableData($order_column, $order_dir, $start, $length)->get();

        $data = [];
        foreach ($projects as $project) {
            $url = "/projects/{$project->id}";
            $edit_btn = $user?->can('projects edit')
                ? "<i title='Edit' class='fas fa-edit mr-3 cursor-pointer text-primary project-edit-btn' data-id='{$project->id}' data-url='{$url}' data-name='".e($project->project_name)."' data-customer='{$project->customer_id}' data-status='{$project->status}' data-value='{$project->initial_value}' data-launch='".($project->launch_date ? $project->launch_date->format('Y-m-d') : "")."'></i>"
                : "";

            $delete_btn = $user?->can('projects delete')
                ? "<i title='Delete' class='fas fa-trash-alt cursor-pointer text-danger project-delete-btn' data-id='{$project->id}' data-url='{$url}'></i>"
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