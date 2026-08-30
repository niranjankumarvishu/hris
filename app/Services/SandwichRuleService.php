<?php

namespace App\Services;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SandwichRuleService
{
    public function apply(): void
    {
        $employees = Attendance::query()
            ->select('employee_id')
            ->distinct()
            ->pluck('employee_id');

        foreach ($employees as $employeeId) {
            $this->applyForEmployee($employeeId);
        }
    }

    private function applyForEmployee(int $employeeId): void
    {
        $records = Attendance::query()
            ->where('employee_id', $employeeId)
            ->orderBy('attendance_date')
            ->get();

        if ($records->isEmpty()) {
            return;
        }

        $dates = $records
            ->mapWithKeys(function ($attendance) {
                return [
                    Carbon::parse($attendance->attendance_date)
                        ->toDateString() => $attendance,
                ];
            });

        $dateList = $dates->keys()->values()->all();

        $count = count($dateList);

        for ($i = 0; $i < $count; $i++) {

            if (
                ($dates[$dateList[$i]]->status ?? null)
                !== 'Week Off'
            ) {
                continue;
            }

            $blockStart = $i;

            while (
                $i < $count &&
                ($dates[$dateList[$i]]->status ?? null)
                === 'Week Off'
            ) {
                $i++;
            }

            $blockEnd = $i - 1;

            if ($blockStart === 0 || $blockEnd === $count - 1) {
                continue;
            }

            $previousDate = $dateList[$blockStart - 1];
            $nextDate = $dateList[$blockEnd + 1];

            $previousStatus =
                $dates[$previousDate]->status ?? null;

            $nextStatus =
                $dates[$nextDate]->status ?? null;

            $previousIsAbsent = in_array(
                $previousStatus,
                ['Absent', 'ABWO'],
                true
            );

            $nextIsAbsent = in_array(
                $nextStatus,
                ['Absent', 'ABWO'],
                true
            );

            if (!$previousIsAbsent || !$nextIsAbsent) {
                continue;
            }

            DB::transaction(function () use (
                $dates,
                $dateList,
                $blockStart,
                $blockEnd
            ) {
                for (
                    $j = $blockStart;
                    $j <= $blockEnd;
                    $j++
                ) {
                    $attendance =
                        $dates[$dateList[$j]];

                    $attendance->update([
                        'status' => 'ABWO',
                    ]);
                }
            });
        }
    }
}