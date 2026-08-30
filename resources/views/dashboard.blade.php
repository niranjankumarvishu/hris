<x-layouts::app :title="__('Dashboard')">
<div class="flex justify-center">
    <a
        href="{{ route('dashboard') }}"
        wire:navigate
        class="inline-flex items-center rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-700 shadow-sm transition hover:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-200 dark:hover:bg-zinc-700"
    >
        ← Dashboard
    </a>
</div>
    <style>
        .hris-dashboard {
            min-height: 100%;
            width: 100%;
            color: #111827;
            background: #f3f4f6;
        }

        .hris-dashboard,
        .hris-dashboard * {
            box-sizing: border-box;
        }

        .hris-dashboard .dashboard-title {
            color: #111827 !important;
        }

        .hris-dashboard .dashboard-subtitle {
            color: #6b7280 !important;
        }

        .hris-dashboard .dashboard-filter {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        }

        .hris-dashboard .dashboard-label {
            color: #374151 !important;
        }

        .hris-dashboard .dashboard-input {
            width: 100%;
            background: #ffffff !important;
            color: #111827 !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem;
        }

        .hris-dashboard .dashboard-input:focus {
            border-color: #4f46e5 !important;
            outline: none;
            box-shadow: 0 0 0 1px #4f46e5;
        }

        .hris-dashboard .load-button {
            background: #2563eb !important;
            color: #ffffff !important;
            border: none !important;
        }

        .hris-dashboard .load-button:hover {
            background: #1d4ed8 !important;
        }

        .hris-dashboard .summary-card {
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        }

        .hris-dashboard .summary-title {
            font-size: 0.875rem;
            font-weight: 600;
        }

        .hris-dashboard .summary-number {
            margin-top: 0.5rem;
            font-size: 1.875rem;
            line-height: 2.25rem;
            font-weight: 700;
        }

        .hris-dashboard .summary-description {
            margin-top: 0.25rem;
            font-size: 0.75rem;
        }

        .hris-dashboard .card-total {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
        }

        .hris-dashboard .card-total .summary-title {
            color: #374151 !important;
        }

        .hris-dashboard .card-total .summary-number {
            color: #111827 !important;
        }

        .hris-dashboard .card-total .summary-description {
            color: #6b7280 !important;
        }

        .hris-dashboard .card-present {
            background: #f0fdf4 !important;
            border: 1px solid #bbf7d0 !important;
        }

        .hris-dashboard .card-present .summary-title {
            color: #15803d !important;
        }

        .hris-dashboard .card-present .summary-number {
            color: #16a34a !important;
        }

        .hris-dashboard .card-present .summary-description {
            color: #16a34a !important;
        }

        .hris-dashboard .card-absent {
            background: #fef2f2 !important;
            border: 1px solid #fecaca !important;
        }

        .hris-dashboard .card-absent .summary-title {
            color: #b91c1c !important;
        }

        .hris-dashboard .card-absent .summary-number {
            color: #dc2626 !important;
        }

        .hris-dashboard .card-absent .summary-description {
            color: #ef4444 !important;
        }

        .hris-dashboard .card-abwo {
            background: #fff7ed !important;
            border: 1px solid #fed7aa !important;
        }

        .hris-dashboard .card-abwo .summary-title {
            color: #c2410c !important;
        }

        .hris-dashboard .card-abwo .summary-subtitle {
            color: #c2410c !important;
        }

        .hris-dashboard .card-abwo .summary-number {
            color: #ea580c !important;
        }

        .hris-dashboard .card-abwo .summary-description {
            color: #c2410c !important;
        }

        .hris-dashboard .abwo-badge {
            background: #fed7aa !important;
            color: #9a3412 !important;
        }

        .hris-dashboard .card-leave {
            background: #fffbeb !important;
            border: 1px solid #fde68a !important;
        }

        .hris-dashboard .card-leave .summary-title {
            color: #b45309 !important;
        }

        .hris-dashboard .card-leave .summary-number {
            color: #d97706 !important;
        }

        .hris-dashboard .card-leave .summary-description {
            color: #ca8a04 !important;
        }

        .hris-dashboard .card-weekoff {
            background: #eff6ff !important;
            border: 1px solid #bfdbfe !important;
        }

        .hris-dashboard .card-weekoff .summary-title {
            color: #1d4ed8 !important;
        }

        .hris-dashboard .card-weekoff .summary-number {
            color: #2563eb !important;
        }

        .hris-dashboard .card-weekoff .summary-description {
            color: #3b82f6 !important;
        }

        .hris-dashboard .dashboard-period {
            color: #6b7280 !important;
        }

        .hris-dashboard .dashboard-error {
            background: #fef2f2 !important;
            border: 1px solid #fecaca !important;
            color: #b91c1c !important;
        }

        @media (max-width: 768px) {

            .hris-dashboard {
                padding: 1rem;
            }

        }
    </style>


    <div
        id="hris-dashboard"
        class="hris-dashboard flex min-h-full w-full flex-1 flex-col gap-6 p-6"
    >

        {{-- =========================================================
             HEADER
        ========================================================== --}}

        <div>

            <h1 class="dashboard-title text-2xl font-semibold">
                Dashboard
            </h1>

            <p class="dashboard-subtitle mt-1 text-sm">
                Attendance overview for the selected period.
            </p>

        </div>


        {{-- =========================================================
             DATE FILTER
        ========================================================== --}}

        <div class="dashboard-filter p-5">

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

                <div>

                    <label
                        for="from_date"
                        class="dashboard-label mb-2 block text-sm font-medium"
                    >
                        From Date
                    </label>

                    <input
                        type="date"
                        id="from_date"
                        class="dashboard-input px-3 py-2 text-sm"
                    >

                </div>


                <div>

                    <label
                        for="to_date"
                        class="dashboard-label mb-2 block text-sm font-medium"
                    >
                        To Date
                    </label>

                    <input
                        type="date"
                        id="to_date"
                        class="dashboard-input px-3 py-2 text-sm"
                    >

                </div>


                <div class="flex items-end">

                    <button
                        type="button"
                        id="loadAttendance"
                        class="load-button w-full rounded-lg px-4 py-2 text-sm font-semibold transition disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        Load Attendance
                    </button>

                </div>

            </div>

        </div>


        {{-- =========================================================
             SUMMARY CARDS
        ========================================================== --}}

        <div
            class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6"
        >

            {{-- Total Employees --}}

            <div class="summary-card card-total p-5">

                <p class="summary-title">
                    Total Employees
                </p>

                <p
                    id="total_employees"
                    class="summary-number"
                >
                    0
                </p>

                <p class="summary-description">
                    Employees
                </p>

            </div>


            {{-- Present --}}

            <div class="summary-card card-present p-5">

                <p class="summary-title">
                    Present Days
                </p>

                <p
                    id="present"
                    class="summary-number"
                >
                    0
                </p>

                <p class="summary-description">
                    Attendance days
                </p>

            </div>


            {{-- Absent --}}

            <div class="summary-card card-absent p-5">

                <p class="summary-title">
                    Absent Days
                </p>

                <p
                    id="absent"
                    class="summary-number"
                >
                    0
                </p>

                <p class="summary-description">
                    Normal absence
                </p>

            </div>


            {{-- ABWO --}}

            <div class="summary-card card-abwo p-5">

                <div class="flex items-start justify-between gap-2">

                    <div class="min-w-0">

                        <p class="summary-title">
                            ABWO Days
                        </p>

                        <p class="summary-subtitle mt-0.5 text-xs font-medium">
                            Sandwich
                        </p>

                    </div>

                    <span
                        class="abwo-badge shrink-0 rounded-full px-2 py-1 text-[10px] font-bold uppercase tracking-wide"
                    >
                        ABWO
                    </span>

                </div>

                <p
                    id="abwo"
                    class="summary-number"
                >
                    0
                </p>

                <p class="summary-description leading-4">
                    Absent because of Week Off sandwich
                </p>

            </div>


            {{-- Leave --}}

            <div class="summary-card card-leave p-5">

                <p class="summary-title">
                    Leave Days
                </p>

                <p
                    id="on_leave"
                    class="summary-number"
                >
                    0
                </p>

                <p class="summary-description">
                    Approved leave
                </p>

            </div>


            {{-- Week Off --}}

            <div class="summary-card card-weekoff p-5">

                <p class="summary-title">
                    Week Off Days
                </p>

                <p
                    id="week_off"
                    class="summary-number"
                >
                    0
                </p>

                <p class="summary-description">
                    Regular Week Off
                </p>

            </div>

        </div>


        {{-- =========================================================
             PERIOD
        ========================================================== --}}

        <div
            id="period"
            class="dashboard-period text-sm"
        >
            Loading attendance...
        </div>


        {{-- =========================================================
             ERROR
        ========================================================== --}}

        <div
            id="attendanceError"
            class="dashboard-error hidden rounded-lg p-4 text-sm"
        ></div>


        {{-- =========================================================
             JAVASCRIPT
        ========================================================== --}}

        <script>
            document.addEventListener('DOMContentLoaded', function () {

                const fromDate =
                    document.getElementById('from_date');

                const toDate =
                    document.getElementById('to_date');

                const loadButton =
                    document.getElementById('loadAttendance');

                const errorBox =
                    document.getElementById('attendanceError');

                const totalEmployees =
                    document.getElementById('total_employees');

                const present =
                    document.getElementById('present');

                const absent =
                    document.getElementById('absent');

                const abwo =
                    document.getElementById('abwo');

                const onLeave =
                    document.getElementById('on_leave');

                const weekOff =
                    document.getElementById('week_off');

                const period =
                    document.getElementById('period');


                // =====================================================
                // CURRENT MONTH DATES
                // =====================================================

                function getCurrentMonthDates() {

                    const now = new Date();

                    const year =
                        now.getFullYear();

                    const month =
                        String(now.getMonth() + 1)
                            .padStart(2, '0');

                    const firstDay =
                        `${year}-${month}-01`;

                    const lastDayDate =
                        new Date(
                            year,
                            now.getMonth() + 1,
                            0
                        );

                    const lastDay =
                        `${year}-${month}-${String(
                            lastDayDate.getDate()
                        ).padStart(2, '0')}`;

                    return {
                        firstDay,
                        lastDay
                    };
                }


                // =====================================================
                // FORMAT DATE
                // =====================================================

                function formatDate(dateString) {

                    if (!dateString) {
                        return '';
                    }

                    const parts =
                        dateString.split('-');

                    if (parts.length !== 3) {
                        return dateString;
                    }

                    return `${parts[2]}-${parts[1]}-${parts[0]}`;
                }


                // =====================================================
                // LOAD ATTENDANCE
                // =====================================================

                async function loadAttendanceSummary() {

                    errorBox.classList.add('hidden');

                    errorBox.textContent = '';

                    loadButton.disabled = true;

                    loadButton.textContent = 'Loading...';


                    try {

                        if (
                            !fromDate.value ||
                            !toDate.value
                        ) {
                            throw new Error(
                                'Please select both From Date and To Date.'
                            );
                        }


                        if (
                            fromDate.value >
                            toDate.value
                        ) {
                            throw new Error(
                                'From Date cannot be after To Date.'
                            );
                        }


                        const url =
                            `/api/attendance-summary?from_date=${encodeURIComponent(fromDate.value)}&to_date=${encodeURIComponent(toDate.value)}`;


                        const response =
                            await fetch(url, {
                                headers: {
                                    'Accept': 'application/json'
                                }
                            });


                        let data;

                        try {

                            data =
                                await response.json();

                        } catch (jsonError) {

                            throw new Error(
                                'The server returned an invalid response.'
                            );

                        }


                        if (!response.ok) {

                            throw new Error(
                                data.message ||
                                'Unable to load attendance.'
                            );

                        }


                        // =================================================
                        // UPDATE DASHBOARD
                        // =================================================

                        totalEmployees.textContent =
                            data.total_employees ?? 0;

                        present.textContent =
                            data.present ?? 0;

                        absent.textContent =
                            data.absent ?? 0;

                        abwo.textContent =
                            data.abwo ?? 0;

                        onLeave.textContent =
                            data.on_leave ?? 0;

                        weekOff.textContent =
                            data.week_off ?? 0;


                        period.textContent =
                            `Showing attendance from ${formatDate(data.from_date)} to ${formatDate(data.to_date)}`;

                    } catch (error) {

                        errorBox.textContent =
                            error.message ||
                            'Unable to load attendance.';

                        errorBox.classList.remove('hidden');

                    } finally {

                        loadButton.disabled = false;

                        loadButton.textContent =
                            'Load Attendance';

                    }

                }


                // =====================================================
                // SET DEFAULT DATES
                // =====================================================

                const dates =
                    getCurrentMonthDates();


                fromDate.value =
                    dates.firstDay;

                toDate.value =
                    dates.lastDay;


                // =====================================================
                // LOAD BUTTON
                // =====================================================

                loadButton.addEventListener(
                    'click',
                    loadAttendanceSummary
                );


                // =====================================================
                // AUTOMATIC LOAD
                // =====================================================

                loadAttendanceSummary();

            });
        </script>

    </div>

</x-layouts::app>