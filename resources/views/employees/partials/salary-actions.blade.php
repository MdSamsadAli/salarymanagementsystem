<a href="{{ route('salaries.edit', $salary) }}" class="btn btn-sm btn-outline-primary">
    Edit
</a>

<form action="{{ route('salaries.destroy', $salary) }}" method="POST" class="d-inline"
    onsubmit="return confirm('Delete this record?')">

    @csrf
    @method('DELETE')

    <button type="submit" class="btn btn-sm btn-outline-danger">
        Delete
    </button>

</form>
