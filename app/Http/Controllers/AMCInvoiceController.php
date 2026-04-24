<?php

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
    
    public function index(AMCInvoiceTableService $service): View
    {
        // Scope for Active projects only
        $projects = $service->getActiveProjects();
        
        return view('administration.amc-invoices.index', compact('projects'));
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