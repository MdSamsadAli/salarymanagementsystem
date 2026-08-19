@extends('masterlayout.layout')
@section('content')
    <h1 class="my-4">Create News</h1>
    <div class="d-flex justify-content-end">
        <a href="{{ route('news.index') }}" class="btn btn-primary">News list</a>
    </div>
    <form action="{{ isset($news->id) ? route('news.update', $news->id) : route('news.store') }}" method="post"
        enctype="multipart/form-data">
        @csrf

        @isset($news->id)
            @method('put')
        @endisset
        <div class="row">
            <div class="col-lg-4">
                <label for="news">title</label>
                <input class="form-control" type="text" name="title" value="{{ old('title', $news->title ?? '') }}">
            </div>


            <div class="col-lg-4">
                <label for="news">image</label>
                <input class="form-control" type="file" name="image" value="{{ old('image', $news->image ?? '') }}">
            </div>

            <div class="col-lg-4">
                <label for="news">Category</label>
                <select class="form-control select2" name="category_id" id="category">
                    <option value="" selected>--select category--</option>
                    @foreach ($categories as $item)
                        <option value="{{ $item->id }}"
                            {{ old('category_id', @$news->category_id) == $item->id ? 'selected' : '' }}>
                            {{ $item->category ?? '' }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-12">
                <label for="description">description</label>
                <textarea class="form-control" name="description" cols="30" rows="2">{{ old('description', $news->description ?? '') }}</textarea>
            </div>

            <div class="col-lg-12 mt-4">
                <button class="btn btn-primary">Submit</button>
            </div>
        </div>

    </form>
@endsection
