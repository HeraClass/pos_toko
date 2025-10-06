@extends('layouts.admin')

@section('title', __('category.Category_List'))
@section('content-header', __('category.Category_List'))
@section('content-actions')
    <div style="display: flex; align-items: center; gap: 1rem;">
        <div class="search-input" style="min-width: 250px;">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="{{ __('category.Search_Categories') }}"
                onkeyup="filterCategories()">
        </div>
        <a href="{{route('categories.create')}}" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> {{ __('category.Add_Category') }}
        </a>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
    <style>
        .categories-container {
            padding: 0.5rem;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            padding: 1.5rem;
            display: flex;
            justify-content: between;
            align-items: center;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #2d3748;
            margin: 0;
        }

        .card-body {
            padding: 0;
        }

        .categories-table {
            width: 100%;
            border-collapse: collapse;
        }

        .categories-table th {
            background-color: #f7fafc;
            padding: 1rem 1.25rem;
            text-align: left;
            font-weight: 600;
            color: #4a5568;
            border-bottom: 2px solid #e2e8f0;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .categories-table td {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
            color: #4a5568;
        }

        .categories-table tr:last-child td {
            border-bottom: none;
        }

        .categories-table tr:hover {
            background-color: #f8f9fa;
        }

        .avatar-container {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid #e2e8f0;
            transition: transform 0.3s ease;
        }

        .categories-table tr:hover .avatar-container {
            transform: scale(1.05);
        }

        .avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .categorie-name {
            font-weight: 500;
            color: #2d3748;
        }

        .category-email {
            color: #4a5568;
            font-size: 0.9rem;
        }

        .category-phone {
            font-weight: 500;
            color: #2d3748;
        }

        .category-address {
            color: #4a5568;
            font-size: 0.9rem;
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn-action {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-edit {
            background-color: #ebf8ff;
            color: #3182ce;
        }

        .btn-edit:hover {
            background-color: #bee3f8;
            transform: translateY(-1px);
        }

        .btn-delete {
            background-color: #fed7d7;
            color: #e53e3e;
        }

        .btn-delete:hover {
            background-color: #feb2b2;
            transform: translateY(-1px);
        }

        .pagination-container {
            display: flex;
            justify-content: center;
            padding: 1.5rem;
            background: white;
            border-top: 1px solid #e2e8f0;
        }

        .pagination {
            display: flex;
            gap: 0.5rem;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .page-item {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .page-link {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            color: #4a5568;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .page-link:hover {
            background-color: #f7fafc;
            border-color: #cbd5e0;
        }

        .page-item.active .page-link {
            background-color: #4361ee;
            border-color: #4361ee;
            color: white;
        }

        .page-item.disabled .page-link {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #a0aec0;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-state p {
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
        }

        .search-input {
            position: relative;
        }

        .search-input input {
            width: 100%;
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.9rem;
            height: 38px;
            transition: all 0.3s ease;
        }

        .search-input input:focus {
            outline: none;
            border-color: #4361ee;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        }

        .search-input i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
        }

        @media (max-width: 768px) {
            .categories-table-container {
                margin: 0 -1rem;
            }

            .categories-table {
                min-width: 800px;
            }

            .search-input {
                min-width: 100%;
                margin-bottom: 1rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-action {
                width: 32px;
                height: 32px;
            }

            @section('content-actions')
                <div style="display: flex; flex-direction: column; gap: 1rem; width: 100%;"><div class="search-input"><i class="fas fa-search"></i><input type="text" id="searchInput" placeholder="{{ __('category.Search_Categories') }}"
                onkeyup="filtercategories()"></div><a href="{{route('categories.create')}}" class="btn btn-primary" style="align-self: flex-start;"><i class="fas fa-user-plus"></i>
                {{ __('category.Add_Category') }}
            </a></div>@endsection
        }

        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
        }

        .created-at {
            color: #718096;
            font-size: 0.875rem;
        }

        /* Style untuk header actions */
        .card-header .card-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .btn i {
            font-size: 14px;   /* kecilin ukuran icon */
            margin-right: 6px; /* kasih jarak biar gak nempel teks */
            padding: auto
        }
    </style>
@endsection

@section('content')
    <div class="categories-container">
        <div class="card">
            <div class="card-body">
                <!-- Categories Table -->
                <div class="table-responsive">
                    <table class="categories-table">
                        <thead>
                            <tr>
                                <th>{{ __('category.ID') }}</th>
                                <th>{{ __('category.Name') }}</th>
                                <th>{{ __('category.Description') }}</th>
                                <th>{{ __('common.Created_At') }}</th>
                                <th>{{ __('category.Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td>{{$category->id}}</td>
                                    <td>
                                        <div class="category-name">{{$category->name}}</div>
                                    </td>
                                    <td>
                                        <div class="category-description">
                                            @if(!empty(trim($category->description)))
                                                {{$category->description}}
                                            @else
                                                <span style="color: #a0aec0; font-style: italic;">{{ __('category.No_Description') }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="created-at">{{$category->created_at->format('M d, Y')}}</div>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('categories.edit', $category) }}" class="btn-action btn-edit"
                                                title="{{ __('category.Edit_Category') }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn-action btn-delete"
                                                data-url="{{route('categories.destroy', $category)}}"
                                                title="{{ __('category.Delete_Category') }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            @if($categories->count() === 0)
                                <tr>
                                    <td colspan="8">
                                        <div class="empty-state">
                                            <i class="fas fa-users fa-2x"></i>
                                            <p>{{ __('category.No_Categories_Found') }}</p>
                                            <a href="{{route('categories.create')}}" class="btn btn-primary">
                                                <i class="fas fa-user-plus fa-sm"></i> {{ __('category.Add_Category') }}
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($categories->count() > 0)
                    <div class="pagination-container">
                        {{ $categories->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // SweetAlert delete confirmation
            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', function () {
                    const url = this.dataset.url;
                    const categoryName = this.closest('tr').querySelector('.category-name').textContent;

                    Swal.fire({
                        title: '{{ __("category.sure") }}',
                        text: '{{ __("category.really_delete") }}: ' + categoryName + '?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#4361ee',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: '{{ __("category.yes_delete") }}',
                        cancelButtonText: '{{ __("category.No") }}',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(url, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                }
                            })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire({
                                            title: '{{ __("category.Deleted") }}',
                                            text: '{{ __("category.Deleted_Message") }}',
                                            icon: 'success',
                                            confirmButtonColor: '#4361ee'
                                        });

                                        // Fade out and remove the row
                                        const row = button.closest('tr');
                                        row.style.opacity = 0;
                                        setTimeout(() => row.remove(), 500);
                                    }
                                })
                                .catch(error => {
                                    Swal.fire({
                                        title: '{{ __("category.Error") }}',
                                        text: '{{ __("category.Delete_Error") }}',
                                        icon: 'error',
                                        confirmButtonColor: '#4361ee'
                                    });
                                });
                        }
                    });
                });
            });

            // Enhance pagination styling
            const pagination = document.querySelector('.pagination');
            if (pagination) {
                pagination.classList.add('pagination');
                pagination.querySelectorAll('li').forEach(li => {
                    li.classList.add('page-item');
                    const link = li.querySelector('a, span');
                    if (link) {
                        link.classList.add('page-link');
                        if (li.classList.contains('active')) {
                            link.classList.add('active');
                        }
                        if (li.classList.contains('disabled')) {
                            link.classList.add('disabled');
                        }
                    }
                });
            }
        });

        function filterCategories() {
            const searchText = document.getElementById('searchInput').value.toLowerCase();

            document.querySelectorAll('.categories-table tbody tr').forEach(row => {
                if (row.querySelector('.empty-state')) return;

                const name = row.querySelector('.category-name').textContent.toLowerCase();
                const description = row.querySelector('.category-description').textContent.toLowerCase();

                const nameMatch = name.includes(searchText);
                const descriptionMatch = description.includes(searchText);

                row.style.display = (nameMatch || descriptionMatch) ? '' : 'none';
            });
        }
    </script>
@endsection