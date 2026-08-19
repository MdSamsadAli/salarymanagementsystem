@extends('masterlayout.layout')
@section('content')
    <h1>Create Category</h1>
    <div class="d-flex align-items-center justify-content-start gap-2 mb-4 mt-3">
        <a class="btn btn-secondary" href="{{ route('news.create') }}">create news</a>
        <a href="{{ route('categories.index') }}" class="btn btn-primary">list category</a>
    </div>
    <form action="{{ isset($category->id) ? route('categories.update', $category->id) : route('categories.store') }}"
        method="post">
        @csrf

        @isset($category->id)
            @method('put')
        @endisset
        <div class="row">
            <div class="col-lg-6">
                <label for="category">Category</label>
                <input class="form-control" type="text" name="category"
                    value="{{ old('category', $category->category ?? '') }}">
            </div>

            <div class="col-lg-6">
                @if (!isset($category))
                    <label for="parent_id">Parent</label>
                @else
                    <label for="parent_id">subcategory</label>
                @endif
                <select name="parent_id" id="parent_id" class="form-control select2">
                    <option value="">-- Select Parent Category --</option>
                    @include('category.partials.options', [
                        'categories' => $maincategory,
                        'depth' => 0,
                        'selected' => old('parent_id', $category->parent_id ?? ''),
                        'excludeIds' => isset($category)
                            ? array_merge([$category->id], $category->descendantIds())
                            : [],
                    ])
                </select>
            </div>
        </div>

        <div class="mt-4">
            <button class="btn btn-primary">Submit</button>
        </div>

    </form>
@endsection
