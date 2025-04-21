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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id(); // BIGINT - Primary key

            $table->foreignId('room_id') // BIGINT - FK to rooms table
                ->constrained('rooms')
                ->onDelete('cascade');

            $table->foreignId('renter_id') // BIGINT - FK to users table
                ->constrained('users')
                ->onDelete('cascade');

            $table->date('start_date'); // DATE - Start of rental period
            $table->date('end_date'); // DATE - End of rental period
            $table->decimal('total_price', 10, 2); // DECIMAL - Total price for rental
            $table->string('status'); // VARCHAR - Booking status
            $table->boolean('agreement_accepted')->default(false); // BOOLEAN - Agreement accepted

            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
