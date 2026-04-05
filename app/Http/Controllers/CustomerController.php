<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Services\CustomerTableService;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Exception;


/*
 * Class CustomerController
 * Handles CRUD operations for Customers.
 */

class CustomerController extends Controller
{
    protected CustomerTableService $tableService;

    public function __construct(CustomerTableService $tableService)
    {
        $this->tableService = $tableService;
    }

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
        /**
         * Form is rendered via AJAX in the frontend.
         * No separate view needed for create form.
         */
    }

    /**
     * Store a newly created customer.
     * @param CustomerRequest $request
     * @return JsonResponse
     */

    public function store(StoreCustomerRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $customer = Customer::create($data);

            return $this->sendResponse($customer, 'Customer successfully added!');
        } catch (Exception $e) {
            Log::error('Customer store failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'payload' => $request->safe()->all()
            ]);

            return $this->sendError('Error occurred while saving customer', [$e->getMessage()]);
        }
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer): JsonResponse
    {
        try {
            $data = $request->validated();

            $customer->update($data);

            return $this->sendResponse($customer, 'Customer updated successfully');
        } catch (Exception $e) {
            Log::error('Customer update failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'payload' => $request->safe()->all()
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
            Log::error('Customer delete failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);

            return $this->sendError('Delete failed', [$e->getMessage()]);
        }
    }

    /**
     * DataTables server-side processing
     */
    public function tableData(Request $request): JsonResponse
    {
        // Using the injected service to get table data
        return response()->json($this->tableService->getTableData($request->all()));
    }

}