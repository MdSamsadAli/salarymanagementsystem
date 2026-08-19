@extends('masterlayout.layout')

@section('content')
    <div class="container py-4">

        <div class="mb-4">
            <h2 class="fw-bold mb-1">Dashboard</h2>
            <p class="text-muted mb-0">Overview of your system</p>
        </div>

        <div class="row g-4">

            {{-- Employee Count --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>
                                <p class="text-muted mb-1">Employees</p>
                                <h2 class="fw-bold mb-0">
                                    {{ @$employeeCount ?? '6' }}
                                </h2>
                            </div>

                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle
                                    d-flex align-items-center justify-content-center"
                                style="width: 55px; height: 55px;">
                                <i class="bi bi-people fs-3"></i>
                            </div>

                        </div>

                    </div>
                </div>
            </div>


            {{-- News Count --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>
                                <p class="text-muted mb-1">News</p>
                                <h2 class="fw-bold mb-0">
                                    {{ @$newsCount ?? '121' }}
                                </h2>
                            </div>

                            <div class="bg-success bg-opacity-10 text-success rounded-circle
                                    d-flex align-items-center justify-content-center"
                                style="width: 55px; height: 55px;">
                                <i class="bi bi-newspaper fs-3"></i>
                            </div>

                        </div>

                    </div>
                </div>
            </div>


            {{-- Category Count --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>
                                <p class="text-muted mb-1">Categories</p>
                                <h2 class="fw-bold mb-0">
                                    {{ @$categoryCount ?? '866789' }}
                                </h2>
                            </div>

                            <div class="bg-warning bg-opacity-10 text-warning rounded-circle
                                    d-flex align-items-center justify-content-center"
                                style="width: 55px; height: 55px;">
                                <i class="bi bi-grid fs-3"></i>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
