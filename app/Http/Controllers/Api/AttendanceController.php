<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function summary(Request $request): JsonResponse
    {
        $request->validate([
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date', 'after_or_equal:from_date'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | DATE RANGE
        |--------------------------------------------------------------------------
        */

        $fromDate = Carbon::parse(
            $request->input(
                'from_date',
                now()->startOfMonth()->toDateString()
            )
        )->startOfDay();

        $toDate = Carbon::parse(
            $request->input(
                'to_date',
                now()->endOfMonth()->toDateString()
            )
        )->endOfDay();

        /*
        |--------------------------------------------------------------------------
        | EMPLOYEES
        |--------------------------------------------------------------------------
        */

        $employees = Employee::all();

        /*
        |--------------------------------------------------------------------------
        | ATTENDANCE RECORDS
        |--------------------------------------------------------------------------
        */

        $attendances = Attendance::query()
            ->whereDate(
                'attendance_date',
                '>=',
                $fromDate->toDateString()
            )
            ->whereDate(
                'attendance_date',
                '<=',
                $toDate->toDateString()
            )
            ->get()
            ->groupBy('employee_id');

        /*
        |--------------------------------------------------------------------------
        | APPROVED LEAVES
        |--------------------------------------------------------------------------
        */

        $approvedLeaves = Leave::query()
            ->where('status', 'Approved')
            ->where(function ($query) use ($fromDate, $toDate) {
                $query
                    ->whereDate(
                        'from_date',
                        '<=',
                        $toDate->toDateString()
                    )
                    ->whereDate(
                        'to_date',
                        '>=',
                        $fromDate->toDateString()
                    );
            })
            ->get()
            ->groupBy('employee_id');

        /*
        |--------------------------------------------------------------------------
        | SUMMARY COUNTERS
        |--------------------------------------------------------------------------
        */

        $present = 0;
        $absent = 0;
        $abwo = 0;
        $onLeave = 0;
        $weekOff = 0;

        /*
        |--------------------------------------------------------------------------
        | PROCESS EACH EMPLOYEE
        |--------------------------------------------------------------------------
        */

        foreach ($employees as $employee) {

            /*
            |--------------------------------------------------------------------------
            | EMPLOYEE ATTENDANCE
            |--------------------------------------------------------------------------
            */

            $employeeAttendances = $attendances->get(
                $employee->id,
                collect()
            );

            /*
            |--------------------------------------------------------------------------
            | KEY ATTENDANCE BY DATE
            |--------------------------------------------------------------------------
            */

            $records = $employeeAttendances->keyBy(
                function ($attendance) {
                    return Carbon::parse(
                        $attendance->attendance_date
                    )->toDateString();
                }
            );

            /*
            |--------------------------------------------------------------------------
            | EMPLOYEE APPROVED LEAVES
            |--------------------------------------------------------------------------
            */

            $leaves = $approvedLeaves->get(
                $employee->id,
                collect()
            );

            /*
            |--------------------------------------------------------------------------
            | BUILD DAILY STATUS
            |--------------------------------------------------------------------------
            */

            $dailyStatus = [];

            $currentDate = $fromDate->copy();

            while ($currentDate->lte($toDate)) {

                $date = $currentDate->toDateString();

                /*
                |--------------------------------------------------------------------------
                | CHECK APPROVED LEAVE
                |--------------------------------------------------------------------------
                */

                $isOnLeave = $leaves->contains(
                    function ($leave) use ($currentDate) {

                        $checkDate = $currentDate
                            ->copy()
                            ->startOfDay();

                        $leaveFrom = Carbon::parse(
                            $leave->from_date
                        )->startOfDay();

                        $leaveTo = Carbon::parse(
                            $leave->to_date
                        )->endOfDay();

                        return $checkDate->between(
                            $leaveFrom,
                            $leaveTo
                        );
                    }
                );

                /*
                |--------------------------------------------------------------------------
                | STATUS PRIORITY
                |--------------------------------------------------------------------------
                |
                | Approved Leave
                |        ↓
                | Attendance
                |        ↓
                | No Status
                |
                */

                if ($isOnLeave) {

                    $dailyStatus[$date] = 'On Leave';

                } elseif (isset($records[$date])) {

                    $dailyStatus[$date] =
                        $records[$date]->status;
                }

                $currentDate->addDay();
            }

            /*
            |--------------------------------------------------------------------------
            | SANDWICH RULE
            |--------------------------------------------------------------------------
            |
            | Absent - Week Off - Absent
            |
            | becomes:
            |
            | Absent - ABWO - Absent
            |
            |
            | Absent - Week Off - Week Off - Absent
            |
            | becomes:
            |
            | Absent - ABWO - ABWO - Absent
            |
            |--------------------------------------------------------------------------
            */

            $dates = [];

            $currentDate = $fromDate->copy();

            while ($currentDate->lte($toDate)) {

                $dates[] = $currentDate->toDateString();

                $currentDate->addDay();
            }

            $sandwichDates = [];

            $count = count($dates);

            $index = 0;

            while ($index < $count) {

                $date = $dates[$index];

                /*
                |--------------------------------------------------------------------------
                | ONLY WEEK OFF BLOCKS
                |--------------------------------------------------------------------------
                */

                if (
                    ($dailyStatus[$date] ?? null) !== 'Week Off'
                ) {
                    $index++;
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | START OF WEEK OFF BLOCK
                |--------------------------------------------------------------------------
                */

                $blockStart = $index;

                /*
                |--------------------------------------------------------------------------
                | FIND ALL CONSECUTIVE WEEK OFF DAYS
                |--------------------------------------------------------------------------
                */

                while (
                    $index < $count &&
                    ($dailyStatus[$dates[$index]] ?? null)
                        === 'Week Off'
                ) {
                    $index++;
                }

                $blockEnd = $index - 1;

                /*
                |--------------------------------------------------------------------------
                | BLOCK MUST HAVE A DAY BEFORE AND AFTER
                |--------------------------------------------------------------------------
                */

                if (
                    $blockStart === 0 ||
                    $blockEnd === $count - 1
                ) {
                    continue;
                }

                $previousDate =
                    $dates[$blockStart - 1];

                $nextDate =
                    $dates[$blockEnd + 1];

                /*
                |--------------------------------------------------------------------------
                | PREVIOUS DAY MUST BE ABSENT / ABWO
                |--------------------------------------------------------------------------
                */

                $previousIsAbsent = in_array(
                    $dailyStatus[$previousDate] ?? null,
                    [
                        'Absent',
                        'ABWO',
                    ],
                    true
                );

                /*
                |--------------------------------------------------------------------------
                | NEXT DAY MUST BE ABSENT / ABWO
                |--------------------------------------------------------------------------
                */

                $nextIsAbsent = in_array(
                    $dailyStatus[$nextDate] ?? null,
                    [
                        'Absent',
                        'ABWO',
                    ],
                    true
                );

                /*
                |--------------------------------------------------------------------------
                | CONVERT WEEK OFF TO ABWO
                |--------------------------------------------------------------------------
                */

                if (
                    $previousIsAbsent &&
                    $nextIsAbsent
                ) {

                    for (
                        $i = $blockStart;
                        $i <= $blockEnd;
                        $i++
                    ) {

                        $sandwichDates[] =
                            $dates[$i];
                    }
                }
            }

            /*
            |--------------------------------------------------------------------------
            | SAVE ABWO
            |--------------------------------------------------------------------------
            */

            if (!empty($sandwichDates)) {

                DB::transaction(function () use (
                    $employee,
                    $sandwichDates,
                    $records,
                    &$dailyStatus
                ) {

                    foreach ($sandwichDates as $date) {

                        /*
                        |--------------------------------------------------------------------------
                        | UPDATE EXISTING ATTENDANCE
                        |--------------------------------------------------------------------------
                        */

                        if (isset($records[$date])) {

                            $records[$date]->update([
                                'status' => 'ABWO',
                            ]);

                        } else {

                            /*
                            |--------------------------------------------------------------------------
                            | CREATE ABWO RECORD
                            |--------------------------------------------------------------------------
                            */

                            Attendance::create([
                                'employee_id' =>
                                    $employee->id,

                                'attendance_date' =>
                                    $date,

                                'status' =>
                                    'ABWO',
                            ]);
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | UPDATE CURRENT CALCULATION
                        |--------------------------------------------------------------------------
                        */

                        $dailyStatus[$date] = 'ABWO';
                    }
                });
            }

            /*
            |--------------------------------------------------------------------------
            | COUNT FINAL STATUS
            |--------------------------------------------------------------------------
            */

            foreach ($dailyStatus as $status) {

                switch ($status) {

                    case 'Present':
                        $present++;
                        break;

                    case 'Absent':
                        $absent++;
                        break;

                    case 'ABWO':
                        $abwo++;
                        break;

                    case 'On Leave':
                        $onLeave++;
                        break;

                    case 'Week Off':
                        $weekOff++;
                        break;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | API RESPONSE
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'total_employees' =>
                $employees->count(),

            'present' =>
                $present,

            'absent' =>
                $absent,

            'abwo' =>
                $abwo,

            'on_leave' =>
                $onLeave,

            'week_off' =>
                $weekOff,

            'from_date' =>
                $fromDate->toDateString(),

            'to_date' =>
                $toDate->toDateString(),
        ]);
    }
}