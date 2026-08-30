<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->unsignedInteger('paid_leave_balance')
                ->default(12)
                ->after('joining_date');

            $table->unsignedInteger('sick_leave_balance')
                ->default(6)
                ->after('paid_leave_balance');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'paid_leave_balance',
                'sick_leave_balance',
            ]);
        });
    }
};