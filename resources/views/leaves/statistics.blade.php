<x-layouts::app :title="__('Leave Statistics')">

    {{-- =========================================================
         LIGHT PAGE CONTAINER
    ========================================================== --}}
    <div class="min-h-full w-full bg-gray-50">

        <div class="flex w-full flex-1 flex-col gap-6 p-6">

            {{-- =====================================================
                 HEADER
            ====================================================== --}}
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">
                    Leave Statistics
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Overview of employee leave applications and balances.
                </p>
            </div>


            {{-- =====================================================
                 LEAVE APPLICATION SUMMARY
            ====================================================== --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

                {{-- Total Leaves --}}
                <div
                    class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm"
                >
                    <p class="text-sm font-medium text-gray-500">
                        Total Leaves
                    </p>

                    <p class="mt-2 text-3xl font-bold text-gray-900">
                        {{ $statistics['total'] ?? 0 }}
                    </p>

                    <p class="mt-1 text-xs text-gray-500">
                        All leave applications
                    </p>
                </div>


                {{-- Pending --}}
                <div
                    class="rounded-xl border border-yellow-200 bg-yellow-50 p-5 shadow-sm"
                >
                    <p class="text-sm font-medium text-yellow-700">
                        Pending
                    </p>

                    <p class="mt-2 text-3xl font-bold text-yellow-600">
                        {{ $statistics['pending'] ?? 0 }}
                    </p>

                    <p class="mt-1 text-xs text-yellow-600">
                        Awaiting approval
                    </p>
                </div>


                {{-- Approved --}}
                <div
                    class="rounded-xl border border-green-200 bg-green-50 p-5 shadow-sm"
                >
                    <p class="text-sm font-medium text-green-700">
                        Approved
                    </p>

                    <p class="mt-2 text-3xl font-bold text-green-600">
                        {{ $statistics['approved'] ?? 0 }}
                    </p>

                    <p class="mt-1 text-xs text-green-600">
                        Approved applications
                    </p>
                </div>


                {{-- Rejected --}}
                <div
                    class="rounded-xl border border-red-200 bg-red-50 p-5 shadow-sm"
                >
                    <p class="text-sm font-medium text-red-700">
                        Rejected
                    </p>

                    <p class="mt-2 text-3xl font-bold text-red-600">
                        {{ $statistics['rejected'] ?? 0 }}
                    </p>

                    <p class="mt-1 text-xs text-red-600">
                        Rejected applications
                    </p>
                </div>

            </div>


            {{-- =====================================================
                 LEAVE TYPE OVERVIEW
            ====================================================== --}}
            <div>

                <div class="mb-3">

                    <h2 class="text-lg font-semibold text-gray-900">
                        Leave Type Overview
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Breakdown of leave types and approved leave days.
                    </p>

                </div>


                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

                    {{-- Paid Leave --}}
                    <div
                        class="rounded-xl border border-blue-200 bg-blue-50 p-5 shadow-sm"
                    >
                        <p class="text-sm font-medium text-blue-700">
                            Paid Leave
                        </p>

                        <p class="mt-2 text-3xl font-bold text-blue-600">
                            {{ $statistics['paid'] ?? 0 }}
                        </p>

                        <p class="mt-1 text-xs text-blue-600">
                            Paid leave applications
                        </p>
                    </div>


                    {{-- Sick Leave --}}
                    <div
                        class="rounded-xl border border-purple-200 bg-purple-50 p-5 shadow-sm"
                    >
                        <p class="text-sm font-medium text-purple-700">
                            Sick Leave
                        </p>

                        <p class="mt-2 text-3xl font-bold text-purple-600">
                            {{ $statistics['sick'] ?? 0 }}
                        </p>

                        <p class="mt-1 text-xs text-purple-600">
                            Sick leave applications
                        </p>
                    </div>


                    {{-- Casual Leave --}}
                    <div
                        class="rounded-xl border border-cyan-200 bg-cyan-50 p-5 shadow-sm"
                    >
                        <p class="text-sm font-medium text-cyan-700">
                            Casual Leave
                        </p>

                        <p class="mt-2 text-3xl font-bold text-cyan-600">
                            {{ $statistics['casual'] ?? 0 }}
                        </p>

                        <p class="mt-1 text-xs text-cyan-600">
                            Casual leave applications
                        </p>
                    </div>


                    {{-- Approved Leave Days --}}
                    <div
                        class="rounded-xl border border-indigo-200 bg-indigo-50 p-5 shadow-sm"
                    >
                        <p class="text-sm font-medium text-indigo-700">
                            Approved Leave Days
                        </p>

                        <p class="mt-2 text-3xl font-bold text-indigo-600">
                            {{ $statistics['approved_days'] ?? 0 }}
                        </p>

                        <p class="mt-1 text-xs text-indigo-600">
                            Total approved days
                        </p>
                    </div>

                </div>

            </div>


            {{-- =====================================================
                 EMPLOYEE LEAVE STATISTICS
            ====================================================== --}}
            <div
                class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm"
            >

                {{-- Table Header --}}
                <div
                    class="border-b border-gray-200 bg-white px-5 py-4"
                >
                    <h2 class="text-lg font-semibold text-gray-900">
                        Employee Leave Statistics
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Leave applications and remaining leave balances by employee.
                    </p>
                </div>


                {{-- Table --}}
                <div class="overflow-x-auto">

                    <table class="w-full min-w-[900px] text-left text-sm">

                        {{-- Table Head --}}
                        <thead class="border-b border-gray-200 bg-gray-50">

                            <tr>

                                <th
                                    class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-gray-600"
                                >
                                    Employee
                                </th>

                                <th
                                    class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-gray-600"
                                >
                                    Total
                                </th>

                                <th
                                    class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-gray-600"
                                >
                                    Pending
                                </th>

                                <th
                                    class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-gray-600"
                                >
                                    Approved
                                </th>

                                <th
                                    class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-gray-600"
                                >
                                    Rejected
                                </th>

                                <th
                                    class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-gray-600"
                                >
                                    Paid Balance
                                </th>

                                <th
                                    class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-gray-600"
                                >
                                    Sick Balance
                                </th>

                            </tr>

                        </thead>


                        {{-- Table Body --}}
                        <tbody class="divide-y divide-gray-200 bg-white">

                            @forelse($employeeStatistics as $employee)

                                <tr class="transition hover:bg-gray-50">

                                    {{-- Employee --}}
                                    <td class="px-5 py-4">

                                        <div class="font-semibold text-gray-900">
                                            {{ $employee->name }}
                                        </div>

                                        <div class="mt-0.5 text-xs text-gray-500">
                                            Employee
                                        </div>

                                    </td>


                                    {{-- Total --}}
                                    <td class="px-5 py-4">

                                        <span
                                            class="inline-flex min-w-8 items-center justify-center rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-700"
                                        >
                                            {{ $employee->total_leaves ?? 0 }}
                                        </span>

                                    </td>


                                    {{-- Pending --}}
                                    <td class="px-5 py-4">

                                        <span
                                            class="inline-flex min-w-8 items-center justify-center rounded-full bg-yellow-100 px-2.5 py-1 text-xs font-semibold text-yellow-700"
                                        >
                                            {{ $employee->pending_leaves ?? 0 }}
                                        </span>

                                    </td>


                                    {{-- Approved --}}
                                    <td class="px-5 py-4">

                                        <span
                                            class="inline-flex min-w-8 items-center justify-center rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700"
                                        >
                                            {{ $employee->approved_leaves ?? 0 }}
                                        </span>

                                    </td>


                                    {{-- Rejected --}}
                                    <td class="px-5 py-4">

                                        <span
                                            class="inline-flex min-w-8 items-center justify-center rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700"
                                        >
                                            {{ $employee->rejected_leaves ?? 0 }}
                                        </span>

                                    </td>


                                    {{-- Paid Balance --}}
                                    <td class="px-5 py-4">

                                        <div class="font-semibold text-gray-900">
                                            {{ $employee->paid_leave_balance ?? 0 }}
                                        </div>

                                        <div class="text-xs text-gray-500">
                                            days remaining
                                        </div>

                                    </td>


                                    {{-- Sick Balance --}}
                                    <td class="px-5 py-4">

                                        <div class="font-semibold text-gray-900">
                                            {{ $employee->sick_leave_balance ?? 0 }}
                                        </div>

                                        <div class="text-xs text-gray-500">
                                            days remaining
                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="7"
                                        class="px-5 py-12 text-center"
                                    >

                                        <div
                                            class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100"
                                        >
                                            <svg
                                                class="h-6 w-6 text-gray-400"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M9 12h6m-6 4h4m5-9.5V19a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7.5L18 6.5V19"
                                                />

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M15 3v4h4"
                                                />
                                            </svg>
                                        </div>

                                        <p class="mt-3 text-sm font-medium text-gray-900">
                                            No employee data available
                                        </p>

                                        <p class="mt-1 text-xs text-gray-500">
                                            Employee leave statistics will appear here.
                                        </p>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</x-layouts::app>