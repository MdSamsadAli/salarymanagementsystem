@if (!$row->attendance_status)
    <form action="{{ route('attendance.admin-check-in', $row->id) }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-sm btn-success">Check In</button>
    </form>
@elseif ($row->check_in && !$row->check_out)
    <form action="{{ route('attendance.admin-check-out', $row->id) }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-sm btn-danger">Check Out</button>
    </form>
@else
    <span class="text-muted small">Done</span>
@endif
