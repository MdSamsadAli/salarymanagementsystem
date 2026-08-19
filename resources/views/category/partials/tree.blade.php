<ul class="list-unstyled ps-4">
    @foreach ($categories as $cat)
        <li class="py-1">
            {{ $cat->category }}
            <a href="{{ route('categories.edit', $cat->id) }}" class="btn btn-sm btn-warning ms-2">Edit</a>
            <form action="{{ route('categories.destroy', $cat->id) }}" method="post" class="d-inline">
                @csrf @method('delete')
                <button class="btn btn-sm btn-danger"
                    onclick="return confirm('Delete this category and all its subcategories?')">Delete</button>
            </form>
            @if ($cat->childrenRecursive->count())
                @include('category.partials.tree', ['categories' => $cat->childrenRecursive])
            @endif
        </li>
    @endforeach
</ul>
