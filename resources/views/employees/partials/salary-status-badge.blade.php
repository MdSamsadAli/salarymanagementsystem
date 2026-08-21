@php
    $today = now();
    $isCurrent =
        $salary->effective_from->lte($today) && (is_null($salary->effective_to) || $salary->effective_to->gte($today));
    $isUpcoming = $salary->effective_from->gt($today);
@endphp

@if ($isCurrent)
    <span class="badge rounded-pill text-bg-success">Current</span>
@elseif ($isUpcoming)
    <span class="badge rounded-pill text-bg-warning text-dark">Upcoming</span>
@else
    <span class="badge rounded-pill text-bg-secondary">Past</span>
@endif
