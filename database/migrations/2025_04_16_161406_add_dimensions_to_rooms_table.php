<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->decimal('length', 6, 2)->nullable()->after('description');
            $table->decimal('width', 6, 2)->nullable()->after('length');
            $table->decimal('height', 6, 2)->nullable()->after('width');
        });
    }

    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn(['length', 'width', 'height']);
        });
    }
};
