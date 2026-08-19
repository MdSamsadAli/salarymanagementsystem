<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Salary;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SalaryController extends Controller
{
    // public function index(Request $request)
    // {
    //     $query = Salary::with('employee')->orderByDesc('effective_from');

    //     if ($request->filled('employee_id')) {
    //         $query->where('employee_id', $request->employee_id);
    //     }

    //     if ($request->filled('status')) {
    //         $request->status === 'current'
    //             ? $query->whereNull('effective_to')
    //             : $query->whereNotNull('effective_to');
    //     }

    //     $salaries = $query->paginate(15)->withQueryString();
    //     $employees = Employee::orderBy('name')->get();

    //     return view('salaries.index', compact('salaries', 'employees'));
    // }

    public function create(Request $request)
    {
        $employees = Employee::whereDoesntHave('salaries', function ($query) {
            $query->whereNull('effective_to');
        })->get();
        $selectedEmployeeId = $request->query('employee_id');

        return view('salaries.create', compact('employees', 'selectedEmployeeId'));
    }


    public function show() {}

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'basic_salary' => 'required|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'effective_from' => 'required|date',
        ]);

        DB::transaction(function () use ($data) {
            // Close any currently open salary record for this employee
            Salary::where('employee_id', $data['employee_id'])
                ->whereNull('effective_to')
                ->update(['effective_to' => Carbon::parse($data['effective_from'])->subDay()]);

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

    public function update(Request $request, Salary $salary)
    {
        $data = $request->validate([
            'basic_salary' => 'required|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'effective_from' => 'required|date',
            'effective_to' => 'nullable|date|after_or_equal:effective_from',
        ]);

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
