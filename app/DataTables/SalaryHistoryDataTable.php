<?php

namespace App\DataTables;

use App\Models\Employee;
use App\Models\Salary;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SalaryHistoryDataTable extends DataTable
{
    protected ?Employee $employee = null;

    /**
     * Scope this DataTable to a specific employee.
     * Call this from the controller before ->render() / ->ajax().
     */
    public function employee(Employee $employee): static
    {
        $this->employee = $employee;

        return $this;
    }

    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Salary> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('effective_from', fn($salary) => $salary->effective_from->format('Y-m-d'))
            ->editColumn('effective_to', fn($salary) => $salary->effective_to
                ? $salary->effective_to->format('Y-m-d')
                : '—')
            ->editColumn('basic_salary', fn($salary) => number_format($salary->basic_salary, 2))
            ->editColumn('allowances', fn($salary) => number_format($salary->allowances, 2))
            ->editColumn('gross_salary', fn($salary) => number_format($salary->gross_salary, 2))
            ->addColumn('previous_salary', function ($salary) {
                $previous = $salary->employee->salaries()
                    ->where('effective_from', '<', $salary->effective_from)
                    ->orderByDesc('effective_from')
                    ->first();

                return $previous
                    ? number_format($previous->basic_salary, 2)
                    : '—';
            })

            ->addColumn('status', function ($salary) {
                $today = now();

                $isCurrent = $salary->effective_from->lte($today)
                    && (is_null($salary->effective_to) || $salary->effective_to->gte($today));

                $isUpcoming = $salary->effective_from->gt($today);

                if ($isCurrent) {
                    return '<span class="badge rounded-pill text-bg-success">Current</span>';
                }

                if ($isUpcoming) {
                    return '<span class="badge rounded-pill text-bg-warning text-dark">Upcoming</span>';
                }

                return '<span class="badge rounded-pill text-bg-secondary">Past</span>';
            })
            ->addColumn('action', fn($salary) => view('employees.partials.salary-actions', compact('salary'))->render())
            ->rawColumns(['status', 'action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Salary>
     */
    public function query(Salary $model): QueryBuilder
    {
        return $model->newQuery()
            ->where('employee_id', $this->employee->id)
            ->orderByDesc('effective_from');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('salaryhistory-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->selectStyleSingle()
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
                ->title('#')
                ->exportable(false)
                ->printable(false)
                ->width(40)
                ->addClass('text-center'),
            Column::make('effective_from'),
            Column::make('effective_to'),
            Column::make('basic_salary'),
            Column::make('allowances'),
            Column::make('gross_salary'),
            Column::computed('previous_salary')
                ->orderable(false)
                ->searchable(false),
            Column::computed('status')
                ->orderable(false)
                ->searchable(false),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(100)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'SalaryHistory_' . date('YmdHis');
    }
}
