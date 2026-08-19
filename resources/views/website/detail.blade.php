@extends('website.layout.layout')
@section('content')
    <div class="container">
        <h1>{{ $news->title }}</h1>
        <img src="{{ asset('storage/news/' . $news->image) }}" alt="image" class="w-100">
        <div>
            <p>{{ $news->description }}</p>
        </div>
    </div>
@endsection
