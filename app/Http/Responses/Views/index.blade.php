<x-layouts.app :title="__('Attendance')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">

        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">
                    Attendance
                </h1>

                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                    Manage daily employee attendance.
                </p>
            </div>

            <a
                href="{{ route('dashboard') }}"
                class="inline-flex items-center justify-center rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-700 shadow-sm transition hover:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200 dark:hover:bg-zinc-800"
            >
                Dashboard
            </a>
        </div>

        {{-- Messages --}}
        @if (session('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-900 dark:bg-green-950/40 dark:text-green-300">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900 dark:bg-red-950/40 dark:text-red-300">
                <ul class="list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Date selector --}}
        <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <form
                method="GET"
                action="{{ route('attendance.index') }}"
                class="flex flex-col gap-4 sm:flex-row sm:items-end"
            >
                <div>
                    <label
                        for="date"
                        class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-300"
                    >
                        Attendance Date
                    </label>

                    <input
                        id="date"
                        name="date"
                        type="date"
                        value="{{ $date }}"
                        class="rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 outline-none focus:border-zinc-500 focus:ring-2 focus:ring-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white"
                    >
                </div>

                <button
                    type="submit"
                    class="rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-zinc-700 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200"
                >
                    View Attendance
                </button>
            </form>
        </div>

        {{-- Attendance table --}}
        <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">

                    <thead class="bg-zinc-50 dark:bg-zinc-800/60">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">
                                Employee
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">
                                Date
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">
                                Status
                            </th>

                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">
                                Action
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">

                        @forelse ($employees as $employee)

                            @php
                                $record = $attendanceRecords->get($employee->id);
                                $approvedLeave = $approvedLeaves->get($employee->id);

                                $currentStatus = $approvedLeave
                                    ? 'On Leave'
                                    : ($record?->status ?? '');
                            @endphp

                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/40">

                                <td class="whitespace-nowrap px-5 py-4">
                                    <div class="font-medium text-zinc-900 dark:text-white">
                                        {{ $employee->name }}
                                    </div>

                                    @if (!empty($employee->employee_code))
                                        <div class="text-xs text-zinc-500">
                                            {{ $employee->employee_code }}
                                        </div>
                                    @endif
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-sm text-zinc-600 dark:text-zinc-300">
                                    {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
                                </td>

                                <td class="px-5 py-4">
                                    <form
                                        method="POST"
                                        action="{{ route('attendance.store') }}"
                                        class="flex flex-col gap-2 sm:flex-row"
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

                                        @if ($approvedLeave)

                                            <input
                                                type="hidden"
                                                name="status"
                                                value="On Leave"
                                            >

                                            <span class="inline-flex w-fit items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700 dark:bg-blue-950/50 dark:text-blue-300">
                                                On Leave
                                            </span>

                                        @else

                                            <select
                                                name="status"
                                                class="rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-zinc-500 focus:ring-2 focus:ring-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white"
                                            >
                                                <option value="" {{ $currentStatus === '' ? 'selected' : '' }}>
                                                    Not Marked
                                                </option>

                                                <option value="Present" {{ $currentStatus === 'Present' ? 'selected' : '' }}>
                                                    Present
                                                </option>

                                                <option value="Absent" {{ $currentStatus === 'Absent' ? 'selected' : '' }}>
                                                    Absent
                                                </option>

                                                <option value="Week Off" {{ $currentStatus === 'Week Off' ? 'selected' : '' }}>
                                                    Week Off
                                                </option>
                                            </select>

                                        @endif

                                </td>

                                <td class="px-5 py-4 text-right">

                                    @if ($approvedLeave)

                                        <button
                                            type="submit"
                                            class="rounded-lg bg-zinc-900 px-3 py-2 text-xs font-medium text-white dark:bg-white dark:text-zinc-900"
                                        >
                                            Confirm
                                        </button>

                                    @else

                                        <button
                                            type="submit"
                                            class="rounded-lg bg-zinc-900 px-3 py-2 text-xs font-medium text-white transition hover:bg-zinc-700 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200"
                                        >
                                            Save
                                        </button>

                                    @endif

                                    </form>

                                    @if ($record)

                                        <form
                                            method="POST"
                                            action="{{ route('attendance.destroy', $record) }}"
                                            class="mt-2"
                                            onsubmit="return confirm('Remove this attendance record?');"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="text-xs font-medium text-red-600 hover:underline dark:text-red-400"
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
                                    class="px-5 py-10 text-center text-sm text-zinc-500"
                                >
                                    No employees found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>

        {{-- Status explanation --}}
        <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">

            <h2 class="text-sm font-semibold text-zinc-900 dark:text-white">
                Attendance Status
            </h2>

            <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-5">

                <div class="rounded-lg bg-green-50 p-3 dark:bg-green-950/30">
                    <div class="text-sm font-semibold text-green-700 dark:text-green-300">
                        Present
                    </div>
                    <div class="mt-1 text-xs text-green-600 dark:text-green-400">
                        Employee attended work.
                    </div>
                </div>

                <div class="rounded-lg bg-red-50 p-3 dark:bg-red-950/30">
                    <div class="text-sm font-semibold text-red-700 dark:text-red-300">
                        Absent
                    </div>
                    <div class="mt-1 text-xs text-red-600 dark:text-red-400">
                        Employee was absent.
                    </div>
                </div>

                <div class="rounded-lg bg-blue-50 p-3 dark:bg-blue-950/30">
                    <div class="text-sm font-semibold text-blue-700 dark:text-blue-300">
                        On Leave
                    </div>
                    <div class="mt-1 text-xs text-blue-600 dark:text-blue-400">
                        Approved leave exists.
                    </div>
                </div>

                <div class="rounded-lg bg-zinc-100 p-3 dark:bg-zinc-800">
                    <div class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                        Week Off
                    </div>
                    <div class="mt-1 text-xs text-zinc-500">
                        Scheduled weekly off.
                    </div>
                </div>

                <div class="rounded-lg bg-orange-50 p-3 dark:bg-orange-950/30">
                    <div class="text-sm font-semibold text-orange-700 dark:text-orange-300">
                        ABWO
                    </div>
                    <div class="mt-1 text-xs text-orange-600 dark:text-orange-400">
                        Week Off affected by sandwich rule.
                    </div>
                </div>

            </div>
        </div>

    </div>
</x-layouts.app>