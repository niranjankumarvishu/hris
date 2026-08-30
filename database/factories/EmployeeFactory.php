<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'employee_code' => 'EMP' . fake()->unique()->numerify('####'),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->numerify('##########'),
            'department' => fake()->randomElement([
                'IT',
                'HR',
                'Finance',
                'Operations',
                'Sales',
            ]),
            'joining_date' => fake()->dateTimeBetween(
                '-2 years',
                'now'
            )->format('Y-m-d'),
            'paid_leave_balance' => 12,
            'sick_leave_balance' => 6,
        ];
    }
}