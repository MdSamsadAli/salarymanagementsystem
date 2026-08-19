@extends('masterlayout.layout')
@section('content')
    <div class="container">
        <div class="row justify-content-start">
            <div class="col-lg-7 col-md-9">

                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h2 class="fw-bold mb-1">Add Salary Record</h2>
                        <p class="text-muted mb-0">Create a new salary entry for an employee</p>
                    </div>
                    <a href="{{ route('employee.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form action="{{ route('salaries.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="employee_id" class="form-label fw-semibold">Employee</label>
                                <select id="employee_id" name="employee_id"
                                    class="form-select @error('employee_id') is-invalid @enderror" required>
                                    <option value="">-- Select --</option>
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}"
                                            {{ old('employee_id', $selectedEmployeeId) == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('employee_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="basic_salary" class="form-label fw-semibold">Basic Salary</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" id="basic_salary" name="basic_salary"
                                            class="form-control @error('basic_salary') is-invalid @enderror"
                                            value="{{ old('basic_salary') }}" required>
                                        @error('basic_salary')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="allowances" class="form-label fw-semibold">Allowances</label>
                                    <input type="number" step="0.01" id="allowances" name="allowances"
                                        class="form-control @error('allowances') is-invalid @enderror"
                                        value="{{ old('allowances', 0) }}">
                                    @error('allowances')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="effective_from" class="form-label fw-semibold">Effective From</label>
                                <input type="date" id="effective_from" name="effective_from"
                                    class="form-control @error('effective_from') is-invalid @enderror"
                                    value="{{ old('effective_from') }}" required>
                                @error('effective_from')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr class="my-4">

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('employee.index') }}" class="btn btn-light border">Cancel</a>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-check-circle"></i> Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
