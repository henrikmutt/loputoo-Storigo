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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id(); // BIGINT - Primary key
            $table->foreignId('user_id') // BIGINT - FK to users.id
                ->constrained()
                ->onDelete('cascade');
            $table->string('location'); // VARCHAR - Physical location
            $table->text('description'); // TEXT - Detailed description
            $table->float('size'); // FLOAT - suurus kuupmeetrites
            $table->decimal('price_per_day', 8, 2); // DECIMAL - Rental price per day
            $table->decimal('price_per_month',); // Price per month
            $table->date('available_from'); // DATE - Start of availability
            $table->date('available_until'); // DATE - End of availability
            $table->boolean('is_available')->default(true); // BOOLEAN - Is the room currently available
            $table->boolean('is_deleted')->default(false); // BOOLEAN - Soft-delete flag
            $table->json('images')->nullable(); // JSON - Up to 5 image URLs
            $table->timestamps(); // TIMESTAMP - created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
