<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')
                ->constrained('employees')
                ->cascadeOnDelete();

            $table->enum('leave_type', ['Paid', 'Sick', 'Casual']);

            $table->date('from_date');
            $table->date('to_date');

            $table->text('reason')->nullable();

            $table->enum('status', [
                'Pending',
                'Approved',
                'Rejected'
            ])->default('Pending');

            $table->text('admin_remarks')->nullable();

            $table->timestamps();

            $table->index([
                'employee_id',
                'from_date',
                'to_date'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};