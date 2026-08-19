@extends('masterlayout.layout')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-start">
            <div class="col-lg-7">

                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header text-black rounded-top-4 py-3">
                        <h4 class="mb-0"><i class="bi bi-person-plus-fill me-2"></i>Add Employee</h4>
                    </div>

                    <div class="card-body p-4">

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('employee.store') }}" method="POST" novalidate>
                            @csrf

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Name</label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                    class="form-control @error('name') is-invalid @enderror" placeholder="Enter full name"
                                    required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Address</label>
                                <input type="text" name="address" value="{{ old('address') }}"
                                    class="form-control @error('address') is-invalid @enderror" placeholder="Enter address"
                                    required>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Designation</label>
                                <input type="text" name="designation" value="{{ old('designation') }}"
                                    class="form-control @error('designation') is-invalid @enderror"
                                    placeholder="Enter designation" required>
                                @error('designation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Date of Joining</label>
                                <input type="date" name="date_of_joining" value="{{ old('date_of_joining') }}"
                                    class="form-control @error('date_of_joining') is-invalid @enderror" required>
                                @error('date_of_joining')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('employee.index') }}" class="btn btn-outline-secondary rounded-3 px-4">
                                    <i class="bi bi-arrow-left me-1"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary rounded-3 px-4">
                                    <i class="bi bi-check-circle me-1"></i> Save Employee
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
