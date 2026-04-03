<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\AMCInvoice;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

/**
 * Class AMCInvoiceController
 *
 * Handles manual AMC invoice generation requests.
 */

class AMCInvoiceController extends Controller

/**
 * Generate a manual AMC invoice for a given project.
 *
 * @param int $projectId
 * @return JsonResponse
 */

{ 
    public function store(int $projectId): JsonResponse
    {
        try {
            $project = Project::findOrFail($projectId);
            
            $amount = 0;
            if ($project->initial_value > 0 && $project->amc_percentage > 0) {
                $amount = ($project->initial_value * ($project->amc_percentage / 100));
            }

            $invoice = AMCInvoice::create([
                'project_id'   => $project->id,
                'invoice_no'   => 'INV-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6)),
                'amount'       => $amount,
                'description'  => "Manual AMC Generation for " . $project->project_name,
                'invoice_date' => now(),
                'due_date'     => now()->addDays(14),
                'status'       => AMCInvoice::STATUS_PENDING,
                'created_by'   => Auth::id(),
            ]);

            Log::info("AMC Invoice Manually Generated", ['project_id' => $project->id]);

            return response()->json(['success' => true, 'message' => 'Invoice generated successfully!']);
        } catch (Exception $e) {
            Log::error("Manual AMC Generation Failed", ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error generating invoice'], 500);
        }
    }
}