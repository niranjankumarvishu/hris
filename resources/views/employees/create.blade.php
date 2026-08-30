<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Employee - HRIS</title>

    @vite(['resources/css/app.css'])
</head>

<body class="bg-gray-100 min-h-screen">

<div class="max-w-3xl mx-auto px-6 py-8">

    <div class="mb-6">

        <a
            href="{{ route('employees.index') }}"
            class="text-blue-600 hover:underline"
        >
            ← Back to Employees
        </a>

        <h1 class="text-3xl font-bold text-gray-900 mt-4">
            Add Employee
        </h1>

        <p class="text-gray-600">
            Enter employee information below.
        </p>

    </div>


    @if($errors->any())

        <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-5">

            <ul class="list-disc ml-5">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        action="{{ route('employees.store') }}"
        method="POST"
        class="bg-white rounded-xl shadow-sm p-6"
    >

        @csrf


        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <div>
                <label class="block font-medium mb-2">
                    Employee Code
                </label>

                <input
                    type="text"
                    name="employee_code"
                    value="{{ old('employee_code') }}"
                    required
                    class="w-full border rounded-lg px-4 py-3"
                    placeholder="EMP001"
                >
            </div>


            <div>
                <label class="block font-medium mb-2">
                    Name
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    class="w-full border rounded-lg px-4 py-3"
                    placeholder="John Doe"
                >
            </div>


            <div>
                <label class="block font-medium mb-2">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    class="w-full border rounded-lg px-4 py-3"
                    placeholder="john@example.com"
                >
            </div>


            <div>
                <label class="block font-medium mb-2">
                    Phone
                </label>

                <input
                    type="text"
                    name="phone"
                    value="{{ old('phone') }}"
                    class="w-full border rounded-lg px-4 py-3"
                    placeholder="9876543210"
                >
            </div>


            <div>
                <label class="block font-medium mb-2">
                    Department
                </label>

                <input
                    type="text"
                    name="department"
                    value="{{ old('department') }}"
                    required
                    class="w-full border rounded-lg px-4 py-3"
                    placeholder="IT"
                >
            </div>


            <div>
                <label class="block font-medium mb-2">
                    Joining Date
                </label>

                <input
                    type="date"
                    name="joining_date"
                    value="{{ old('joining_date') }}"
                    required
                    class="w-full border rounded-lg px-4 py-3"
                >
            </div>

        </div>


        <div class="flex justify-end gap-3 mt-6">

            <a
                href="{{ route('employees.index') }}"
                class="px-5 py-3 border rounded-lg"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="bg-blue-600 text-white px-5 py-3 rounded-lg hover:bg-blue-700"
            >
                Save Employee
            </button>

        </div>

    </form>

</div>

</body>
</html>
