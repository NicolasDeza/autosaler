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
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();

            $table->string('key')->unique();
            $table->decimal('price', 10, 2);

            $table->unsignedInteger('listing_limit');
            $table->unsignedInteger('featured_limit');
            $table->unsignedInteger('duration_days');

            $table->unsignedInteger('image_limit_per_vehicle')->default(20);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
