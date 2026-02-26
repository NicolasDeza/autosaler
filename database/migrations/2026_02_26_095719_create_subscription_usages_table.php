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
        Schema::create('subscription_usages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('subscription_id')
                  ->constrained('subscriptions')
                  ->cascadeOnDelete();

            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->unsignedInteger('listings_used')->default(0);
            $table->unsignedInteger('feature_used')->default(0);

            $table->timestamps();
            // INDEX Composite
            $table->index(['user_id', 'subscription_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_usages');
    }
};
