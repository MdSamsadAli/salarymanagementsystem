<?php

namespace Modules\Attendance\DataTables;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Modules\Attendance\Models\Attendance;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AttendanceDataTable extends DataTable
{
    public function dataTable(Builder $query)
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('designation', fn($row) => $row->designation ?? '—')
            ->editColumn('check_in', fn($row) => $this->formatTime($row->check_in))
            ->editColumn('check_out', fn($row) => $this->formatTime($row->check_out))
            ->addColumn('working_hours', fn($row) => Attendance::formatWorkingHours(
                $row->check_in,
                $row->check_out
            ))
            ->addColumn('status_badge', fn($row) => view('attendance::partials.status-badge', [
                'status' => $row->attendance_status,
                'checkedInOnly' => $row->check_in && !$row->check_out,
            ])->render())
            ->addColumn('action', fn($row) => view('attendance::partials.action-buttons', [
                'row' => $row,
            ])->render())
            ->rawColumns(['status_badge', 'action']);
    }

    public function query(Employee $model): Builder
    {
        $today = today()->toDateString();

        return $model->newQuery()
            ->select(['employees.id', 'employees.name', 'employees.designation'])
            ->leftJoin('attendances', function ($join) use ($today) {
                $join->on('attendances.employee_id', '=', 'employees.id')
                    ->whereDate('attendances.attendance_date', $today);
            })
            ->addSelect([
                'attendances.id as attendance_id',
                'attendances.status as attendance_status',
                'attendances.check_in as check_in',
                'attendances.check_out as check_out',
            ]);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('attendance-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->parameters([
                'processing' => true,
                'serverSide' => true,
            ]);
    }

    protected function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')
                ->title('SNo.')
                ->exportable(false)
                ->printable(false)
                ->width(40)
                ->addClass('text-center'),
            Column::make('name')->title('Name'),
            Column::make('designation')->title('Designation'),
            Column::make('check_in')->title('Check In'),
            Column::make('check_out')->title('Check Out'),
            Column::make('working_hours')->title('Working Hours')->orderable(false)->searchable(false),
            Column::make('status_badge')->title('Status')->orderable(false)->searchable(false),
            Column::computed('action')->title('Action')->addClass('text-center')->orderable(false)->searchable(false),
        ];
    }

    public function filename(): string
    {
        return 'Attendance_' . date('YmdHis');
    }

    private function formatTime(?string $time): string
    {
        return $time ? Carbon::parse($time)->format('h:i A') : '—';
    }
}
