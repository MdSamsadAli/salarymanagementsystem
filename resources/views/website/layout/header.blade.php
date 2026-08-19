<div class="container">
    <nav class="navbar navbar-expand-lg navbar-dark header">
        <button class="navbar-toggler my-2" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav justify-content-between gap-0">
                <li class="nav-item">
                    <a class="nav-link text-white {{ Request()->routeIs('front.index') ? 'active' : '' }}"
                        href="{{ route('front.index') }}">Home</a>
                </li>
                @foreach ($menu_categories as $item)
                    @include('website.layout.partials.nav-category', ['category' => $item])
                @endforeach
            </ul>
        </div>
    </nav>
</div>
