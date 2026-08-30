<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Attendance - HRIS</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-neutral-100 text-neutral-900 dark:bg-neutral-950 dark:text-white">

    <div class="mx-auto min-h-screen max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h1 class="text-3xl font-bold tracking-tight">
                    Attendance
                </h1>

                <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                    Manage daily employee attendance.
                </p>
            </div>

            <a
                href="{{ route('dashboard') }}"
                class="inline-flex items-center justify-center rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm font-medium shadow-sm transition hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900 dark:hover:bg-neutral-800"
            >
                ← Dashboard
            </a>

        </div>

        {{-- Success message --}}
        @if (session('success'))
            <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-900 dark:bg-green-950 dark:text-green-300">
                {{ session('success') }}
            </div>
        @endif

        {{-- Validation errors --}}
        @if ($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-900 dark:bg-red-950 dark:text-red-300">
                <ul class="list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Date selection --}}
        <div class="mb-6 rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">

            <form
                method="GET"
                action="{{ route('attendance.index') }}"
                class="flex flex-col gap-4 sm:flex-row sm:items-end"
            >

                <div class="w-full sm:max-w-xs">

                    <label
                        for="date"
                        class="mb-2 block text-sm font-medium"
                    >
                        Attendance Date
                    </label>

                    <input
                        id="date"
                        name="date"
                        type="date"
                        value="{{ $date }}"
                        required
                        class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2.5 text-sm text-neutral-900 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                    >

                </div>

                <button
                    type="submit"
                    class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700"
                >
                    View Attendance
                </button>

            </form>

        </div>

        {{-- Attendance table --}}
        <div class="overflow-hidden rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900">

            <div class="border-b border-neutral-200 px-6 py-5 dark:border-neutral-800">

                <h2 class="text-lg font-semibold">
                    Daily Attendance
                </h2>

                <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                    {{ \Carbon\Carbon::parse($date)->format('d F Y') }}
                </p>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full min-w-[900px] text-left">

                    <thead class="border-b border-neutral-200 bg-neutral-50 dark:border-neutral-800 dark:bg-neutral-800/50">

                        <tr>

                            <th class="px-6 py-4 text-sm font-semibold">
                                Employee
                            </th>

                            <th class="px-6 py-4 text-sm font-semibold">
                                Current Status
                            </th>

                            <th class="px-6 py-4 text-sm font-semibold">
                                Update Attendance
                            </th>

                            <th class="px-6 py-4 text-right text-sm font-semibold">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-800">

                        @forelse ($employees as $employee)

                            @php
                                $record = $attendanceRecords->get($employee->id);
                                $approvedLeave = $approvedLeaves->get($employee->id);

                                $status = $record?->status;

                                if ($approvedLeave) {
                                    $status = 'On Leave';
                                }

                                $statusLabel = $status ?? 'Not Marked';
                            @endphp

                            <tr class="transition hover:bg-neutral-50 dark:hover:bg-neutral-800/40">

                                {{-- Employee --}}
                                <td class="px-6 py-5 align-top">

                                    <div class="font-medium">
                                        {{ $employee->name }}
                                    </div>

                                    @if (!empty($employee->employee_code))
                                        <div class="mt-1 text-xs text-neutral-500">
                                            {{ $employee->employee_code }}
                                        </div>
                                    @endif

                                </td>

                                {{-- Current status --}}
                                <td class="px-6 py-5 align-top">

                                    @if ($statusLabel === 'Present')

                                        <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700 dark:bg-green-950 dark:text-green-300">
                                            Present
                                        </span>

                                    @elseif ($statusLabel === 'Absent')

                                        <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700 dark:bg-red-950 dark:text-red-300">
                                            Absent
                                        </span>

                                    @elseif ($statusLabel === 'On Leave')

                                        <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700 dark:bg-blue-950 dark:text-blue-300">
                                            On Leave
                                        </span>

                                    @elseif ($statusLabel === 'Week Off')

                                        <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700 dark:bg-yellow-950 dark:text-yellow-300">
                                            Week Off
                                        </span>

                                    @elseif ($statusLabel === 'ABWO')

                                        <span class="inline-flex rounded-full bg-orange-100 px-3 py-1 text-xs font-semibold text-orange-700 dark:bg-orange-950 dark:text-orange-300">
                                            ABWO
                                        </span>

                                    @else

                                        <span class="inline-flex rounded-full bg-neutral-100 px-3 py-1 text-xs font-semibold text-neutral-600 dark:bg-neutral-800 dark:text-neutral-300">
                                            Not Marked
                                        </span>

                                    @endif

                                </td>

                                {{-- Update --}}
                                <td class="px-6 py-5 align-top">

                                    @if ($approvedLeave)

                                        <div class="font-medium text-blue-600 dark:text-blue-400">
                                            Approved Leave
                                        </div>

                                        <div class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                                            Attendance is automatically treated as On Leave.
                                        </div>

                                    @else

                                        <form
                                            method="POST"
                                            action="{{ route('attendance.store') }}"
                                            class="flex flex-col gap-3 sm:flex-row sm:items-center"
                                        >

                                            @csrf

                                            <input
                                                type="hidden"
                                                name="employee_id"
                                                value="{{ $employee->id }}"
                                            >

                                            <input
                                                type="hidden"
                                                name="attendance_date"
                                                value="{{ $date }}"
                                            >

                                            <select
                                                name="status"
                                                required
                                                class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white sm:w-44"
                                            >

                                                <option value="">
                                                    Select status
                                                </option>

                                                <option
                                                    value="Present"
                                                    @selected($status === 'Present')
                                                >
                                                    Present
                                                </option>

                                                <option
                                                    value="Absent"
                                                    @selected($status === 'Absent')
                                                >
                                                    Absent
                                                </option>

                                                <option
                                                    value="Week Off"
                                                    @selected($status === 'Week Off')
                                                >
                                                    Week Off
                                                </option>

                                                <option
                                                    value="On Leave"
                                                    @selected($status === 'On Leave')
                                                >
                                                    On Leave
                                                </option>

                                            </select>

                                            <button
                                                type="submit"
                                                class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700"
                                            >
                                                Save
                                            </button>

                                        </form>

                                    @endif

                                </td>

                                {{-- Remove --}}
                                <td class="px-6 py-5 text-right align-top">

                                    @if ($record)

                                        <form
                                            method="POST"
                                            action="{{ route('attendance.destroy', $record) }}"
                                            onsubmit="return confirm('Remove this attendance record?')"
                                            class="inline"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="rounded-lg px-3 py-2 text-sm font-medium text-red-600 transition hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950"
                                            >
                                                Remove
                                            </button>

                                        </form>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="4"
                                    class="px-6 py-12 text-center text-sm text-neutral-500"
                                >
                                    No employees found.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        {{-- Attendance information --}}
        <div class="mt-6 rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">

            <h2 class="text-lg font-semibold">
                Attendance Status
            </h2>

            <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">

                <div>
                    <div class="font-medium text-green-600 dark:text-green-400">
                        Present
                    </div>

                    <p class="mt-1 text-sm text-neutral-500">
                        Employee attended work.
                    </p>
                </div>

                <div>
                    <div class="font-medium text-red-600 dark:text-red-400">
                        Absent
                    </div>

                    <p class="mt-1 text-sm text-neutral-500">
                        Employee was absent.
                    </p>
                </div>

                <div>
                    <div class="font-medium text-blue-600 dark:text-blue-400">
                        On Leave
                    </div>

                    <p class="mt-1 text-sm text-neutral-500">
                        Approved leave.
                    </p>
                </div>

                <div>
                    <div class="font-medium text-yellow-600 dark:text-yellow-400">
                        Week Off
                    </div>

                    <p class="mt-1 text-sm text-neutral-500">
                        Regular weekly off.
                    </p>
                </div>

                <div>
                    <div class="font-medium text-orange-600 dark:text-orange-400">
                        ABWO
                    </div>

                    <p class="mt-1 text-sm text-neutral-500">
                        Week Off treated as absent by the sandwich rule.
                    </p>
                </div>

            </div>

        </div>

    </div>

</body>
</html>