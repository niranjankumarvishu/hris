@extends('layouts.app')

@section('content')
<div style="max-width: 900px; margin: 40px auto; padding: 0 20px;">

    {{-- Header --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <div>
            <h1 style="font-size: 28px; font-weight: 600; margin: 0;">
                Leave Details
            </h1>

            <p style="color: #64748b; margin-top: 6px;">
                View leave application details.
            </p>
        </div>

        <a
            href="{{ route('leaves.index') }}"
            style="
                text-decoration: none;
                color: #2563eb;
                font-weight: 500;
            "
        >
            ← Back to Leaves
        </a>
    </div>


    {{-- Leave Details Card --}}
    <div
        style="
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        "
    >

        {{-- Employee --}}
        <div style="margin-bottom: 22px;">
            <div style="font-size: 14px; color: #64748b;">
                Employee
            </div>

            <div style="font-size: 17px; font-weight: 600; margin-top: 5px;">
                {{ $leave->employee->name }}
            </div>

            @if($leave->employee->employee_code)
                <div style="font-size: 13px; color: #64748b; margin-top: 3px;">
                    {{ $leave->employee->employee_code }}
                </div>
            @endif
        </div>


        {{-- Leave Type --}}
        <div style="margin-bottom: 22px;">
            <div style="font-size: 14px; color: #64748b;">
                Leave Type
            </div>

            <div style="font-size: 16px; margin-top: 5px;">
                {{ $leave->leave_type }}
            </div>
        </div>


        {{-- Dates --}}
        <div
            style="
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 30px;
                margin-bottom: 22px;
            "
        >

            <div>
                <div style="font-size: 14px; color: #64748b;">
                    From Date
                </div>

                <div style="font-size: 16px; margin-top: 5px;">
                    {{ $leave->from_date->format('d M Y') }}
                </div>
            </div>

            <div>
                <div style="font-size: 14px; color: #64748b;">
                    To Date
                </div>

                <div style="font-size: 16px; margin-top: 5px;">
                    {{ $leave->to_date->format('d M Y') }}
                </div>
            </div>

        </div>


        {{-- Reason --}}
        <div style="margin-bottom: 22px;">
            <div style="font-size: 14px; color: #64748b;">
                Reason
            </div>

            <div
                style="
                    margin-top: 7px;
                    padding: 14px;
                    background: #f8fafc;
                    border-radius: 6px;
                    line-height: 1.6;
                "
            >
                {{ $leave->reason ?: 'No reason provided.' }}
            </div>
        </div>


        {{-- Status --}}
        <div style="margin-bottom: 22px;">
            <div style="font-size: 14px; color: #64748b;">
                Status
            </div>

            <div style="margin-top: 7px;">

                @if($leave->status === 'Approved')

                    <span
                        style="
                            color: #16a34a;
                            font-weight: 600;
                        "
                    >
                        Approved
                    </span>

                @elseif($leave->status === 'Rejected')

                    <span
                        style="
                            color: #dc2626;
                            font-weight: 600;
                        "
                    >
                        Rejected
                    </span>

                @else

                    <span
                        style="
                            color: #d97706;
                            font-weight: 600;
                        "
                    >
                        Pending
                    </span>

                @endif

            </div>
        </div>


        {{-- Existing Admin Remarks --}}
        @if($leave->admin_remarks)

            <div
                style="
                    margin-top: 25px;
                    padding: 18px;
                    background: #f8fafc;
                    border: 1px solid #e2e8f0;
                    border-radius: 8px;
                "
            >

                <div
                    style="
                        font-size: 14px;
                        color: #64748b;
                        margin-bottom: 7px;
                    "
                >
                    Admin Remarks
                </div>

                <div
                    style="
                        font-size: 15px;
                        line-height: 1.6;
                        white-space: pre-wrap;
                    "
                >
                    {{ $leave->admin_remarks }}
                </div>

            </div>

        @endif


        {{-- ADMIN ACTIONS --}}
        @if($leave->status === 'Pending')

            <div
                style="
                    margin-top: 35px;
                    padding-top: 25px;
                    border-top: 1px solid #e2e8f0;
                "
            >

                <h2
                    style="
                        font-size: 20px;
                        font-weight: 600;
                        margin-bottom: 20px;
                    "
                >
                    Admin Action
                </h2>


                {{-- APPROVE FORM --}}
                <form
                    method="POST"
                    action="{{ route('leaves.approve', $leave) }}"
                    style="margin-bottom: 25px;"
                >

                    @csrf

                    <label
                        for="approve_remarks"
                        style="
                            display: block;
                            font-size: 14px;
                            font-weight: 500;
                            margin-bottom: 7px;
                        "
                    >
                        Admin Remarks
                    </label>

                    <textarea
                        id="approve_remarks"
                        name="admin_remarks"
                        rows="4"
                        maxlength="1000"
                        placeholder="Enter remarks before approving..."
                        style="
                            width: 100%;
                            box-sizing: border-box;
                            border: 1px solid #cbd5e1;
                            border-radius: 6px;
                            padding: 12px;
                            resize: vertical;
                            font-family: inherit;
                            font-size: 14px;
                        "
                    >{{ old('admin_remarks') }}</textarea>

                    @error('admin_remarks')
                        <div
                            style="
                                color: #dc2626;
                                font-size: 13px;
                                margin-top: 5px;
                            "
                        >
                            {{ $message }}
                        </div>
                    @enderror

                    <button
                        type="submit"
                        style="
                            margin-top: 12px;
                            padding: 10px 20px;
                            background: #16a34a;
                            color: white;
                            border: none;
                            border-radius: 6px;
                            font-size: 14px;
                            font-weight: 600;
                            cursor: pointer;
                        "
                    >
                        Approve Leave
                    </button>

                </form>


                {{-- REJECT FORM --}}
                <form
                    method="POST"
                    action="{{ route('leaves.reject', $leave) }}"
                >

                    @csrf

                    <label
                        for="reject_remarks"
                        style="
                            display: block;
                            font-size: 14px;
                            font-weight: 500;
                            margin-bottom: 7px;
                        "
                    >
                        Admin Remarks
                    </label>

                    <textarea
                        id="reject_remarks"
                        name="admin_remarks"
                        rows="4"
                        maxlength="1000"
                        placeholder="Enter reason for rejection..."
                        style="
                            width: 100%;
                            box-sizing: border-box;
                            border: 1px solid #cbd5e1;
                            border-radius: 6px;
                            padding: 12px;
                            resize: vertical;
                            font-family: inherit;
                            font-size: 14px;
                        "
                    >{{ old('admin_remarks') }}</textarea>

                    @error('admin_remarks')
                        <div
                            style="
                                color: #dc2626;
                                font-size: 13px;
                                margin-top: 5px;
                            "
                        >
                            {{ $message }}
                        </div>
                    @enderror

                    <button
                        type="submit"
                        style="
                            margin-top: 12px;
                            padding: 10px 20px;
                            background: #dc2626;
                            color: white;
                            border: none;
                            border-radius: 6px;
                            font-size: 14px;
                            font-weight: 600;
                            cursor: pointer;
                        "
                    >
                        Reject Leave
                    </button>

                </form>

            </div>

        @endif

    </div>

</div>
@endsection