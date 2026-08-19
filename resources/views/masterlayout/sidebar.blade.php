<div>
    <div class="stick-side position-relative">
        <div class="sticky">
            <h3 class="side-title">
                <a href="{{ route('dashboard') }}">NewsPortal</a>
            </h3>

            <nav class="sidenav">
                <ul class="sidebar">
                    <li class="{{ Request()->routeIs('categories.index') ? 'active' : '' }}"><a
                            href="{{ route('categories.index') }}">Category</a></li>
                    {{-- <li><a href="{{ route('categories.index') }}">All Category</a></li>
                    <li class="{{ Request()->routeIs('categories.create') ? 'active' : '' }}"><a
                            href="{{ route('categories.create') }}">Create Category</a></li> --}}

                    <li class="{{ Request()->routeIs('news.index') ? 'active' : '' }}"><a
                            href="{{ route('news.index') }}">News</a></li>
                    {{-- <li><a href="{{ route('news.index') }}">All News</a></li>
                    <li class="{{ Request()->routeIs('news.create') ? 'active' : '' }}"><a
                            href="{{ route('news.create') }}">Create News</a></li> --}}

                    <li class="{{ Request()->routeIs('employee.index') ? 'active' : '' }}"><a
                            href="{{ route('employee.index') }}">Employee</a>
                    </li>
                    <li class="{{ Request()->routeIs('salaries.create') ? 'active' : '' }}"><a
                            href="{{ route('salaries.create') }}">Salary</a>
                    </li>

                    {{-- <li>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            {{ __('Profile') }}
                        </a>
                    </li> --}}


                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </a>
                        </form>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

</div>
