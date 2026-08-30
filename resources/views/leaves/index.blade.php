<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Leave Management</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen">

<div class="max-w-7xl mx-auto py-8 px-4">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">

        <div>
            <h1 class="text-2xl font-semibold text-gray-900">
                Leave Management
            </h1>

            <p class="text-sm text-gray-500 mt-1">
                View and manage employee leave applications.
            </p>
        </div>

        <a
            href="{{ route('leaves.create') }}"
            class="text-blue-600 hover:text-blue-800 font-medium"
        >
            + Apply Leave
        </a>

    </div>

    {{-- Success message --}}
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- Error message --}}
    @if ($errors->any())
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Leave table --}}
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="text-left px-6 py-4 font-medium text-gray-600">
                            Employee
                        </th>

                        <th class="text-left px-6 py-4 font-medium text-gray-600">
                            Leave Type
                        </th>

                        <th class="text-left px-6 py-4 font-medium text-gray-600">
                            From
                        </th>

                        <th class="text-left px-6 py-4 font-medium text-gray-600">
                            To
                        </th>

                        <th class="text-left px-6 py-4 font-medium text-gray-600">
                            Status
                        </th>

                        <th class="text-left px-6 py-4 font-medium text-gray-600">
                            Actions
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @forelse ($leaves as $leave)

                        <tr class="hover:bg-gray-50">

                            {{-- Employee --}}
                            <td class="px-6 py-5">

                                <div class="font-medium text-gray-900">
                                    {{ $leave->employee->name }}
                                </div>

                                <div class="text-gray-500 text-xs mt-1">
                                    {{ $leave->employee->employee_code }}
                                </div>

                            </td>

                            {{-- Leave Type --}}
                            <td class="px-6 py-5 text-gray-700">
                                {{ $leave->leave_type }}
                            </td>

                            {{-- From --}}
                            <td class="px-6 py-5 text-gray-700">
                                {{ $leave->from_date->format('d M Y') }}
                            </td>

                            {{-- To --}}
                            <td class="px-6 py-5 text-gray-700">
                                {{ $leave->to_date->format('d M Y') }}
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-5">

                                @if ($leave->status === 'Pending')

                                    <span class="text-yellow-600 font-medium">
                                        Pending
                                    </span>

                                @elseif ($leave->status === 'Approved')

                                    <span class="text-green-600 font-medium">
                                        Approved
                                    </span>

                                @elseif ($leave->status === 'Rejected')

                                    <span class="text-red-600 font-medium">
                                        Rejected
                                    </span>

                                @else

                                    <span class="text-gray-600">
                                        {{ $leave->status }}
                                    </span>

                                @endif

                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-5">

                                <div class="flex flex-wrap items-center gap-3">

                                    {{-- View --}}
                                    <a
                                        href="{{ route('leaves.show', $leave) }}"
                                        class="text-blue-600 hover:text-blue-800"
                                    >
                                        View
                                    </a>

                                    {{-- Pending actions --}}
                                    @if ($leave->status === 'Pending')

                                        {{-- Approve --}}
                                        <form
                                            method="POST"
                                            action="{{ route('leaves.approve', $leave) }}"
                                            class="inline"
                                        >
                                            @csrf

                                            <input
                                                type="text"
                                                name="admin_remarks"
                                                placeholder="Admin remarks"
                                                class="border border-gray-300 rounded-md px-2 py-1 text-xs w-32"
                                            >

                                            <button
                                                type="submit"
                                                class="text-green-600 hover:text-green-800 font-medium ml-1"
                                                onclick="return confirm('Approve this leave application?')"
                                            >
                                                Approve
                                            </button>
                                        </form>

                                        {{-- Reject --}}
                                        <form
                                            method="POST"
                                            action="{{ route('leaves.reject', $leave) }}"
                                            class="inline"
                                        >
                                            @csrf

                                            <button
                                                type="submit"
                                                class="text-red-600 hover:text-red-800 font-medium"
                                                onclick="return confirm('Reject this leave application?')"
                                            >
                                                Reject
                                            </button>
                                        </form>

                                    @endif

                                    {{-- Edit --}}
                                    <a
                                        href="{{ route('leaves.edit', $leave) }}"
                                        class="text-gray-600 hover:text-gray-900"
                                    >
                                        Edit
                                    </a>

                                    {{-- Delete --}}
                                    <form
                                        method="POST"
                                        action="{{ route('leaves.destroy', $leave) }}"
                                        class="inline"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="text-red-600 hover:text-red-800"
                                            onclick="return confirm('Delete this leave application?')"
                                        >
                                            Delete
                                        </button>
                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td
                                colspan="6"
                                class="px-6 py-10 text-center text-gray-500"
                            >
                                No leave applications found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- Pagination --}}
        @if ($leaves->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $leaves->links() }}
            </div>
        @endif

    </div>

</div>

</body>
</html>