@extends('website.layout.layout')
@section('content')
    <div class="container">
        <div class="griding">
            @if (!$newsdata->isEmpty())
                @foreach ($newsdata as $item)
                    <div class="card">
                        <a href="{{ route('news.detail', $item->id) }}" style="text-decoration: none">
                            <div class="item">
                                <div class="img-container">
                                    <img src="{{ asset('storage/news/' . $item->image) }}" alt="{{ $item->image }}">
                                </div>
                                <div class="p-3">
                                    <h4 class="text-black m-0">{{ $item->title }}</h4>
                                    <p class="text-black m-0">{{ $item->description }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            @else
                <h4>No data Found</h4>
            @endif
        </div>
    </div>
@endsection
