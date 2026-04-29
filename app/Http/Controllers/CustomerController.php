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


/**
 * Class CustomerController
 * 
 * Handles CRUD operations for Customers.
 */

class CustomerController extends Controller
{

    /**
     * Display the customers index page.
     *
     * @return View
     */

    public function index(): View
    {
        return view('administration.customers.index');
    }

    /**
     * Store a newly created customer in the database.
     *
     * @param  StoreCustomerRequest $request
     * @return JsonResponse
     */

    public function store(StoreCustomerRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $customer = Customer::create($data);

            return $this->sendResponse($customer, 'Customer successfully created!');
        } catch (Exception $e) {
            Log::error('Customer store failed', [
                'user_id' => Auth::id(),
                'request_data' => $request->safe()->all(),
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
            ]);

            return $this->sendError('Error saving customer', [$e->getMessage()]);
        }
    }

    /**
     * Update the specified customer in the database.
     *
     * @param UpdateCustomerRequest $request
     * @param Customer $customer
     * @return JsonResponse
     */

    public function update(UpdateCustomerRequest $request, Customer $customer): JsonResponse
    {
        try {
            $data = $request->validated();

            $customer->update($data);

            return $this->sendResponse($customer, 'Customer updated successfully');
        } catch (Exception $e) {
            Log::error('Customer update failed', [
                'user_id' => Auth::id(),
                'customer_id' => $customer->id,
                'request_data' => $request->safe()->all(),
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
            ]);

            return $this->sendError('Update failed', [$e->getMessage()]);
        }
    }

    /**
     * Remove the specified customer from the database.
     *
     * @param Customer $customer
     * @return JsonResponse
     */

    public function destroy(Customer $customer, CustomerTableService $service): JsonResponse
    {
        try {
            $service->deleteCustomer($customer);

            return $this->sendResponse(null, 'Customer deleted successfully');
        } catch (Exception $e) {
            // Log detailed error information for debugging
            Log::error('Customer delete failed', [
                'user_id' => Auth::id(),
                'customer_id' => $customer->id,
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
            ]);
            return $this->sendError('Delete failed', [$e->getMessage()]);
        }
    }
    
    /**
     * Handle DataTables server-side processing for customers.
     *
     * @param Request $request
     * @param CustomerTableService $service
     * @return JsonResponse
     */

    public function tableData(Request $request, CustomerTableService $service): JsonResponse
    {
        $data = $service->getTableData($request->all());

        return response()->json($data);
    }
}