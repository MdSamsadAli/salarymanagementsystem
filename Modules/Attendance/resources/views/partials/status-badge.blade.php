@if ($checkedInOnly)
    <span class="badge rounded-pill text-bg-warning text-dark">Checked In</span>
@elseif (!$status)
    <span class="badge rounded-pill text-bg-danger">Absent</span>
@else
    @php
        $classes = [
            'present' => 'success',
            'late' => 'warning text-dark',
            'half_day' => 'info text-dark',
            'leave' => 'secondary',
        ];
    @endphp
    <span class="badge rounded-pill text-bg-{{ $classes[$status] ?? 'danger' }}">
        {{ ucfirst(str_replace('_', ' ', $status)) }}
    </span>
@endif
