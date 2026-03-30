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
        Schema::create('global_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 64);
            $table->string('key_value', 64);
            $table->text('value')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('enabled')->default(1);
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
        Schema::dropIfExists('global_configurations');
    }
};
