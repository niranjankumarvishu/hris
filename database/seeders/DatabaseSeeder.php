<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Leave;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
         * Admin user
         */
        User::updateOrCreate(
            [
                'email' => 'niranjan@example.com',
            ],
            [
                'name' => 'Niranjan Kumar',
                'password' => Hash::make('password123'),
            ]
        );

        /*
         * Employees
         */
        $sunny = Employee::updateOrCreate(
            [
                'employee_code' => 'EMP001',
            ],
            [
                'name' => 'Sunny Raj',
                'email' => 'sunny@example.com',
                'phone' => '9876543210',
                'department' => 'IT',
                'joining_date' => '2026-01-01',
                'paid_leave_balance' => 12,
                'sick_leave_balance' => 6,
            ]
        );

        $rahul = Employee::updateOrCreate(
            [
                'employee_code' => 'EMP002',
            ],
            [
                'name' => 'Rahul Kumar',
                'email' => 'rahul@example.com',
                'phone' => '9876543211',
                'department' => 'HR',
                'joining_date' => '2026-02-01',
                'paid_leave_balance' => 12,
                'sick_leave_balance' => 6,
            ]
        );

        /*
         * Example leave records.
         */
        Leave::updateOrCreate(
            [
                'employee_id' => $sunny->id,
                'from_date' => '2026-08-03',
                'to_date' => '2026-08-04',
            ],
            [
                'leave_type' => 'Paid',
                'reason' => 'Personal work',
                'status' => 'Approved',
                'admin_remarks' => 'Approved by HR.',
            ]
        );

        Leave::updateOrCreate(
            [
                'employee_id' => $rahul->id,
                'from_date' => '2026-08-15',
                'to_date' => '2026-08-16',
            ],
            [
                'leave_type' => 'Sick',
                'reason' => 'Medical rest',
                'status' => 'Pending',
                'admin_remarks' => null,
            ]
        );
    }
}