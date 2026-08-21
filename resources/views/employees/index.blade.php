@extends('masterlayout.layout')

@section('content')
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Employees</h2>

            <a href="{{ route('employee.create') }}" class="btn btn-success btn-sm">
                + Add Employee
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            {{ $dataTable->table(['class' => 'table table-hover table-bordered table-striped w-100']) }}
        </div>

    </div>
@endsection

@push('styles')
@endpush

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
