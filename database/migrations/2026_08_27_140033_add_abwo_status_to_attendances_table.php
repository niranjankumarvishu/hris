<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->enum('status', [
                'Present',
                'Absent',
                'On Leave',
                'Week Off',
                'ABWO',
            ])->change();
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->enum('status', [
                'Present',
                'Absent',
                'On Leave',
                'Week Off',
            ])->change();
        });
    }
};