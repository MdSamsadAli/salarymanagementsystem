<?php

namespace App\Http\Controllers;

use App\DataTables\EmployeeDataTable;
use App\DataTables\SalaryHistoryDataTable;
use App\Http\Requests\EmployeeRequest;
use App\Models\Category;
use App\Models\Employee;
use App\Models\News;
use Illuminate\Http\Request;

use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    // public function index()
    // {
    //     $employees = Employee::with('currentSalary')->latest()->paginate(15);
    //     return view('employees.index', compact('employees'));
    // }

    public function admin()
    {
        $employeeCount = Employee::count();
        $newsCount = News::count();
        $categoryCount = Category::count();

        return view('admin', compact(
            'employeeCount',
            'newsCount',
            'categoryCount'
        ));
    }

    public function index(EmployeeDataTable $dataTable)
    {
        return $dataTable->render('employees.index');
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(EmployeeRequest $request)
    {
        // $data = $request->validate([
        //     'name' => 'required|string|max:255',
        //     'address' => 'nullable|string|max:255',
        //     'designation' => 'nullable|string|max:255',
        //     'date_of_joining' => 'required|date',
        // ]);

        $data = $request->validated();
        // dd($data);

        Employee::create($data);

        return redirect()->route('employee.index')->with('success', 'Employee created Successfully.');
    }

    public function show(Employee $employee)
    {
        $employee->current_salary = $employee->currentSalary();

        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(EmployeeRequest $request, Employee $employee)
    {
        // $data = $request->validate([
        //     'name' => 'required|string|max:255',
        //     'address' => 'nullable|string|max:255',
        //     'designation' => 'nullable|string|max:255',
        //     'date_of_joining' => 'required|date',
        // ]);

        $data = $request->validated();

        $employee->update($data);

        return redirect()->route('employee.index')->with('success', 'Employee updated Successfully');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employee.index')->with('success', 'Employee deleted.');
    }

    public function salaryHistory(Request $request, $id, SalaryHistoryDataTable $dataTable)
    {
        $employee = Employee::findOrFail($id);

        if ($request->ajax()) {
            return $dataTable->employee($employee)->ajax();
        }
        // $today = \Carbon\Carbon::today();

        // $currentSalary = $employee->salaries()
        //     ->whereDate('effective_from', '<=', $today)
        //     ->where(function ($q) use ($today) {
        //         $q->whereNull('effective_to')
        //             ->orWhereDate('effective_to', '>=', $today);
        //     })
        //     ->orderByDesc('effective_from')
        //     ->first();

        // $upcomingSalary = $employee->salaries()
        //     ->whereDate('effective_from', '>', $today)
        //     ->orderBy('effective_from')
        //     ->first();
        $currentSalary = $employee->currentSalary();
        $upcomingSalary = $employee->upcomingSalary();

        return $dataTable->employee($employee)->render('employees.salary-history', compact(
            'employee',
            'currentSalary',
            'upcomingSalary'
        ));
    }
}
