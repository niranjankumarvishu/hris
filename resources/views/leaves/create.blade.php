<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply Leave</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen">

<div class="max-w-3xl mx-auto py-10 px-4">

    <div class="bg-white rounded-xl shadow-sm border">

        <div class="px-6 py-5 border-b">
            <h1 class="text-2xl font-semibold text-gray-900">
                Apply Leave
            </h1>

            <p class="text-sm text-gray-500 mt-1">
                Submit a new leave application.
            </p>
        </div>

        <form method="POST" action="{{ route('leaves.store') }}" class="p-6 space-y-6">

            @csrf

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-4">
                    <p class="font-semibold mb-2">
                        Please correct the following errors:
                    </p>

                    <ul class="list-disc ml-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Employee
                </label>

                <select
                    name="employee_id"
                    class="w-full rounded-lg border-gray-300"
                    required
                >
                    <option value="">Select employee</option>

                    @foreach ($employees as $employee)
                        <option
                            value="{{ $employee->id }}"
                            @selected(old('employee_id') == $employee->id)
                        >
                            {{ $employee->employee_code }} - {{ $employee->name }}
                        </option>
                    @endforeach
                </select>

                @error('employee_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Leave Type
                </label>

                <select
                    name="leave_type"
                    class="w-full rounded-lg border-gray-300"
                    required
                >
                    <option value="">Select leave type</option>

                    <option value="Paid" @selected(old('leave_type') === 'Paid')}>
                        Paid Leave
                    </option>

                    <option value="Sick" @selected(old('leave_type') === 'Sick')}>
                        Sick Leave
                    </option>

                    <option value="Casual" @selected(old('leave_type') === 'Casual')}>
                        Casual Leave
                    </option>
                </select>

                @error('leave_type')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        From Date
                    </label>

                    <input
                        type="date"
                        name="from_date"
                        value="{{ old('from_date') }}"
                        class="w-full rounded-lg border-gray-300"
                        required
                    >

                    @error('from_date')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        To Date
                    </label>

                    <input
                        type="date"
                        name="to_date"
                        value="{{ old('to_date') }}"
                        class="w-full rounded-lg border-gray-300"
                        required
                    >

                    @error('to_date')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Reason
                </label>

                <textarea
                    name="reason"
                    rows="4"
                    class="w-full rounded-lg border-gray-300"
                    placeholder="Enter reason for leave..."
                >{{ old('reason') }}</textarea>

                @error('reason')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between border-t pt-6">

                <a
                    href="{{ route('leaves.index') }}"
                    class="text-gray-600 hover:text-gray-900"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium"
                >
                    Apply Leave
                </button>

            </div>

        </form>

    </div>

</div>

</body>
</html>