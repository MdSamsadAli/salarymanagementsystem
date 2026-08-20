<?php

namespace App\DataTables;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class EmployeeDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Employee> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('date_of_joining', fn($employee) => $employee->date_of_joining?->format('Y-m-d'))
            ->editColumn('current_gross_salary', fn($employee) => $employee->current_gross_salary
                ? number_format($employee->current_gross_salary, 2)
                : '—')
            ->addColumn('action', fn($employee) => view('employees.partials.action', compact('employee'))->render())
            ->rawColumns(['action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
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
                'salaries.gross_salary as current_gross_salary',
            ])
            ->leftJoin('salaries', function ($join) use ($today) {
                $join->on('salaries.employee_id', '=', 'employees.id')
                    ->whereRaw('DATE(salaries.effective_from) <= ?', [$today])
                    ->where(function ($q) use ($today) {
                        $q->whereNull('salaries.effective_to')
                            ->orWhereRaw('DATE(salaries.effective_to) >= ?', [$today]);
                    })
                    ->whereRaw('salaries.effective_from = (
                        SELECT MAX(s2.effective_from) FROM salaries s2
                        WHERE s2.employee_id = employees.id
                        AND DATE(s2.effective_from) <= ?
                        AND (s2.effective_to IS NULL OR DATE(s2.effective_to) >= ?)
                    )', [$today, $today]);
            })
            ->latest('employees.created_at');
    }

    /**
     * Optional method if you want to use the html builder.
     */
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

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')
                ->title('SNo.')
                ->exportable(false)
                ->printable(false)
                ->width(40)
                ->addClass('text-center'),
            // Column::make('id'),
            Column::make('name'),
            Column::make('address'),
            Column::make('designation'),
            Column::make('date_of_joining'),
            Column::computed('current_gross_salary')
                ->title('Current Gross Salary')
                ->orderable(false)
                ->searchable(false),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Employee_' . date('YmdHis');
    }
}
