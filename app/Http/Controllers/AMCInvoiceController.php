<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\AMCInvoice;
use App\Services\AMCInvoiceTableService;
use App\Http\Requests\StoreAMCInvoiceRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Exception;

/**
 * Class AMCInvoiceController
 *
 * Handles manual AMC invoice generation requests.
 */

class AMCInvoiceController extends Controller
{
    /**
     * Display the AMC invoice generation page with active projects.
     *
     * @return View
     */
    public function index(): View
    {
        $projects = Project::active()->get();
        return view('administration.amc-invoices.index', compact('projects'));
    }

    /**
     * Generate a manual AMC invoice for a selected project.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(StoreAMCInvoiceRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $project = Project::findOrFail($data['project_id']);

            $amount = 0;
            if ($project->initial_value > 0 && $project->amc_percentage > 0) {
                $amount = $project->initial_value * ($project->amc_percentage / 100);
            }

            $invoice = AMCInvoice::create([
                'project_id'   => $project->id,
                'invoice_no'   => 'INV-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6)),
                'amount'       => $amount,
                'description'  => $data['description'] ?? ("Manual AMC Generation for " . $project->project_name),
                'invoice_date' => $data['invoice_date'],
                'due_date'     => $data['due_date'],
                'status'       => AMCInvoice::STATUS_PENDING,
            
            ]);

            Log::info('AMC Invoice Manually Generated', [
                'user_id' => Auth::id(),
                'project_id' => $project->id,
                'invoice_id' => $invoice->id,
            ]);

            return $this->sendResponse($invoice, 'Invoice generated successfully!');
        } catch (Exception $e) {
            Log::error('Manual AMC Generation Failed', [
                'user_id' => Auth::id(),
                'request_data' => $request->all(),
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
            ]);

            return $this->sendError('Error generating invoice', [$e->getMessage()]);
        }
    }

    /**
     * Handle DataTables server-side processing for AMC invoices.
     *
     * @param Request $request
     * @param AMCInvoiceTableService $service
     * @return JsonResponse
     */
    public function tableData(Request $request, AMCInvoiceTableService $service): JsonResponse
    {
        $data = $service->getTableData($request->all());

        return response()->json($data);
    }
}