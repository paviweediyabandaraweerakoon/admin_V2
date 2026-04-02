<?php 

namespace App\Services;

use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class CustomerTableService
{
    public function getTableData(array $requestData): array
    {
        $user = Auth::user();
        $query = Customer::query();

        // 1. Total records before filtering
        $recordsTotal = Customer::count();

        // 2. Global Search using Model Scope
        $query->searchData($requestData['search']['value'] ?? null);
        $recordsFiltered = $query->count();

        // 3. Ordering
        $columns = ['id', 'company_name', 'phone', 'country', 'status', 'created_at'];
        $order_column = $columns[$requestData['order'][0]['column']] ?? 'id';
        $order_dir = $requestData['order'][0]['dir'] ?? 'desc';

        $customers = $query->orderBy($order_column, $order_dir)
            ->offset($requestData['start'] ?? 0)
            ->limit($requestData['length'] ?? 10)
            ->get();

        $data = [];
        foreach ($customers as $customer) {
            $url = "customers/{$customer->id}";
            
            $edit_btn = $user?->can('customers edit')
                ? "<i title='Edit' class='fas fa-edit mr-3 cursor-pointer text-primary' onclick='edit(this)' 
                    data-id='{$customer->id}' data-name='".e($customer->company_name)."' 
                    data-phone='".e($customer->phone)."' data-country='".e($customer->country)."' 
                    data-status='{$customer->status}'></i>"
                : "";

            $delete_btn = $user?->can('customers delete')
                ? "<i title='Delete' class='fas fa-trash-alt cursor-pointer text-danger' 
                    onclick=\"FormOptions.deleteRecord('{$customer->id}','{$url}','dataTable')\"></i>"
                : "";

            $data[] = [
                e($customer->company_name),
                e($customer->phone),
                e($customer->country),
                $customer->status == 1 
                    ? '<span class="badge badge-success">Active</span>' 
                    : '<span class="badge badge-danger">Inactive</span>',
                $customer->created_at->format('Y-m-d H:i'),
                $edit_btn . $delete_btn
            ];
        }

        return [
            "draw"            => intval($requestData['draw'] ?? 0),
            "recordsTotal"    => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data"            => $data
        ];
    }
}