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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->string('project_name');
            $table->text('description')->nullable();
            $table->decimal('initial_value', 15, 2)->default(0.00);
            $table->string('status')->default('active');
            $table->decimal('amc_percentage', 5, 2)->nullable();
            $table->integer('amc_durations_month')->nullable();
            $table->date('launch_date')->nullable();
            $table->date('next_amc_date')->nullable();
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
        Schema::dropIfExists('projects');
    }
};
