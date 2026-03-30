<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('amc_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id')->index();
            $table->string('invoice_no')->unique();
            $table->text('description')->nullable();
            $table->decimal('amount', 15, 2);
            $table->string('status')->default('pending')->comment('pending, paid, cancelled');
            $table->date('invoice_date');
            $table->date('due_date');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amc_invoices');
    }
};
