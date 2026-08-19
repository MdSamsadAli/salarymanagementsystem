@php
    $hasChildren = $category->childrenRecursive->count() > 0;
@endphp

@if (!$hasChildren)
    <li class="nav-item">
        <a class="nav-link text-white" href="{{ route('front.page', $category->id) }}">
            {{ $category->category }}
        </a>
    </li>
@else
    <li class="nav-item dropdown">
        <a class="nav-link text-white dropdown-toggle" href="{{ route('front.page', $category->id) }}">
            {{ $category->category }}
        </a>
        <ul class="dropdown-menu">
            @foreach ($category->childrenRecursive as $child)
                @include('website.layout.partials.nav-subcategory', ['category' => $child])
            @endforeach
        </ul>
    </li>
@endif
