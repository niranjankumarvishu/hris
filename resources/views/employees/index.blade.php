<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employees - HRIS</title>

    @vite(['resources/css/app.css'])
</head>

<body class="bg-gray-100 min-h-screen">

<div class="max-w-7xl mx-auto px-6 py-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                Employee Management
            </h1>

            <p class="text-gray-600 mt-1">
                Manage your organization employees.
            </p>
        </div>

        <a
            href="{{ route('employees.create') }}"
            class="bg-blue-600 text-white px-5 py-3 rounded-lg hover:bg-blue-700"
        >
            + Add Employee
        </a>
    </div>


    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-3 rounded-lg mb-5">
            {{ session('success') }}
        </div>
    @endif


    <div class="bg-white rounded-xl shadow-sm overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="text-left px-6 py-4">Code</th>
                        <th class="text-left px-6 py-4">Name</th>
                        <th class="text-left px-6 py-4">Email</th>
                        <th class="text-left px-6 py-4">Phone</th>
                        <th class="text-left px-6 py-4">Department</th>
                        <th class="text-left px-6 py-4">Joining Date</th>
                        <th class="text-right px-6 py-4">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                @forelse($employees as $employee)

                    <tr class="hover:bg-gray-50">

                        <td class="px-6 py-4 font-medium">
                            {{ $employee->employee_code }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $employee->name }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $employee->email }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $employee->phone ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $employee->department }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $employee->joining_date->format('d M Y') }}
                        </td>

                        <td class="px-6 py-4 text-right">

                            <a
                                href="{{ route('employees.edit', $employee) }}"
                                class="text-blue-600 hover:underline mr-4"
                            >
                                Edit
                            </a>

                            <form
                                action="{{ route('employees.destroy', $employee) }}"
                                method="POST"
                                class="inline"
                                onsubmit="return confirm('Are you sure you want to delete this employee?')"
                            >

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="text-red-600 hover:underline"
                                >
                                    Delete
                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            No employees found.
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>


        @if($employees->hasPages())

            <div class="px-6 py-4 border-t">
                {{ $employees->links() }}
            </div>

        @endif

    </div>

</div>

</body>
</html>
