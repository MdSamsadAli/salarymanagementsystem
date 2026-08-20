<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'name',
        'address',
        'designation',
        'date_of_joining',
    ];

    protected $casts = [
        'date_of_joining' => 'date',
    ];


    public function salaries()
    {
        return \DB::table('salaries')->where('employee_id', $this->id);
    }


    public function currentSalary()
    {
        $today = now()->toDateString();

        $salary = \DB::table('salaries')
            ->where('employee_id', $this->id)
            ->whereDate('effective_from', '<=', $today)
            ->where(function ($q) use ($today) {
                $q->whereNull('effective_to')
                    ->orWhereDate('effective_to', '>=', $today);
            })
            ->orderByDesc('effective_from')
            ->first();

        if ($salary) {
            $salary->effective_from = \Carbon\Carbon::parse($salary->effective_from);
            $salary->effective_to = $salary->effective_to
                ? \Carbon\Carbon::parse($salary->effective_to)
                : null;
        }

        return $salary;
    }

    public function upcomingSalary()
    {
        $today = now()->toDateString();

        $salary = \DB::table('salaries')
            ->where('employee_id', $this->id)
            ->whereDate('effective_from', '>', $today)
            ->orderBy('effective_from')
            ->first();

        if ($salary) {
            $salary->effective_from = \Carbon\Carbon::parse($salary->effective_from);
            $salary->effective_to = $salary->effective_to
                ? \Carbon\Carbon::parse($salary->effective_to)
                : null;
        }

        return $salary;
    }
}
