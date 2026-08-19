@foreach ($categories as $cat)
    @if (!in_array($cat->id, $excludeIds ?? []))
        <option value="{{ $cat->id }}" {{ (string) $selected === (string) $cat->id ? 'selected' : '' }}>
            {{ str_repeat('— ', $depth ?? 0) }}{{ $cat->category }}
        </option>
        @if ($cat->childrenRecursive->count())
            @include('category.partials.options', [
                'categories' => $cat->childrenRecursive,
                'depth' => ($depth ?? 0) + 1,
                'selected' => $selected ?? null,
                'excludeIds' => $excludeIds ?? [],
            ])
        @endif
    @endif
@endforeach
