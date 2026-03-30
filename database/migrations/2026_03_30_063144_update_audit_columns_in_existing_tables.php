<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to align audit columns with the existing user structure.
     * This migration updates 'created_by' and 'updated_by' fields to standard integers
     * and removes unnecessary foreign key constraints as per architectural requirements.
     */
    public function up(): void
    {
        // Update Customers Table
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign('customers_created_by_foreign'); 
            $table->dropForeign('customers_updated_by_foreign');
            $table->unsignedInteger('created_by')->nullable()->change();
            $table->unsignedInteger('updated_by')->nullable()->change();
        });

        // Update Projects Table
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign('projects_created_by_foreign');
            $table->dropForeign('projects_updated_by_foreign');
            $table->unsignedInteger('created_by')->nullable()->change();
            $table->unsignedInteger('updated_by')->nullable()->change();
        });

        // Update AMC Invoices Table
        Schema::table('amc_invoices', function (Blueprint $table) {
            $table->dropForeign('amc_invoices_created_by_foreign');
            $table->dropForeign('amc_invoices_updated_by_foreign');
            $table->unsignedInteger('created_by')->nullable()->change();
            $table->unsignedInteger('updated_by')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
    }
};
