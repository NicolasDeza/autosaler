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
        Schema::create('vehicle_ads', function (Blueprint $table) {
            $table->id();

            // USER
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // STATUS
            $table->enum('status', ['draft', 'active', 'sold', 'archived'])
                ->default('draft')
                ->index();

            // DATASET AUTO
            $table->foreignId('brand_id')
                ->constrained('vehicle_brands')
                ->restrictOnDelete();

            $table->foreignId('model_id')
                ->constrained('vehicle_models')
                ->restrictOnDelete();

            $table->foreignId('vehicle_version_id')
                ->constrained('vehicle_versions')
                ->restrictOnDelete();

            // COLORS
            $table->foreignId('exterior_color_id')
                ->constrained('exterior_colors')
                ->restrictOnDelete();

            $table->boolean('exterior_color_metalised')->default(false);

            $table->foreignId('interior_color_id')
                ->constrained('interior_colors')
                ->restrictOnDelete();

            $table->foreignId('interior_type_id')
                ->constrained('interior_types')
                ->restrictOnDelete();

            $table->foreignId('fuel_type_id')
                ->constrained('fuel_types')
                ->restrictOnDelete();

            $table->foreignId('body_type_id')
                ->constrained('body_types')
                ->restrictOnDelete();

            $table->foreignId('euro_norm_id')
                ->constrained('euro_norms')
                ->restrictOnDelete();

            $table->foreignId('transmission_type_id')
                ->constrained('transmission_types')
                ->restrictOnDelete();

            // TECHNICAL Infos
            $table->decimal('price', 10, 2)->index();
            $table->unsignedInteger('mileage')->index();
            $table->unsignedInteger('power_kw')->nullable();
            $table->unsignedInteger('engine_displacement')->nullable(); // cm³ cylindrée
            $table->unsignedTinyInteger('cylinder_count')->nullable();
            $table->decimal('fuel_consumption_avg', 5, 2)->nullable();
            $table->unsignedInteger('weight_kg')->nullable();
            $table->unsignedInteger('co2_emission')->nullable();

            $table->unsignedTinyInteger('gear_count');
            $table->unsignedTinyInteger('doors');
            $table->unsignedTinyInteger('seats');
            $table->unsignedInteger('previous_owner')->nullable();

            $table->boolean('technical_inspection_status')->nullable();
            $table->boolean('is_damaged')->nullable();
            $table->boolean('has_accident')->nullable();
            $table->boolean('complete_maintenance_book')->default(false);
            $table->boolean('non_smoker')->default(false);

            $table->string('vin', 50)->unique();
            $table->string('registration_number', 50)->nullable()->index();

            $table->date('first_registration_date')->nullable()->index();
            $table->date('purchase_date')->nullable();

            $table->text('description')->nullable();

            $table->timestamps();

            // INDEX Composite
            $table->index(['brand_id', 'model_id', 'vehicle_version_id']);
            $table->index([
                'status',
                'brand_id',
                'fuel_type_id',
                'transmission_type_id',
                'body_type_id',
            ], 'vehicles_filter_index');

            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_ads');
    }
};
