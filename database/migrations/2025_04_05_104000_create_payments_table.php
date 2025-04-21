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
        Schema::create('payments', function (Blueprint $table) {
            $table->id(); // BIGINT - Primary key

            $table->foreignId('booking_id') // BIGINT - FK to bookings
                ->constrained('bookings')
                ->onDelete('cascade');

            $table->string('stripe_payment_id'); // VARCHAR - Stripe payment/session ID
            $table->decimal('amount', 10, 2); // DECIMAL - Amount paid
            $table->string('status'); // VARCHAR - Payment status (e.g. succeeded, pending)
            $table->timestamp('paid_at')->nullable(); // TIMESTAMP - When payment was made

            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
