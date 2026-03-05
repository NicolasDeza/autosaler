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
        Schema::create('vehicle_versions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('model_id')
                ->constrained('vehicle_models')
                ->cascadeOnDelete();

            $table->string('name', 100);
            $table->unsignedSmallInteger('start_year')->nullable()->default(null);
            $table->unsignedSmallInteger('end_year')->nullable()->default(null);

            $table->foreignId('body_type_id')
                ->constrained('body_types')
                ->restrictOnDelete();

            $table->foreignId('fuel_type_id')
                ->constrained('fuel_types')
                ->restrictOnDelete();

            $table->foreignId('transmission_type_id')
                ->constrained('transmission_types')
                ->restrictOnDelete();

            $table->foreignId('euro_norm_id')
                ->constrained('euro_norms')
                ->restrictOnDelete();

            $table->unsignedInteger('power_kw')->nullable();
            $table->unsignedInteger('engine_displacement')->nullable();
            $table->unsignedTinyInteger('cylinder_count')->nullable();
            $table->decimal('fuel_consumption_avg', 5, 2)->nullable();
            $table->unsignedInteger('weight_kg')->nullable();
            $table->unsignedInteger('co2_emission')->nullable();
            $table->unsignedInteger('gear_count')->nullable();

            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // INDEX Composite
            $table->index('fuel_type_id');
            $table->index('transmission_type_id');

            $table->index(['model_id', 'name']);
            $table->index(['model_id', 'fuel_type_id']);
            $table->index(['model_id', 'power_kw']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_versions');
    }
};
