<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    /**
     * Display attendance for a selected date.
     */
    public function index(Request $request): View
    {
        $request->validate([
            'date' => ['nullable', 'date'],
        ]);

        $date = Carbon::parse(
            $request->input('date', now()->toDateString())
        )->toDateString();

        $employees = Employee::query()
            ->orderBy('name')
            ->get();

        $attendanceRecords = Attendance::query()
            ->whereDate('attendance_date', $date)
            ->get()
            ->keyBy('employee_id');

        $approvedLeaves = Leave::query()
            ->where('status', 'Approved')
            ->whereDate('from_date', '<=', $date)
            ->whereDate('to_date', '>=', $date)
            ->get()
            ->keyBy('employee_id');

        return view('attendance.index', [
            'employees' => $employees,
            'attendanceRecords' => $attendanceRecords,
            'approvedLeaves' => $approvedLeaves,
            'date' => $date,
        ]);
    }

    /**
     * Save or update attendance.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => [
                'required',
                'integer',
                'exists:employees,id',
            ],

            'attendance_date' => [
                'required',
                'date',
            ],

            'status' => [
                'required',
                'in:Present,Absent,On Leave,Week Off',
            ],
        ]);

        $date = Carbon::parse(
            $validated['attendance_date']
        )->toDateString();

        /*
         * Check whether the employee has approved
         * leave on this date.
         */
        $approvedLeaveExists = Leave::query()
            ->where('employee_id', $validated['employee_id'])
            ->where('status', 'Approved')
            ->whereDate('from_date', '<=', $date)
            ->whereDate('to_date', '>=', $date)
            ->exists();

        /*
         * If approved leave exists, attendance must
         * be marked as On Leave.
         */
        if (
            $approvedLeaveExists &&
            $validated['status'] !== 'On Leave'
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'status' =>
                        'This employee has approved leave on this date. Attendance must be On Leave.',
                ]);
        }

        /*
         * On Leave cannot be selected when there is
         * no approved leave.
         */
        if (
            !$approvedLeaveExists &&
            $validated['status'] === 'On Leave'
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'status' =>
                        'There is no approved leave for this employee on this date.',
                ]);
        }

        /*
         * Create or update the attendance record.
         */
        Attendance::updateOrCreate(
            [
                'employee_id' => $validated['employee_id'],
                'attendance_date' => $date,
            ],
            [
                'status' => $validated['status'],
            ]
        );

        return back()->with(
            'success',
            'Attendance saved successfully.'
        );
    }

    /**
     * Delete an attendance record.
     */
    public function destroy(Attendance $attendance): RedirectResponse
    {
        $attendance->delete();

        return back()->with(
            'success',
            'Attendance record removed successfully.'
        );
    }
}