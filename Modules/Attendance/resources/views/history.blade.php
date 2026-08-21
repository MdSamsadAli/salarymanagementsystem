@extends('masterlayout.layout')
@section('content')
    <div class="container py-4">

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-3">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-clock-history"></i>
                    Attendance History — {{ $employee->name }}
                </h5>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    {{ $dataTable->table(['class' => 'table table-hover align-middle']) }}
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
