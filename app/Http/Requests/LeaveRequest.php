<?php

namespace App\Http\Requests;

use App\Models\Leave;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class LeaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => [
                'required',
                'integer',
                'exists:employees,id',
            ],

            'leave_type' => [
                'required',
                'in:Paid,Sick,Casual',
            ],

            'from_date' => [
                'required',
                'date',
            ],

            'to_date' => [
                'required',
                'date',
                'after_or_equal:from_date',
            ],

            'reason' => [
                'required',
                'string',
                'max:1000',
            ],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $employeeId = $this->input('employee_id');
            $fromDate = $this->input('from_date');
            $toDate = $this->input('to_date');

            if (!$employeeId || !$fromDate || !$toDate) {
                return;
            }

            $query = Leave::query()
                ->where('employee_id', $employeeId)
                ->whereIn('status', ['Pending', 'Approved'])
                ->whereDate('from_date', '<=', $toDate)
                ->whereDate('to_date', '>=', $fromDate);

            // Ignore current leave while editing.
            if ($this->route('leave')) {
                $leave = $this->route('leave');

                if ($leave instanceof Leave) {
                    $query->whereKeyNot($leave->id);
                } else {
                    $query->whereKeyNot($leave);
                }
            }

            if ($query->exists()) {
                $validator->errors()->add(
                    'from_date',
                    'This employee already has a leave overlapping the selected dates.'
                );
            }
        });
    }
}