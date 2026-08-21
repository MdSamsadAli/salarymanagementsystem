<?php

namespace App\DataTables;

use App\Models\Employee;
use App\Models\Salary;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\Attendance\Models\Attendance;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class EmployeeDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('date_of_joining', fn($employee) => $employee->date_of_joining?->format('Y-m-d'))
            ->editColumn('current_gross_salary', fn($employee) => $employee->current_gross_salary
                ? number_format($employee->current_gross_salary, 2)
                : '—')
            ->addColumn('today_attendance', fn($employee) => view('attendance::partials.status-badge', [
                'status' => $employee->attendance_status,
                'checkedInOnly' => $employee->attendance_check_in && !$employee->attendance_check_out,
            ])->render())
            ->addColumn('working_hours', fn($employee) => Attendance::formatWorkingHours(
                $employee->attendance_check_in,
                $employee->attendance_check_out
            ))
            ->addColumn('action', fn($employee) => view('employees.partials.action', compact('employee'))->render())
            ->rawColumns(['today_attendance', 'action'])
            ->setRowId('id');
    }

    /**
     * @return QueryBuilder<Employee>
     */
    public function query(Employee $model): QueryBuilder
    {
        $today = now()->toDateString();

        return $model->newQuery()
            ->select([
                'employees.id',
                'employees.name',
                'employees.address',
                'employees.designation',
                'employees.date_of_joining',
                'current_salaries.gross_salary as current_gross_salary',
                'attendances.status as attendance_status',
                'attendances.check_in as attendance_check_in',
                'attendances.check_out as attendance_check_out',
            ])
            ->leftJoinSub(
                $this->currentSalarySubquery($today),
                'current_salaries',
                fn($join) => $join->on('current_salaries.employee_id', '=', 'employees.id')
            )
            ->leftJoin('attendances', function ($join) use ($today) {
                $join->on('attendances.employee_id', '=', 'employees.id')
                    ->whereDate('attendances.attendance_date', $today);
            })
            ->latest('employees.created_at');
    }

    /**
     * The single salary row per employee that is in effect on the given date.
     */
    private function currentSalarySubquery(string $date): QueryBuilder
    {
        return Salary::query()
            ->select('employee_id', 'gross_salary')
            ->whereDate('effective_from', '<=', $date)
            ->where(function ($q) use ($date) {
                $q->whereNull('effective_to')
                    ->orWhereDate('effective_to', '>=', $date);
            })
            ->whereIn('id', function ($sub) use ($date) {
                $sub->selectRaw('MAX(id)')
                    ->from('salaries as s2')
                    ->whereColumn('s2.employee_id', 'salaries.employee_id')
                    ->whereDate('s2.effective_from', '<=', $date)
                    ->where(function ($q) use ($date) {
                        $q->whereNull('s2.effective_to')
                            ->orWhereDate('s2.effective_to', '>=', $date);
                    });
            });
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('employee-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(4)
            ->selectStyleSingle()
            ->parameters([
                'pageLength' => 5,
                'lengthMenu' => [[5, 10, 25, 50, -1], [5, 10, 25, 50, 'All']],
            ])
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload'),
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')
                ->title('SNo.')
                ->exportable(false)
                ->printable(false)
                ->width(40)
                ->addClass('text-center'),
            Column::make('name'),
            Column::make('address'),
            Column::make('designation'),
            Column::make('date_of_joining'),
            Column::computed('current_gross_salary')
                ->title('Current Gross Salary')
                ->orderable(false)
                ->searchable(false),
            Column::computed('today_attendance')
                ->title('Today')
                ->orderable(false)
                ->searchable(false)
                ->addClass('text-center'),
            Column::computed('working_hours')
                ->title('Working Hours')
                ->orderable(false)
                ->searchable(false)
                ->addClass('text-center'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Employee_' . date('YmdHis');
    }
}
