@php
    $hasChildren = $category->childrenRecursive->count() > 0;
@endphp

<li class="{{ $hasChildren ? 'dropdown-submenu' : '' }}">
    <a class="dropdown-item {{ $hasChildren ? 'dropdown-toggle' : '' }}" href="{{ route('front.page', $category->id) }}">
        {{ $category->category }}
    </a>
    @if ($hasChildren)
        <ul class="dropdown-menu">
            @foreach ($category->childrenRecursive as $child)
                @include('website.layout.partials.nav-subcategory', ['category' => $child])
            @endforeach
        </ul>
    @endif
</li>
