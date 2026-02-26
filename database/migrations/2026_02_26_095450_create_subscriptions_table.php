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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->foreignId('subscription_plan_id')
                  ->constrained('subscription_plans')
                  ->restrictOnDelete();

             $table->enum('status', [
                 'trial',
                 'active',
                 'expired',
                 'cancelled'
             ])->index();

             $table->timestamp('starts_at');
             $table->timestamp('ends_at');

             $table->boolean('auto_renew')->default(true);

             $table->timestamp('trial_ends_at')->nullable();
             $table->timestamp('cancelled_at')->nullable();

             $table->timestamps();
             // INDEX Composite
             $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
