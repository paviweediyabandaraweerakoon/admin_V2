<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\AMCInvoice;
use App\Services\AMCInvoiceTableService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Exception;

/**
 * Class AMCInvoiceController
 *
 * Handles manual AMC invoice management and server-side table processing.
 */
class AMCInvoiceController extends Controller
{
    /**
     * Display the AMC invoice index page.
     */
    public function index(): View
    {
        // Scope for Active projects only
        $projects = Project::active()->get();
        return view('administration.amc-invoices.index', compact('projects'));
    }

    /**
     * Remove the specified AMC invoice from storage.
     * * Added manual activity log to fix missing log issue.
     */
    public function destroy(AMCInvoice $amcInvoice): JsonResponse
    {
        try {
            $invoiceNo = $amcInvoice->invoice_no;
            

            // Activity log for deletion record
            activity()
                ->performedOn($amcInvoice)
                ->causedBy(Auth::user())
                ->withProperties(['invoice_no' => $invoiceNo])
                ->log("AMC Invoice {$invoiceNo} was deleted.");

            $amcInvoice->delete();

            return $this->sendResponse([], 'Invoice deleted successfully!');
        } catch (Exception $e) {
            Log::error('AMC Invoice Deletion Failed: ' . $e->getMessage());
            return $this->sendError('Error deleting invoice');
        }
    }

    /**
     * Process DataTables server-side request.
     */
    public function tableData(Request $request, AMCInvoiceTableService $service): JsonResponse
    {
        $data = $service->getTableData($request->all());
        return response()->json($data);
    }
}