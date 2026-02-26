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
        Schema::create('vehicle_stats', function (Blueprint $table) {
            $table->id();

            $table->foreignId('vehicle_id')
                  ->constrained('vehicles')
                  ->cascadeOnDelete()
                  ->unique(); //! RAJOUT De Unique

            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('contact_count')->default(0);
            $table->unsignedInteger('fav_count')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_stats');
    }
};
