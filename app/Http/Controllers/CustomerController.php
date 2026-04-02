<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Exception;

class CustomerController extends Controller
{
    /**
     * Display the customer administration index page.
     */
    public function index(): View
    {
        return view('administration.customers.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): void
    {
        // Not used
    }

    /**
     * Store a newly created customer in storage.
     */
    public function store(StoreCustomerRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $data['created_by'] = Auth::id();

            $customer = Customer::create($data);

            return $this->sendResponse($customer, 'Customer successfully added!');
        } catch (Exception $e) {
            Log::error('Customer store failed', [
                'error'   => $e->getMessage(),
                'user_id' => Auth::id(),
                'payload' => $request->all()
            ]);

            return $this->sendError('Error occurred while saving customer', [$e->getMessage()]);
        }
    }

    /**
     * Display the specified customer.
     */
    public function show(Customer $customer): JsonResponse
    {
        return $this->sendResponse($customer, 'Customer data retrieved');
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(Customer $customer): JsonResponse
    {
        return $this->sendResponse($customer, 'Customer data fetched for editing');
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer): JsonResponse
    {
        try {
            $data = $request->validated();
            $data['updated_by'] = Auth::id();

            $customer->update($data);

            return $this->sendResponse($customer, 'Customer updated successfully');
        } catch (Exception $e) {
            Log::error('Customer update failed', [
                'id'      => $customer->id,
                'error'   => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return $this->sendError('Update failed', [$e->getMessage()]);
        }
    }

    /**
     * Remove the specified customer from storage.
     */
    public function destroy(Customer $customer): JsonResponse
    {
        try {
            $customer->delete();

            return $this->sendResponse(null, 'Customer successfully deleted');
        } catch (Exception $e) {
            Log::error('Customer deletion failed', [
                'id'      => $customer->id,
                'error'   => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return $this->sendError('Delete failed', [$e->getMessage()]);
        }
    }

    /**
     * DataTables server-side processing
     */
    public function tableData(Request $request): JsonResponse
    {
        $user = Auth::user();

        $search = $request->search['value'] ?? null;
        $start = (int) $request->start;
        $length = (int) $request->length;

        $columns = ['id', 'company_name', 'phone', 'country', 'status', 'created_at'];
        $order_column = $columns[$request->order[0]['column']] ?? 'id';
        $order_dir = $request->order[0]['dir'] ?? 'desc';

        $query = Customer::query();

        $recordsTotal = Customer::count();

        $query->searchData($search);

        $recordsFiltered = $query->count();

        $customers = $query->tableData($order_column, $order_dir, $start, $length)->get();

        $data = [];

        $can_edit = $user?->can('customers edit');
        $can_delete = $user?->can('customers delete');

        foreach ($customers as $customer) {

            $edit_btn = $can_edit
                ? "<i title='Edit' class='fas fa-edit mr-3 cursor-pointer text-primary'
                    onclick='edit(this)'
                    data-id='{$customer->id}'
                    data-name='" . e($customer->company_name) . "'
                    data-phone='" . e($customer->phone) . "'
                    data-country='" . e($customer->country) . "'
                    data-status='{$customer->status}'></i>"
                : "";

            $url = "customers/{$customer->id}";

            $delete_btn = $can_delete
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

        return response()->json([
            "draw" => intval($request->draw),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data
        ]);
    }
}