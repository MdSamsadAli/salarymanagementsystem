<?php

namespace Modules\Attendance\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Attendance\DataTables\AttendanceDataTable;
use Modules\Attendance\DataTables\AttendanceHistoryDataTable;
use Modules\Attendance\Models\Attendance;

class AttendanceController extends Controller
{

    public function index(AttendanceDataTable $dataTable)
    {
        return $dataTable->render('attendance::index');
    }

    public function CheckIn(Employee $employee)
    {
        $existing = Attendance::forEmployee($employee->id)->today()->first();

        if ($existing) {
            return back()->with('error', $employee->name . ' is already checked in today.');
        }

        $checkInTime = now();

        Attendance::create([
            'employee_id'     => $employee->id,
            'attendance_date' => today(),
            'check_in'        => $checkInTime,
            'status'          => Attendance::STATUS_PRESENT,
            // 'status'          => $checkInTime->gt(today()->setTime(10, 00))
            //     ? Attendance::STATUS_LATE
            //     : Attendance::STATUS_PRESENT,
        ]);

        return back()->with('success', $employee->name . ' checked in successfully.');
    }


    public function CheckOut(Employee $employee)
    {
        $attendance = Attendance::forEmployee($employee->id)->today()->first();

        if (!$attendance || !$attendance->check_in) {
            return back()->with('error', $employee->name . ' has not checked in today.');
        }

        if ($attendance->check_out) {
            return back()->with('error', $employee->name . ' is already checked out today.');
        }

        $checkOut = now();
        $workingHours = $attendance->check_in->diffInMinutes($checkOut) / 60;

        $attendance->update([
            'check_out'     => $checkOut,
            'working_hours' => round($workingHours, 2),
        ]);

        return back()->with('success', $employee->name . ' checked out successfully.');
    }


    public function history(Employee $employee, AttendanceHistoryDataTable $dataTable)
    {
        return $dataTable
            ->forEmployee($employee->id)
            ->render('attendance::history', compact('employee'));
    }
}
