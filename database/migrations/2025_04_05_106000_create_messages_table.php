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
        Schema::create('messages', function (Blueprint $table) {
            $table->id(); // BIGINT - Primary key

            $table->foreignId('conversation_id') // BIGINT - FK to conversations
                ->constrained('conversations')
                ->onDelete('cascade');

            $table->foreignId('sender_id') // BIGINT - FK to users
                ->constrained('users')
                ->onDelete('cascade');

            $table->text('message'); // TEXT - Message content
            $table->timestamp('read_at')->nullable(); // TIMESTAMP - When the message was read

            $table->timestamps(); // created_at - When sent, updated_at - Last update
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
