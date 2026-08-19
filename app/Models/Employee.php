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
        return $this->hasMany(Salary::class);
    }

    public function currentSalary()
    {
        return $this->hasOne(Salary::class)
            ->whereDate('effective_from', '<=', now())
            ->where(function ($q) {
                $q->whereNull('effective_to')
                    ->orWhereDate('effective_to', '>=', now());
            })
            ->orderByDesc('effective_from');
    }
}
