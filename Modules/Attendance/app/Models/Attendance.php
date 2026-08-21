<?php

namespace Modules\Attendance\Models;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    public const STATUS_PRESENT  = 'present';
    public const STATUS_ABSENT   = 'absent';
    public const STATUS_LATE     = 'late';
    public const STATUS_HALF_DAY = 'half_day';
    public const STATUS_LEAVE    = 'leave';

    protected $fillable = [
        'employee_id',
        'attendance_date',
        'check_in',
        'check_out',
        'working_hours',
        'status',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'check_in'        => 'datetime',
        'check_out'        => 'datetime',
        'working_hours'    => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('attendance_date', today());
    }

    public function scopeForEmployee($query, int $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    public static function formatWorkingHours(?string $checkIn, ?string $checkOut = null): string
    {
        if (!$checkIn) {
            return '—';
        }

        $start = Carbon::parse($checkIn);
        $end = $checkOut ? Carbon::parse($checkOut) : now();
        $total = $start->diffInMinutes($end);
        $formatted = intdiv($total, 60) . ' hrs ' . ($total % 60) . ' mins';

        // if (!$checkOut) {
        //     $formatted .= ' <span class="text-muted small">(so far)</span>';
        // }

        return $formatted;
    }
}
