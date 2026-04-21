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
     * Display the main reports dashboard with available report types.
     * * @return View
     */
    public function index(): View
    {
        // Define available reports dynamically for the dashboard view
        $reports = [
            [
                'title'       => 'Customer Reports',
                'description' => 'View and export customer-level reporting summaries.',
                'route'       => 'reports.customer',
            ],
            [
                'title'       => 'Project Reports',
                'description' => 'View and export project-level reporting summaries.',
                'route'       => 'reports.project',
            ],
            [
                'title'       => 'AMC Invoice Reports',
                'description' => 'View and export AMC invoice reporting summaries.',
                'route'       => 'reports.amc-invoice',
            ],
        ];

        return view('administration.reports.index', compact('reports'));
    }

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