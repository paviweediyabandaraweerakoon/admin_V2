<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * ReportController
 * * Handles the display and navigation of system reports.
 */
class ReportController extends Controller
{
    /**
     * Display the customer-specific reporting page.
     * * @return View
     */
    public function customer(): View
    {
        return view('administration.reports.customer');
    }

    /**
     * Display the project-specific reporting page.
     * * @return View
     */
    public function project(): View
    {
        return view('administration.reports.project');
    }

    /**
     * Display the AMC invoice reporting page.
     * * @return View
     */
    public function amcInvoice(): View
    {
        return view('administration.reports.amc-invoice');
    }
}