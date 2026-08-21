<a href="{{ route('employee.show', $employee) }}" class="btn btn-secondary btn-sm" data-bs-toggle="tooltip" title="View">
    <i class="bi bi-eye"></i> Show
</a>

<a href="{{ route('employee.edit', $employee) }}" class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="Edit">
    <i class="bi bi-pencil"></i> Edit
</a>

<a href="{{ route('employee.salary-history', $employee) }}" class="btn btn-primary btn-sm" data-bs-toggle="tooltip"
    title="Salary History">
    <i class="bi bi-cash-stack"></i> Sal History
</a>

<a href="{{ route('attendance.history', $employee) }}" class="btn btn-outline-dark btn-sm" data-bs-toggle="tooltip"
    title="Attendance History">
    <i class="bi bi-clock-history"></i> Atten History
</a>

<form action="{{ route('employee.destroy', $employee) }}" method="POST" class="delete-form d-inline">
    @csrf
    @method('DELETE')

    <button type="submit" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Delete">
        <i class="bi bi-trash"></i> Delete
    </button>
</form>
