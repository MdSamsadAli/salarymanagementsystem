@extends('masterlayout.layout')
@section('content')
    <div class="container py-4">

        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <h2 class="fw-bold mb-1">Salary History</h2>
                {{-- <p class="text-muted mb-0">
                    {{ $employee->name }} &middot; {{ $employee->designation ?? '—' }} &middot;
                    Joined {{ $employee->date_of_joining->format('Y-m-d') }}
                </p> --}}
            </div>
            <a href="{{ route('employee.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Employees
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-3 mb-4">
            {{-- Current Salary Card --}}
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100 border-start border-success border-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge rounded-pill text-bg-success">Current</span>
                            <h5 class="fw-bold mb-0">Current Salary</h5>
                        </div>

                        @if ($currentSalary)
                            <div class="row text-center mt-3">
                                <div class="col-4">
                                    <div class="text-muted small">Basic</div>
                                    <div class="fw-semibold">{{ number_format($currentSalary->basic_salary, 2) }}</div>
                                </div>
                                <div class="col-4">
                                    <div class="text-muted small">Allowances</div>
                                    <div class="fw-semibold">{{ number_format($currentSalary->allowances, 2) }}</div>
                                </div>
                                <div class="col-4">
                                    <div class="text-muted small">Gross</div>
                                    <div class="fw-bold fs-5 text-success">
                                        {{ number_format($currentSalary->gross_salary, 2) }}</div>
                                </div>
                            </div>
                            <p class="text-muted small mt-3 mb-0">
                                <i class="bi bi-calendar-check"></i>
                                Effective since {{ $currentSalary->effective_from->format('Y-m-d') }}
                            </p>
                        @else
                            <p class="text-muted mb-0">No active salary record as of today.</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Upcoming Increment Card --}}
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100 border-start border-warning border-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge rounded-pill text-bg-warning text-dark">Upcoming</span>
                            <h5 class="fw-bold mb-0">Upcoming Increment</h5>
                        </div>

                        @if ($upcomingSalary)
                            <div class="text-center mt-3">
                                <div class="text-muted small">Gross Salary</div>
                                <div class="fw-bold fs-4 text-warning-emphasis">
                                    {{ number_format($upcomingSalary->gross_salary, 2) }}
                                </div>
                            </div>
                            <p class="text-muted small mt-3 mb-0">
                                <i class="bi bi-calendar-event"></i>
                                Starting {{ $upcomingSalary->effective_from->format('Y-m-d') }}
                            </p>
                        @else
                            <p class="text-muted mb-0">No increment scheduled.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Increment Form --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 pt-3">
                <h5 class="fw-bold mb-0"><i class="bi bi-graph-up-arrow"></i> Add Increment for {{ $employee->name }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('salaries.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">

                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">From Salary</label>
                            <input type="number" step="0.01" name="from_salary" class="form-control bg-light"
                                value="{{ old('from_salary', $currentSalary->basic_salary ?? '') }}" readonly>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">To Salary</label>
                            <input type="number" step="0.01" name="basic_salary"
                                class="form-control @error('basic_salary') is-invalid @enderror"
                                value="{{ old('basic_salary') }}" required>
                            @error('basic_salary')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Allowances</label>
                            <input type="number" step="0.01" name="allowances"
                                class="form-control @error('allowances') is-invalid @enderror"
                                value="{{ old('allowances', $currentSalary->allowances ?? 0) }}">
                            @error('allowances')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Effective From</label>
                            <input type="date" name="effective_from"
                                class="form-control @error('effective_from') is-invalid @enderror"
                                value="{{ old('effective_from', now()->format('Y-m-d')) }}" required>
                            @error('effective_from')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-3 text-end">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-circle"></i> Save Increment
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- History Table --}}
        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white border-0 pt-3 d-flex justify-content-between align-items-center">

                <h5 class="fw-bold mb-0">
                    <i class="bi bi-clock-history"></i>
                    Full History
                </h5>

                <a href="{{ route('salaries.create', ['employee_id' => $employee->id]) }}"
                    class="btn btn-sm btn-outline-primary">

                    <i class="bi bi-plus-lg"></i>
                    Add Salary Record

                </a>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table id="salary-history-table" class="table table-hover align-middle">

                        <thead class="table-light">

                            <tr>
                                <th>#</th>
                                <th>Previous Basic</th>
                                <th>Basic Salary</th>
                                <th>Allowances</th>
                                <th>Gross</th>
                                <th>Effective From</th>
                                <th>Effective To</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>

                        </thead>

                    </table>

                </div>

            </div>

        </div>

    </div>
@endsection

@push('scripts')
    <script>
        $('#salary-history-table').DataTable({

            processing: true,
            serverSide: true,

            ajax: "{{ route('employee.salary-history', ['id' => $employee->id]) }}",

            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'previous_salary',
                    name: 'previous_salary',
                    orderable: false
                },
                {
                    data: 'basic_salary',
                    name: 'basic_salary'
                },
                {
                    data: 'allowances',
                    name: 'allowances'
                },
                {
                    data: 'gross_salary',
                    name: 'gross_salary'
                },
                {
                    data: 'effective_from',
                    name: 'effective_from'
                },
                {
                    data: 'effective_to',
                    name: 'effective_to'
                },
                {
                    data: 'status',
                    name: 'status',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ],

            order: [
                [5, 'desc']
            ]
        });
    </script>
@endpush
