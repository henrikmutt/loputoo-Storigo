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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id(); // BIGINT - Primary key

            $table->foreignId('user_one_id') // BIGINT - One user in the conversation
                ->constrained('users')
                ->onDelete('cascade');

            $table->foreignId('user_two_id') // BIGINT - The other user
                ->constrained('users')
                ->onDelete('cascade');

            $table->timestamps(); // created_at - Conversation start, updated_at - Last update
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
