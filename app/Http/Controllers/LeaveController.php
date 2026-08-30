<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeaveRequest;
use App\Models\Employee;
use App\Models\Leave;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LeaveController extends Controller
{
    public function index(): View
    {
        $leaves = Leave::with('employee')
            ->latest()
            ->paginate(10);

        return view('leaves.index', compact('leaves'));
    }

    public function create(): View
    {
        $employees = Employee::query()
            ->orderBy('name')
            ->get();

        return view('leaves.create', compact('employees'));
    }

    public function store(LeaveRequest $request): RedirectResponse
    {
        Leave::create($request->validated());

        return redirect()
            ->route('leaves.index')
            ->with(
                'success',
                'Leave application submitted successfully.'
            );
    }

    public function show(Leave $leave): View
    {
        $leave->load('employee');

        return view('leaves.show', compact('leave'));
    }

    public function edit(Leave $leave): View
    {
        $employees = Employee::query()
            ->orderBy('name')
            ->get();

        return view('leaves.edit', compact('leave', 'employees'));
    }

    public function update(
        LeaveRequest $request,
        Leave $leave
    ): RedirectResponse {
        if ($leave->status !== 'Pending') {
            return redirect()
                ->route('leaves.index')
                ->with(
                    'error',
                    'Only pending leaves can be edited.'
                );
        }

        $leave->update($request->validated());

        return redirect()
            ->route('leaves.index')
            ->with(
                'success',
                'Leave application updated successfully.'
            );
    }

    public function destroy(Leave $leave): RedirectResponse
    {
        if ($leave->status === 'Approved') {
            return redirect()
                ->route('leaves.index')
                ->with(
                    'error',
                    'Approved leave cannot be deleted.'
                );
        }

        $leave->delete();

        return redirect()
            ->route('leaves.index')
            ->with(
                'success',
                'Leave application deleted successfully.'
            );
    }

    public function approve(
        Request $request,
        Leave $leave
    ): RedirectResponse {
        $request->validate([
            'admin_remarks' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        try {
            DB::transaction(function () use ($request, $leave) {

                $leave = Leave::query()
                    ->lockForUpdate()
                    ->findOrFail($leave->id);

                if ($leave->status !== 'Pending') {
                    abort(
                        422,
                        'Only pending leave applications can be approved.'
                    );
                }

                $days = $leave->from_date->diffInDays(
                    $leave->to_date
                ) + 1;

                $employee = Employee::query()
                    ->lockForUpdate()
                    ->findOrFail($leave->employee_id);

                if ($leave->leave_type === 'Paid') {

                    if ($employee->paid_leave_balance < $days) {
                        abort(
                            422,
                            "Insufficient paid leave balance. Required: {$days}, Available: {$employee->paid_leave_balance}."
                        );
                    }

                    $employee->decrement(
                        'paid_leave_balance',
                        $days
                    );
                }

                if ($leave->leave_type === 'Sick') {

                    if ($employee->sick_leave_balance < $days) {
                        abort(
                            422,
                            "Insufficient sick leave balance. Required: {$days}, Available: {$employee->sick_leave_balance}."
                        );
                    }

                    $employee->decrement(
                        'sick_leave_balance',
                        $days
                    );
                }

                $leave->update([
                    'status' => 'Approved',
                    'admin_remarks' => $request->input(
                        'admin_remarks'
                    ),
                ]);
            });

        } catch (\Throwable $e) {

            if ($e->getCode() === 422) {
                return redirect()
                    ->route('leaves.index')
                    ->with(
                        'error',
                        $e->getMessage()
                    );
            }

            throw $e;
        }

        return redirect()
            ->route('leaves.index')
            ->with(
                'success',
                'Leave approved successfully.'
            );
    }

    public function reject(
        Request $request,
        Leave $leave
    ): RedirectResponse {
        $request->validate([
            'admin_remarks' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        if ($leave->status !== 'Pending') {
            return redirect()
                ->route('leaves.index')
                ->with(
                    'error',
                    'Only pending leave applications can be rejected.'
                );
        }

        $leave->update([
            'status' => 'Rejected',
            'admin_remarks' => $request->input(
                'admin_remarks'
            ),
        ]);

        return redirect()
            ->route('leaves.index')
            ->with(
                'success',
                'Leave rejected successfully.'
            );
    }

    /**
     * Display leave statistics.
     */
    public function statistics(): View
    {
        $statistics = [
            'total' => Leave::count(),

            'pending' => Leave::where(
                'status',
                'Pending'
            )->count(),

            'approved' => Leave::where(
                'status',
                'Approved'
            )->count(),

            'rejected' => Leave::where(
                'status',
                'Rejected'
            )->count(),

            'paid' => Leave::where(
                'leave_type',
                'Paid'
            )->count(),

            'sick' => Leave::where(
                'leave_type',
                'Sick'
            )->count(),

            'casual' => Leave::where(
                'leave_type',
                'Casual'
            )->count(),

            /*
             * SQLite-compatible calculation.
             *
             * julianday() is supported by SQLite and calculates
             * the difference between two dates.
             *
             * + 1 makes the calculation inclusive:
             *
             * 01 Sep -> 01 Sep = 1 day
             * 01 Sep -> 03 Sep = 3 days
             */
            'approved_days' => (int) (
                Leave::where('status', 'Approved')
                    ->selectRaw(
                        'COALESCE(SUM(julianday(to_date) - julianday(from_date) + 1), 0) as total'
                    )
                    ->value('total') ?? 0
            ),
        ];

        /*
         * Load employees together with leave counts.
         *
         * withCount() avoids an N+1 query problem.
         */
        $employeeStatistics = Employee::query()
            ->withCount([
                'leaves as total_leaves',

                'leaves as approved_leaves' => function ($query) {
                    $query->where(
                        'status',
                        'Approved'
                    );
                },

                'leaves as pending_leaves' => function ($query) {
                    $query->where(
                        'status',
                        'Pending'
                    );
                },

                'leaves as rejected_leaves' => function ($query) {
                    $query->where(
                        'status',
                        'Rejected'
                    );
                },
            ])
            ->orderBy('name')
            ->get();

        return view(
            'leaves.statistics',
            compact(
                'statistics',
                'employeeStatistics'
            )
        );
    }
}