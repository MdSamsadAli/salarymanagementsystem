<?php

namespace Modules\Attendance\DataTables;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Modules\Attendance\Models\Attendance;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AttendanceHistoryDataTable extends DataTable
{
    protected int $employeeId;

    public function forEmployee(int $employeeId): static
    {
        $this->employeeId = $employeeId;

        return $this;
    }

    public function dataTable(Builder $query)
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('attendance_date', fn($row) => $row->attendance_date->format('D, d M Y'))
            ->editColumn('check_in', fn($row) => Attendance::formatTime($row->check_in))
            ->editColumn('check_out', fn($row) => Attendance::formatTime($row->check_out))
            ->addColumn('working_hours', fn($row) => Attendance::formatWorkingHours(
                $row->check_in,
                $row->check_out
            ))
            ->addColumn('status_badge', fn($row) => view('attendance::partials.status-badge', [
                'status' => $row->status,
                'checkedInOnly' => false,
            ])->render())
            ->rawColumns(['status_badge']);
    }

    public function query(Attendance $model): Builder
    {
        return $model->newQuery()
            ->forEmployee($this->employeeId)
            ->orderByDesc('attendance_date');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('attendance-history-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1, 'desc')
            ->parameters([
                'processing' => true,
                'serverSide' => true,
            ]);
    }

    protected function getColumns(): array
    {
        return [
            Column::make('attendance_date')->title('Date'),
            Column::make('check_in')->title('Check In'),
            Column::make('check_out')->title('Check Out'),
            Column::make('working_hours')->title('Working Hours')->orderable(false)->searchable(false),
            Column::make('status_badge')->title('Status')->orderable(false)->searchable(false),
        ];
    }

    public function filename(): string
    {
        return 'AttendanceHistory_' . date('YmdHis');
    }

    // private function formatTime(?string $time): string
    // {
    //     return $time ? Carbon::parse($time)->format('h:i A') : '—';
    // }
}
