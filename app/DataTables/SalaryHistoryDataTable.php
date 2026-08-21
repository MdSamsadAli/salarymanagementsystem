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

    public function employee(Employee $employee): static
    {
        $this->employee = $employee;

        return $this;
    }

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
            ->editColumn('previous_salary', fn($salary) => $salary->previous_basic_salary
                ? number_format($salary->previous_basic_salary, 2)
                : '—')
            ->addColumn('status', fn($salary) => view('employees.partials.salary-status-badge', [
                'salary' => $salary,
            ])->render())
            ->addColumn('action', fn($salary) => view('employees.partials.salary-actions', compact('salary'))->render())
            ->rawColumns(['status', 'action'])
            ->setRowId('id');
    }

    /**
     * @return QueryBuilder<Salary>
     */
    public function query(Salary $model): QueryBuilder
    {
        abort_unless($this->employee, 500, 'SalaryHistoryDataTable::employee() must be called before querying.');

        return $model->newQuery()
            ->select('salaries.*')
            ->addSelect([
                'previous_basic_salary' => Salary::query()
                    ->select('basic_salary')
                    ->whereColumn('employee_id', 'salaries.employee_id')
                    ->whereColumn('effective_from', '<', 'salaries.effective_from')
                    ->orderByDesc('effective_from')
                    ->limit(1),
            ])
            ->where('employee_id', $this->employee->id)
            ->orderByDesc('effective_from');
    }

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

    protected function filename(): string
    {
        return 'SalaryHistory_' . date('YmdHis');
    }
}
