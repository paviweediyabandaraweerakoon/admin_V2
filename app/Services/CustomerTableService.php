<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

/**
 * Service class to handle customer table data retrieval and formatting for DataTables.
 */
class CustomerTableService
{
        /**
        * Get formatted customer data for DataTables.
        *
        * @param array $requestData
        * @return array
        */
        
    public function getTableData(array $requestData): array
    {
        $user = Auth::user();
        $search = $requestData['search']['value'] ?? null;
        $start = (int) ($requestData['start'] ?? 0);
        $length = (int) ($requestData['length'] ?? 10);

        $columns = ['id', 'company_name', 'phone', 'country', 'status', 'created_at'];
        $order_column = $columns[$requestData['order'][0]['column'] ?? 0] ?? 'id';
        $order_dir = $requestData['order'][0]['dir'] ?? 'desc';

        $query = Customer::query()
            ->searchData($search, ['company_name', 'phone', 'country', 'status']);

        $recordsTotal = Customer::count();
        $recordsFiltered = $query->count();

        $customers = $query->tableData($order_column, $order_dir, $start, $length)->get();

        $data = [];
        foreach ($customers as $customer) {
            $url = "/customers/{$customer->id}";
            $edit_btn = $user?->can('customers edit')
                ? "<i title='Edit' class='fas fa-edit mr-3 cursor-pointer text-primary customer-edit-btn' data-id='{$customer->id}' data-url='{$url}' data-name='".e($customer->company_name)."' data-phone='".e($customer->phone)."' data-country='".e($customer->country)."' data-status='{$customer->status}'></i>"
                : "";

            $delete_btn = $user?->can('customers delete')
                ? "<i title='Delete' class='fas fa-trash-alt cursor-pointer text-danger customer-delete-btn' data-id='{$customer->id}' data-url='{$url}'></i>"
                : "";

            $data[] = [
                e($customer->company_name),
                e($customer->phone),
                e($customer->country),
                $customer->status
                    ? '<span class="badge badge-success">Active</span>'
                    : '<span class="badge badge-danger">Inactive</span>',
                $customer->created_at->format('Y-m-d H:i'),
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