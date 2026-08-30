<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceSummaryTest extends TestCase
{
    use RefreshDatabase;

    public function test_attendance_summary_returns_expected_structure(): void
    {
        $employee = Employee::factory()->create();

        Attendance::create([
            'employee_id' => $employee->id,
            'attendance_date' => '2026-08-10',
            'status' => 'Present',
        ]);

        Attendance::create([
            'employee_id' => $employee->id,
            'attendance_date' => '2026-08-11',
            'status' => 'Absent',
        ]);

        Attendance::create([
            'employee_id' => $employee->id,
            'attendance_date' => '2026-08-12',
            'status' => 'Week Off',
        ]);

        $response = $this->getJson(
            '/api/attendance-summary?from_date=2026-08-10&to_date=2026-08-12'
        );

        $response
            ->assertOk()
            ->assertJsonStructure([
                'total_employees',
                'present',
                'absent',
                'abwo',
                'on_leave',
                'week_off',
                'from_date',
                'to_date',
            ])
            ->assertJson([
                'total_employees' => 1,
                'present' => 1,
                'absent' => 1,
                'abwo' => 0,
                'on_leave' => 0,
                'week_off' => 1,
                'from_date' => '2026-08-10',
                'to_date' => '2026-08-12',
            ]);
    }
}