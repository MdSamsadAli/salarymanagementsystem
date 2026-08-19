@php
    $childCount = $category->childrenRecursive->count();
@endphp
<div class="cat-row" data-depth="{{ $depth }}">
    <div class="cat-name-cell">
        <span class="cat-indent" style="width: {{ $depth * 22 }}px"></span>

        @if ($childCount > 0)
            <button type="button" class="cat-toggle" aria-label="Toggle subcategories">▾</button>
        @else
            <span class="cat-toggle-spacer"></span>
        @endif

        <span class="cat-label">{{ $category->category }}</span>
    </div>

    <span class="cat-count">{{ $childCount > 0 ? $childCount . ($childCount === 1 ? ' sub' : ' subs') : '—' }}</span>

    <div class="cat-actions">
        <a href="{{ route('categories.edit', $category->id) }}" class="cat-icon-btn" title="Edit">✎</a>
        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline delete-form">
            @csrf
            @method('DELETE')
            <button type="submit" class="cat-icon-btn danger" title="Delete">🗑</button>
        </form>
    </div>
</div>

@if ($childCount > 0)
    <div class="cat-children">
        @foreach ($category->childrenRecursive as $child)
            @include('category.partials.tree-node', ['category' => $child, 'depth' => $depth + 1])
        @endforeach
    </div>
@endif
