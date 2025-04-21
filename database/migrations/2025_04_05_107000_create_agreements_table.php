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
        Schema::create('agreements', function (Blueprint $table) {
            $table->id(); // BIGINT - Primary key

            $table->foreignId('user_id') // BIGINT - FK to users
                ->constrained('users')
                ->onDelete('cascade');

            $table->foreignId('booking_id') // BIGINT - FK to bookings
                ->constrained('bookings')
                ->onDelete('cascade');

            $table->string('version'); // VARCHAR - Agreement version
            $table->timestamp('accepted_at'); // TIMESTAMP - When the agreement was accepted

            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agreements');
    }
};
