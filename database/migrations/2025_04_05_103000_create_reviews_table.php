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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id(); // BIGINT - Primary key

            $table->foreignId('from_user_id') // BIGINT - Reviewer
                ->constrained('users')
                ->onDelete('cascade');

            $table->foreignId('to_user_id') // BIGINT - Review recipient
                ->constrained('users')
                ->onDelete('cascade');

            $table->foreignId('to_room_id')->nullable()
                ->constrained('rooms')
                ->onDelete('cascade');

            $table->tinyInteger('rating'); // TINYINT - Rating from 1 to 5
            $table->text('comment')->nullable(); // TEXT - Optional comment
            $table->boolean('is_deleted')->default(false); // BOOLEAN - Soft-delete flag
            $table->timestamp('created_at')->useCurrent(); // TIMESTAMP - Created time
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
