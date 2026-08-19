@extends('masterlayout.layout')
@section('content')
    <div class="container py-4">
        <div class="row justify-content-start">
            <div class="col-lg-7 col-md-9">

                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h2 class="fw-bold mb-0">Employee Profile</h2>
                    <a href="{{ route('employee.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">

                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="d-flex align-items-center justify-content-center rounded-circle bg-primary-subtle text-primary fw-bold"
                                style="width:56px;height:56px;font-size:1.25rem;">
                                {{ strtoupper(substr($employee->name, 0, 1)) }}
                            </div>
                            <div>
                                <h4 class="fw-bold mb-0">{{ $employee->name }}</h4>
                                <span class="text-muted">{{ $employee->designation ?? '—' }}</span>
                            </div>
                        </div>

                        <dl class="row mb-0">
                            <dt class="col-sm-4 text-muted fw-normal">Address</dt>
                            <dd class="col-sm-8">{{ $employee->address ?? '—' }}</dd>

                            <dt class="col-sm-4 text-muted fw-normal">Date of Joining</dt>
                            <dd class="col-sm-8">{{ $employee->date_of_joining->format('Y-m-d') }}</dd>

                            <dt class="col-sm-4 text-muted fw-normal">Current Salary</dt>
                            <dd class="col-sm-8">
                                @if ($employee->currentSalary)
                                    <span class="badge text-bg-success fs-6 fw-semibold">
                                        {{ number_format($employee->currentSalary->gross_salary, 2) }}
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </dd>
                        </dl>

                        <hr class="my-4">

                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('employee.salary-history', $employee) }}" class="btn btn-primary">
                                <i class="bi bi-clock-history"></i> View Salary History
                            </a>
                            <a href="{{ route('employee.edit', $employee) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-pencil"></i> Edit Employee
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
