@extends('masterlayout.layout')
@section('content')
    <div class="category-page">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="page-title mb-0">Categories</h1>
                <p class="page-subtitle mb-0">Organize categories and nest subcategories to any depth.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('news.create') }}" class="btn btn-outline-secondary">+ News</a>
                <a href="{{ route('categories.create') }}" class="btn btn-primary">+ Category</a>
            </div>
        </div>

        <div class="category-tree-card">
            <div class="category-tree-head">
                <span>Name</span>
                <span>Items</span>
                <span class="text-end">Actions</span>
            </div>

            @forelse ($categories as $item)
                @include('category.partials.tree-node', ['category' => $item, 'depth' => 0])
            @empty
                <div class="category-empty">
                    No categories yet. <a href="{{ route('categories.create') }}">Create the first one</a>.
                </div>
            @endforelse
        </div>
    </div>

    <style>
        .category-page {
            max-width: 900px;
        }

        .page-title {
            font-weight: 700;
            font-size: 1.6rem;
            color: #1e2430;
        }

        .page-subtitle {
            color: #8a92a3;
            font-size: .875rem;
        }

        .category-tree-card {
            border: 1px solid #e7e9ee;
            border-radius: 10px;
            overflow: hidden;
            background: #fff;
        }

        .category-tree-head {
            display: grid;
            grid-template-columns: 1fr 90px 140px;
            padding: 10px 18px;
            background: #f7f8fa;
            font-size: .72rem;
            letter-spacing: .04em;
            text-transform: uppercase;
            color: #98a1b3;
            font-weight: 600;
            border-bottom: 1px solid #e7e9ee;
        }

        .category-empty {
            padding: 28px 18px;
            color: #8a92a3;
            font-size: .9rem;
        }

        .category-empty a {
            color: #4f5fea;
            font-weight: 600;
        }

        .cat-row {
            display: grid;
            grid-template-columns: 1fr 90px 140px;
            align-items: center;
            padding: 9px 18px 9px 12px;
            border-bottom: 1px solid #eef0f4;
            border-left: 3px solid transparent;
        }

        .cat-row:last-child {
            border-bottom: none;
        }

        .cat-row:hover {
            background: #fafbfd;
        }

        .cat-row[data-depth="0"] {
            border-left-color: #6366f1;
        }

        .cat-row[data-depth="1"] {
            border-left-color: #38bdf8;
        }

        .cat-row[data-depth="2"] {
            border-left-color: #34d399;
        }

        .cat-row[data-depth="3"] {
            border-left-color: #fbbf24;
        }

        .cat-row[data-depth="4"] {
            border-left-color: #fb7185;
        }

        .cat-name-cell {
            display: flex;
            align-items: center;
            min-width: 0;
        }

        .cat-indent {
            flex-shrink: 0;
        }

        .cat-toggle {
            width: 18px;
            height: 18px;
            border: none;
            background: transparent;
            color: #98a1b3;
            font-size: .7rem;
            flex-shrink: 0;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: transform .15s ease;
        }

        .cat-toggle.collapsed {
            transform: rotate(-90deg);
        }

        .cat-toggle-spacer {
            width: 18px;
            flex-shrink: 0;
        }

        .cat-label {
            font-size: .9rem;
            color: #2b3242;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .cat-row[data-depth="0"] .cat-label {
            font-weight: 600;
        }

        .cat-count {
            font-size: .78rem;
            color: #98a1b3;
        }

        .cat-actions {
            display: flex;
            gap: 6px;
            justify-content: flex-end;
        }

        .cat-icon-btn {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            border: 1px solid #e7e9ee;
            background: #fff;
            color: #8a92a3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: .8rem;
            text-decoration: none;
            transition: all .12s ease;
        }

        .cat-icon-btn:hover {
            border-color: #c7cbd4;
            color: #2b3242;
        }

        .cat-icon-btn.danger:hover {
            border-color: #f3b9b9;
            color: #d33f3f;
            background: #fff5f5;
        }

        .cat-children.collapsed-children {
            display: none;
        }
    </style>

    <script>
        document.addEventListener('click', function(e) {
            const toggle = e.target.closest('.cat-toggle');
            if (!toggle) return;
            const row = toggle.closest('.cat-row');
            const children = row.nextElementSibling;
            if (children && children.classList.contains('cat-children')) {
                children.classList.toggle('collapsed-children');
                toggle.classList.toggle('collapsed');
            }
        });

        document.querySelectorAll('.delete-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                if (!confirm('Delete this category and all its subcategories?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection
