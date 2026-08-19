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

            <table id="employees-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Date of Joining</th>
                        <th>Current Gross Salary</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>

        </div>

    </div>
@endsection


@push('styles')
@endpush

@push('scripts')
    

    <script>
        $(document).ready(function() {

            $('#employees-table').DataTable({
                processing: true,
                serverSide: true,

                ajax: "{{ route('employee.index') }}",

                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'designation',
                        name: 'designation'
                    },
                    {
                        data: 'date_of_joining',
                        name: 'date_of_joining'
                    },
                    {
                        data: 'current_gross_salary',
                        name: 'currentSalary.gross_salary',
                        orderable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

        });
    </script>
@endpush
