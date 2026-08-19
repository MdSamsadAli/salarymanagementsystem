@extends('masterlayout.layout')
@section('content')
    <div class="d-flex justify-content-end gap-3">
        <a href="{{ route('categories.create') }}" class="btn btn-info text-white px-4">Create Category</a>
        <a href="{{ route('news.create') }}" class="btn btn-primary">Create News</a>
    </div>
    <h1>list News</h1>
    <div>
        <table class="table">
            <tr>
                <th>SNo.</th>
                <th>News title</th>
                <th>description</th>
                <th>Image</th>
                <th>Cateory</th>
                <th>Action</th>
            </tr>
            @foreach ($news as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->description }}</td>
                    <td>
                        <img src="{{ asset('storage/news/' . $item->image) }}" alt="{{ $item->image }}"
                            style="width: 100px; height: 100px">
                    </td>
                    <td>{{ $item->category->category }}</td>
                    <td>
                        <a href="{{ route('news.edit', $item->id) }}" class="btn btn-secondary btn-sm d-inline">Edit</a>

                        <form action="{{ route('news.destroy', $item->id) }}" method="POST" class="d-inline delete-form">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
