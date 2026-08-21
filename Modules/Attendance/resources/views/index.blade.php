@extends('masterlayout.layout')
@section('content')
    <div class="container py-4">

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-clock"></i>
                    Attendance — {{ now()->format('l, d M Y') }}
                </h5>
            </div>

            <div class="card-body">

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-warning">{{ session('error') }}</div>
                @endif

                <div class="table-responsive">
                    {{ $dataTable->table(['class' => 'table table-hover align-middle table-bordered table-striped']) }}
                </div>

            </div>
        </div>

    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
