<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_code',
        'name',
        'email',
        'phone',
        'department',
        'joining_date',
        'paid_leave_balance',
        'sick_leave_balance',
    ];

    protected $casts = [
        'joining_date' => 'date',
        'paid_leave_balance' => 'integer',
        'sick_leave_balance' => 'integer',
    ];

    public function leaves(): HasMany
    {
        return $this->hasMany(Leave::class);
    }
}