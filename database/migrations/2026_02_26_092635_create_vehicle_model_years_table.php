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
        Schema::create('vehicle_model_years', function (Blueprint $table) {
            $table->id();

            $table->foreignId('model_id')
                  ->constrained('vehicle_models')
                  ->cascadeOnDelete();

            $table->integer('year');

            $table->timestamps();

            // INDEX Composite
            $table->unique(['model_id', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_model_years');
    }
};
