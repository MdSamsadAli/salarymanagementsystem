<?php

namespace App\Http\Controllers;

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

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $employees = Employee::with('currentSalary')->latest();

            return DataTables::eloquent($employees)
                ->addIndexColumn()
                ->editColumn(
                    'date_of_joining',
                    fn($employee) =>
                    $employee->date_of_joining?->format('Y-m-d')
                )
                ->addColumn(
                    'current_gross_salary',
                    fn($employee) =>
                    $employee->currentSalary
                        ? number_format($employee->currentSalary->gross_salary, 2)
                        : '—'
                )
                ->addColumn(
                    'action',
                    fn($employee) =>
                    view('employees.partials.action', compact('employee'))->render()
                )
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('employees.index');
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

        $data = $request->all();
        // dd($data);

        Employee::create($data);

        return redirect()->route('employee.index')->with('success', 'Employee created Successfully.');
    }

    public function show(Employee $employee)
    {
        $employee->load('currentSalary');
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

        $data = $request->all();

        $employee->update($data);

        return redirect()->route('employee.index')->with('success', 'Employee updated Successfully');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employee.index')->with('success', 'Employee deleted.');
    }

    public function salaryHistory(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        if ($request->ajax()) {

            $salaries = $employee->salaries()
                ->orderByDesc('effective_from');

            return DataTables::eloquent($salaries)
                ->addIndexColumn()

                ->addColumn('previous_salary', function ($salary) {

                    $previous = $salary->employee->salaries()
                        ->where('effective_from', '<', $salary->effective_from)
                        ->orderByDesc('effective_from')
                        ->first();

                    return $previous
                        ? number_format($previous->basic_salary, 2)
                        : '—';
                })

                ->editColumn('basic_salary', function ($salary) {
                    return number_format($salary->basic_salary, 2);
                })

                ->editColumn('allowances', function ($salary) {
                    return number_format($salary->allowances, 2);
                })

                ->editColumn('gross_salary', function ($salary) {
                    return number_format($salary->gross_salary, 2);
                })

                ->editColumn('effective_from', function ($salary) {
                    return $salary->effective_from->format('Y-m-d');
                })

                ->editColumn('effective_to', function ($salary) {
                    return $salary->effective_to
                        ? $salary->effective_to->format('Y-m-d')
                        : '—';
                })

                ->addColumn('status', function ($salary) {

                    $today = now();

                    $isCurrent =
                        $salary->effective_from->lte($today) &&
                        (
                            is_null($salary->effective_to) ||
                            $salary->effective_to->gte($today)
                        );

                    $isUpcoming = $salary->effective_from->gt($today);

                    if ($isCurrent) {
                        return '<span class="badge rounded-pill text-bg-success">
                                Current
                            </span>';
                    }

                    if ($isUpcoming) {
                        return '<span class="badge rounded-pill text-bg-warning text-dark">
                                Upcoming
                            </span>';
                    }

                    return '<span class="badge rounded-pill text-bg-secondary">
                            Past
                        </span>';
                })

                ->addColumn('action', function ($salary) {

                    return '
                    <a href="' . route('salaries.edit', $salary) . '"
                       class="btn btn-sm btn-outline-primary">
                        Edit
                    </a>

                    <form action="' . route('salaries.destroy', $salary) . '"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm(\'Delete this record?\')">

                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '

                        <button type="submit"
                                class="btn btn-sm btn-outline-danger">
                            Delete
                        </button>

                    </form>
                ';
                })

                ->rawColumns(['status', 'action'])
                ->make(true);
        }


        // Normal page request

        $today = \Carbon\Carbon::today();

        $currentSalary = $employee->salaries()
            ->whereDate('effective_from', '<=', $today)
            ->where(function ($q) use ($today) {
                $q->whereNull('effective_to')
                    ->orWhereDate('effective_to', '>=', $today);
            })
            ->orderByDesc('effective_from')
            ->first();

        $upcomingSalary = $employee->salaries()
            ->whereDate('effective_from', '>', $today)
            ->orderBy('effective_from')
            ->first();

        return view('employees.salary-history', compact(
            'employee',
            'currentSalary',
            'upcomingSalary'
        ));
    }
}
