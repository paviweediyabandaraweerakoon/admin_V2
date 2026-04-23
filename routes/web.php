<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\DebugController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ExpiredPasswordController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AMCInvoiceController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('auth.login');
});
Route::get('debug', [DebugController::class, 'index']);

Route::get('password/expired', [ExpiredPasswordController::class , 'expired'])->name('password.expired');
Route::post('password/post_expired', [ExpiredPasswordController::class , 'postExpired'])->name('password.post_expired');

Route::middleware('auth','enabled_entities','user_expired','password_expired')->group(function () {

    Route::get('/', function () {
        return view('home');
    });

    Route::get('/home', function () {
        return view('home');
    })->name('home');

    Route::resource('users', UserController::class)->except('create', 'show', 'edit');
    Route::get('users/table/data', [UserController::class, 'tableData']);
    Route::put('users/{user}/reset', [UserController::class, 'resetPassword'])->name('users.reset');
    Route::post('users/reset/login/{user}',  [UserController::class, 'resetLogin'])->name('users.resetlogin');
    Route::get('/profile', [UserController::class, 'editProfile'])->name('users.profile');
    Route::post('users/get-landing-page', [UserController::class, 'getLandingPageByRole']);

    Route::resource('permissions', PermissionController::class)->except('create', 'show', 'edit');
    Route::get('permissions/table/data', [PermissionController::class, 'tableData']);

    Route::resource('roles', RoleController::class)->except('create', 'show', 'edit');
    Route::get('roles/table/data', [RoleController::class, 'tableData']);
    Route::get('/roles/render/form', [RoleController::class, 'renderForm'])->name('roles.form');

    Route::resource('menu', MenuController::class)->except('create', 'show', 'edit');
    Route::get('menu/table/data', [MenuController::class, 'tableData']);

    Route::resource('activity-logs', ActivityLogController::class)->except('create', 'show', 'edit');
    Route::get('activity-logs/table/data', [ActivityLogController::class, 'tableData'])->name('activity-logs.data');

    /**
     * Customer Management Routes
     * Includes standard resource routes (excluding views) and 
     * a specific endpoint for DataTables JSON responses.
    */
    
    Route::resource('customers', CustomerController::class)->except('create', 'show', 'edit');
Route::get('customers/table/data', [CustomerController::class, 'tableData'])->name('customers.table.data');    

    /**
     * Project Management Routes
     * - Standard resource routes (excluding views)
     * - DataTables endpoint for JSON data
     */
    Route::resource('projects', ProjectController::class)->except('create', 'show', 'edit');
    Route::get('projects/table/data', [ProjectController::class, 'tableData'])->name('projects.data');

    /**
     * Reports Management Routes
     * Navigation for specific reporting modules.
     */
    Route::group(['prefix' => 'reports', 'as' => 'reports.'], function () {
        Route::get('/customers', [ReportController::class, 'customer'])->name('customer');
        Route::get('/index', [ReportController::class, 'customer'])->name('index');

        Route::get('/projects', [ReportController::class, 'project'])->name('project');
        Route::get('/amc-invoice', [ReportController::class, 'amcInvoice'])->name('amc-invoice');
    });

    Route::resource('amc-invoices', AMCInvoiceController::class)->except('create', 'show', 'edit', 'store');
    Route::get('amc-invoices/table/data', [AMCInvoiceController::class, 'tableData'])->name('amc-invoices.tableData');
    
});