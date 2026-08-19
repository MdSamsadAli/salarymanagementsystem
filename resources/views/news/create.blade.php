@extends('masterlayout.layout')

@section('content')
    <h1 class="my-4">{{ isset($news->id) ? 'Edit News' : 'Create News' }}</h1>

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('news.index') }}" class="btn btn-primary">News list</a>
    </div>

    {{-- Show all errors at top (optional but useful) --}}
    {{-- @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif --}}

    <form action="{{ isset($news->id) ? route('news.update', $news->id) : route('news.store') }}" method="post"
        enctype="multipart/form-data">
        @csrf

        @isset($news->id)
            @method('PUT')
        @endisset

        <div class="row">
            {{-- Title --}}
            <div class="col-lg-4">
                <label for="title">Title</label>
                <input class="form-control @error('title') is-invalid @enderror" type="text" name="title"
                    id="title" value="{{ old('title', $news->title ?? '') }}">

                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Image --}}
            <div class="col-lg-4">
                <label for="image">Image</label>
                <input class="form-control @error('image') is-invalid @enderror" type="file" name="image"
                    id="image" accept="image/*">

                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                {{-- Show current image when editing --}}
                @isset($news->image)
                    <div class="mt-2">
                        <img src="{{ asset('storage/news/' . $news->image) }}" alt="Current Image" width="120"
                            class="img-thumbnail">
                    </div>
                @endisset
            </div>

            {{-- Category --}}
            <div class="col-lg-4">
                <label for="category_id">Category</label>
                <select class="form-control select2 @error('category_id') is-invalid @enderror" name="category_id"
                    id="category_id">
                    <option value="">-- Select Category --</option>
                    @foreach ($categories as $item)
                        <option value="{{ $item->id }}"
                            {{ old('category_id', $news->category_id ?? '') == $item->id ? 'selected' : '' }}>
                            {{ $item->category }}
                        </option>
                    @endforeach
                </select>

                @error('category_id')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            {{-- Description --}}
            <div class="col-lg-12 mt-3">
                <label for="description">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description"
                    cols="30" rows="4">{{ old('description', $news->description ?? '') }}</textarea>

                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-lg-12 mt-4">
                <button type="submit" class="btn btn-primary">
                    {{ isset($news->id) ? 'Update' : 'Submit' }}
                </button>
            </div>
        </div>
    </form>
@endsection
