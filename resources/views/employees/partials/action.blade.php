<a href="{{ route('employee.show', $employee) }}" class="btn btn-secondary btn-sm">
    View
</a>

<a href="{{ route('employee.edit', $employee) }}" class="btn btn-info btn-sm">
    Edit
</a>

<a href="{{ route('employee.salary-history', $employee) }}" class="btn btn-primary btn-sm">
    Salary History
</a>

<form action="{{ route('employee.destroy', $employee) }}" method="POST" class="delete-form" style="display:inline ">
    @csrf
    @method('DELETE')

    <button type="submit" class="btn btn-danger btn-sm">
        Delete
    </button>

</form>
