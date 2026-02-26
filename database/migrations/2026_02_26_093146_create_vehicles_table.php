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
    Schema::create('vehicles', function (Blueprint $table) {
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
              ->constrained('brands')
              ->restrictOnDelete();

        $table->foreignId('model_id')
              ->constrained('models')
              ->restrictOnDelete();

        $table->foreignId('model_year_id')
              ->constrained('model_years')
              ->restrictOnDelete();

        // COLORS
        $table->foreignId('exterior_color_id')
              ->constrained('colors')
              ->restrictOnDelete();

        $table->boolean('exterior_color_metalised')->default(false);

        $table->foreignId('interior_color_id')
              ->constrained('colors')
              ->restrictOnDelete();

        // ENUMS
        $table->enum('interior_type', [
            'fabric','leather','alcantara','synthetic','suede'
        ]);

        $table->enum('fuel_type', [
            'petrol','diesel','electric','hybrid',
            'plug_in_hybrid','lpg','cng','hydrogen','ethanol'
        ])->index();

        $table->enum('body_type', [
            'sedan','hatchback','suv','coupe','cabriolet',
            'station_wagon','van','minivan','pickup',
            'roadster','crossover','sport'
        ])->index();

        $table->enum('euro_norm', [
            'euro_1','euro_2','euro_3','euro_4',
            'euro_5','euro_6','euro_6b','euro_6c',
            'euro_6d','euro_6d_temp','euro_6e','euro_7'
        ])->nullable();

        $table->enum('transmission', [
            'manual','automatic','semi_automatic','cvt'
        ])->index();

        $table->enum('technical_inspection_status', [
            'valid','not_valid','unknow'
        ])->default('unknow');

        $table->enum('is_damaged', [
            'yes','no','unknow'
        ])->default('unknow');

        $table->enum('has_accident', [
            'yes','no','unknow'
        ])->default('unknow');

        // TECHNICAL Infos
        $table->integer('power_kw');
        $table->decimal('price', 10, 2)->index();
        $table->integer('mileage')->index();
        $table->decimal('fuel_consumption_avg', 5, 2)->nullable();
        $table->integer('co2_emission')->nullable();

        $table->integer('gear_count')->nullable();
        $table->integer('doors');
        $table->integer('sieges');
        $table->integer('previous_owner')->nullable();

        $table->boolean('complete_maintenance_book')->default(false);
        $table->boolean('non_smoker')->default(false);

        $table->string('vin', 50)->unique();
        $table->string('registration_number', 50)->unique();

        $table->date('first_registration_date')->nullable()->index();
        $table->date('purchase_date')->nullable();

        $table->text('description')->nullable();

        $table->timestamps();

        // INDEX Composite
        $table->index(['brand_id', 'model_id', 'model_year_id']);

        // INDEX created_at !!!
        $table->index('created_at');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
