@extends('masterlayout.layout')

@section('content')

    <div class="container py-4">

        <div class="row justify-content-start">
            <div class="col-lg-7 col-md-9">

                {{-- Header --}}
                <div class="d-flex align-items-center justify-content-between mb-4">

                    <div>
                        <h2 class="fw-bold mb-1">Edit Salary Record</h2>
                        <p class="text-muted mb-0">
                            Update salary information for {{ $salary->employee->name }}
                        </p>
                    </div>

                    <a href="{{ route('employee.salary-history', $salary->employee) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i>
                        Back
                    </a>

                </div>


                {{-- Validation Errors --}}
                @if ($errors->any())
                    <div class="alert alert-danger shadow-sm">
                        <strong>Please fix the following errors:</strong>

                        <ul class="mb-0 mt-2 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                {{-- Salary Form --}}
                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="fw-semibold mb-0">
                            Salary Details
                        </h5>
                    </div>

                    <div class="card-body p-4">

                        <form action="{{ route('salaries.update', $salary) }}" method="POST">

                            @csrf
                            @method('PUT')


                            {{-- Employee --}}
                            <div class="mb-4">

                                <label class="form-label fw-semibold">
                                    Employee
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="bi bi-person"></i>
                                    </span>

                                    <input type="text" class="form-control bg-light"
                                        value="{{ $salary->employee->name }}" disabled>

                                </div>

                                <div class="form-text">
                                    Employee cannot be changed for this salary record.
                                </div>

                            </div>


                            {{-- Basic Salary & Allowances --}}
                            <div class="row">

                                <div class="col-md-6 mb-4">

                                    <label for="basic_salary" class="form-label fw-semibold">
                                        Basic Salary
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            <i class="bi bi-cash"></i>
                                        </span>

                                        <input type="number" step="0.01" min="0" id="basic_salary"
                                            name="basic_salary"
                                            class="form-control @error('basic_salary') is-invalid @enderror"
                                            value="{{ old('basic_salary', $salary->basic_salary) }}" required>

                                        @error('basic_salary')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>

                                </div>


                                <div class="col-md-6 mb-4">

                                    <label for="allowances" class="form-label fw-semibold">
                                        Allowances
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            <i class="bi bi-plus-circle"></i>
                                        </span>

                                        <input type="number" step="0.01" min="0" id="allowances"
                                            name="allowances" class="form-control @error('allowances') is-invalid @enderror"
                                            value="{{ old('allowances', $salary->allowances) }}">

                                        @error('allowances')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>

                                </div>

                            </div>


                            {{-- Effective Dates --}}
                            <div class="row">

                                <div class="col-md-6 mb-4">

                                    <label for="effective_from" class="form-label fw-semibold">
                                        Effective From
                                    </label>

                                    <input type="date" id="effective_from" name="effective_from"
                                        class="form-control @error('effective_from') is-invalid @enderror"
                                        value="{{ old('effective_from', $salary->effective_from->format('Y-m-d')) }}"
                                        required>

                                    @error('effective_from')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>


                                <div class="col-md-6 mb-4">

                                    <label for="effective_to" class="form-label fw-semibold">
                                        Effective To
                                    </label>

                                    <input type="date" id="effective_to" name="effective_to"
                                        class="form-control @error('effective_to') is-invalid @enderror"
                                        value="{{ old('effective_to', $salary->effective_to?->format('Y-m-d')) }}">

                                    @error('effective_to')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    <div class="form-text">
                                        Leave empty if this is the current salary.
                                    </div>

                                </div>

                            </div>


                            <hr class="my-3">


                            {{-- Buttons --}}
                            <div class="d-flex justify-content-end gap-2">

                                <a href="{{ route('employee.salary-history', $salary->employee) }}"
                                    class="btn btn-light border">
                                    Cancel
                                </a>

                                <button type="submit" class="btn btn-primary px-4">

                                    <i class="bi bi-check-circle me-1"></i>
                                    Update Salary

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>
        </div>

    </div>

@endsection
