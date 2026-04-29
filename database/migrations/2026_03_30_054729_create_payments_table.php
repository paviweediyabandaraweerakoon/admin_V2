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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('amc_invoice_id');
            $table->date('payment_date');
            $table->decimal('amount_received', 15, 2);
            $table->enum('payment_method', ['direct', 'cheque'])->default('direct');
            $table->string('currency' , 8)->default('LKR');
            $table->string('cheque_num' , 64)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->foreign('amc_invoice_id')->references('id')->on('amc_invoices')->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};