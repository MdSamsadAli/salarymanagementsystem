<?php

namespace App\Http\Controllers;

use App\Http\Requests\SalaryRequest;
use App\Models\Employee;
use App\Models\Salary;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SalaryController extends Controller
{
    public function create(Request $request)
    {
        $employees = Employee::orderBy('name')->get();
        $selectedEmployeeId = $request->query('employee_id');

        return view('salaries.create', compact('employees', 'selectedEmployeeId'));
    }

    public function show() {}

    public function store(SalaryRequest $request)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data) {
            $openSalary = Salary::where('employee_id', $data['employee_id'])
                ->whereNull('effective_to')
                ->orderByDesc('effective_from')
                ->first();

            if ($openSalary && Carbon::parse($data['effective_from'])->lte($openSalary->effective_from)) {
                throw ValidationException::withMessages([
                    'effective_from' => 'The effective date must be after the current salary\'s effective date ('
                        . $openSalary->effective_from->format('Y-m-d') . ').',
                ]);
            }

            if ($openSalary) {
                $openSalary->update([
                    'effective_to' => Carbon::parse($data['effective_from'])->subDay(),
                ]);
            }

            Salary::create([
                'employee_id' => $data['employee_id'],
                'basic_salary' => $data['basic_salary'],
                'allowances' => $data['allowances'] ?? 0,
                'gross_salary' => $data['basic_salary'] + ($data['allowances'] ?? 0),
                'effective_from' => $data['effective_from'],
                'effective_to' => null,
            ]);
        });

        return redirect()
            ->route('employee.salary-history', $data['employee_id'])
            ->with('success', 'Salary record added.');
    }

    public function edit(Salary $salary)
    {
        $employees = Employee::orderBy('name')->get();
        return view('salaries.edit', compact('salary', 'employees'));
    }

    public function update(SalaryRequest $request, Salary $salary)
    {
        $data = $request->validated();

        $newFrom = Carbon::parse($data['effective_from']);
        $newTo = isset($data['effective_to']) ? Carbon::parse($data['effective_to']) : null;

        $overlaps = Salary::where('employee_id', $salary->employee_id)
            ->where('id', '!=', $salary->id)
            ->where(function ($q) use ($newFrom) {
                $q->whereNull('effective_to')
                    ->orWhere('effective_to', '>=', $newFrom);
            })
            ->when($newTo, fn($q) => $q->where('effective_from', '<=', $newTo))
            ->exists();

        if ($overlaps) {
            throw ValidationException::withMessages([
                'effective_from' => 'This date range overlaps with another salary record for this employee.',
            ]);
        }

        $salary->update([
            'basic_salary' => $data['basic_salary'],
            'allowances' => $data['allowances'] ?? 0,
            'gross_salary' => $data['basic_salary'] + ($data['allowances'] ?? 0),
            'effective_from' => $data['effective_from'],
            'effective_to' => $data['effective_to'] ?? null,
        ]);

        return redirect()
            ->route('employee.salary-history', $salary->employee_id)
            ->with('success', 'Salary record updated.');
    }

    public function destroy(Salary $salary)
    {
        $employeeId = $salary->employee_id;
        $salary->delete();

        return redirect()
            ->route('employee.salary-history', $employeeId)
            ->with('success', 'Salary record deleted.');
    }
}
