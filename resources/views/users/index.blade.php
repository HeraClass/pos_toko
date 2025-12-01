@extends('layouts.admin')

@section('title', __('Users Management'))
@section('content-header', __('Users Management'))
@section('content-actions')
    @can('users.create')
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> {{ __('Add User') }}
        </a>
    @endcan
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
    <style>
        .user-list-container {
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
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2d3748;
            margin: 0;
        }

        .card-body {
            padding: 0;
        }

        .btn-primary {
            background-color: #4361ee;
            color: white;
        }

        .table-container {
            overflow: hidden;
            position: relative;
        }

        .table-scroll-wrapper {
            overflow: auto;
            max-height: 70vh;
        }

        .user-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
            min-width: 1000px;
        }

        .user-table th {
            padding: 1rem 1.5rem;
            text-align: left;
            font-weight: 600;
            color: #4a5568;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid #e2e8f0;
            background-color: #f8fafc;
            position: sticky;
            top: 0;
            white-space: nowrap;
        }

        .user-table td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            color: #4a5568;
            font-size: 0.9rem;
            white-space: nowrap;
        }

        .user-table tbody tr {
            transition: all 0.3s ease;
            background-color: white;
        }

        .user-table tbody tr:hover {
            background-color: #f7fafc;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        .user-name {
            font-weight: 600;
            color: #2d3748;
            min-width: 120px;
        }

        .user-email {
            color: #6b7280;
            min-width: 150px;
        }

        .user-roles {
            display: flex;
            flex-wrap: wrap;
            gap: 0.25rem;
            max-width: 200px;
            min-width: 150px;
        }

        .role-badge {
            background-color: #4299e1;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .no-roles {
            color: #9ca3af;
            font-style: italic;
            font-size: 0.8rem;
        }

        .created-at {
            color: #6b7280;
            font-size: 0.875rem;
            min-width: 120px;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            min-width: 150px;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.8rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            text-decoration: none;
            min-height: 36px;
        }

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
            min-height: 32px;
        }

        .btn-info {
            background-color: #4299e1;
            color: white;
        }

        .btn-info:hover {
            background-color: #3182ce;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(66, 153, 225, 0.3);
        }

        .btn-danger {
            background-color: #e53e3e;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c53030;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(229, 62, 62, 0.3);
        }

        .search-filter-container {
            display: flex;
            gap: 1rem;
            padding: 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            background-color: #f8fafc;
            flex-wrap: wrap;
            align-items: flex-end;
        }

        .search-box {
            position: relative;
            flex: 1;
            min-width: 250px;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .search-input-wrapper {
            position: relative;
            width: 100%;
        }

        .search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.875rem;
            background-color: white;
        }

        .search-input:focus {
            outline: none;
            border-color: #4361ee;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        }

        .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
        }

        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            border-top: 1px solid #e2e8f0;
            background-color: #f8fafc;
        }

        .pagination-info {
            color: #6b7280;
            font-size: 0.875rem;
        }

        .pagination {
            display: flex;
            gap: 0.5rem;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .date-label {
            font-size: 0.75rem;
            color: #6b7280;
            font-weight: 500;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            min-width: 150px;
        }

        .filter-select {
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.875rem;
            background-color: white;
            min-width: 150px;
            cursor: pointer;
        }

        .filter-select:focus {
            outline: none;
            border-color: #4361ee;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        }

        .reset-button {
            height: 46px;
            display: flex;
            align-items: center;
            justify-content: center;
            white-space: nowrap;
        }

        .btn-secondary {
            background-color: #718096;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #4a5568;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(113, 128, 150, 0.3);
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: #6b7280;
        }

        .empty-state-icon {
            font-size: 3rem;
            color: #d1d5db;
            margin-bottom: 1rem;
        }

        .empty-state-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .empty-state-description {
            color: #6b7280;
            margin-bottom: 1.5rem;
        }

        .page-item {
            margin: 0;
        }

        .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            padding: 0 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            color: #4b5563;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            background-color: white;
        }

        .page-link:hover {
            background-color: #f3f4f6;
            border-color: #9ca3af;
        }

        .page-item.active .page-link {
            background-color: #4361ee;
            border-color: #4361ee;
            color: white;
        }

        .page-item.disabled .page-link {
            color: #9ca3af;
            background-color: #f9fafb;
            border-color: #d1d5db;
            cursor: not-allowed;
        }

        .sort-link {
            color: #6b7280;
            text-decoration: none;
            margin-left: 0.25rem;
            transition: color 0.3s ease;
            display: inline-flex;
            align-items: center;
        }

        .sort-link:hover {
            color: #4361ee;
        }

        .th-sortable {
            cursor: pointer;
            user-select: none;
            position: relative;
            transition: background-color 0.3s ease;
        }

        .th-sortable:hover {
            background-color: #f1f5f9;
        }

        .sort-icon {
            font-size: 0.8em;
            margin-left: 4px;
            transition: all 0.3s ease;
        }

        .current-sort .sort-icon {
            color: #4361ee;
        }

        .table-scroll-wrapper::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .table-scroll-wrapper::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .table-scroll-wrapper::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        .table-scroll-wrapper::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        @media (max-width: 768px) {

            .user-table th,
            .user-table td {
                padding: 0.75rem 1rem;
            }

            .search-filter-container {
                flex-direction: column;
                align-items: stretch;
            }

            .search-box {
                min-width: 100%;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .pagination-container {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .table-scroll-wrapper {
                max-height: 60vh;
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .user-table tbody tr {
            animation: fadeInUp 0.5s ease;
        }

        .user-table tbody tr:nth-child(even) {
            animation-delay: 0.1s;
        }

        .user-table tbody tr:nth-child(odd) {
            animation-delay: 0.2s;
        }
    </style>
@endsection

@section('content')
    <div class="user-list-container">
        <div class="card">
            <div class="card-body">
                <!-- Search Section -->
                <div class="search-filter-container">
                    <div class="search-box">
                        <label class="date-label">Search</label>
                        <div class="search-input-wrapper">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" class="search-input" placeholder="Search users by name or email..."
                                id="searchInput" value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="filter-group">
                        <label class="date-label" style="visibility:hidden;">Reset</label>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary reset-button" id="resetButton">
                            <i class="fas fa-refresh"></i> Reset
                        </a>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-container">
                    <div class="table-scroll-wrapper">
                        @if($users->count() > 0)
                            <table class="user-table">
                                <thead>
                                    <tr>
                                        <th class="th-sortable">
                                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'id', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}"
                                                class="sort-link">
                                                ID
                                                @if(request('sort') === 'id')
                                                    <i
                                                        class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} sort-icon"></i>
                                                @else
                                                    <i class="fas fa-sort sort-icon"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="th-sortable">
                                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'first_name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}"
                                                class="sort-link">
                                                Name
                                                @if(request('sort') === 'first_name')
                                                    <i
                                                        class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} sort-icon"></i>
                                                @else
                                                    <i class="fas fa-sort sort-icon"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="th-sortable">
                                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'email', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}"
                                                class="sort-link">
                                                Email
                                                @if(request('sort') === 'email')
                                                    <i
                                                        class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} sort-icon"></i>
                                                @else
                                                    <i class="fas fa-sort sort-icon"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th>Roles</th>
                                        <th class="th-sortable">
                                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}"
                                                class="sort-link">
                                                Created At
                                                @if(request('sort') === 'created_at')
                                                    <i
                                                        class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} sort-icon"></i>
                                                @else
                                                    <i class="fas fa-sort sort-icon"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>#{{ $user->id }}</td>
                                            <td class="user-name">{{ $user->first_name }} {{ $user->last_name }}</td>
                                            <td class="user-email">{{ $user->email }}</td>
                                            <td class="user-roles">
                                                @if($user->roles->count() > 0)
                                                    @foreach($user->roles as $role)
                                                        <span class="role-badge">{{ $role->name }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="no-roles">No roles</span>
                                                @endif
                                            </td>
                                            <td class="created-at">{{ $user->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <div class="action-buttons">
                                                    @can('users.edit')
                                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-info btn-sm">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </a>
                                                    @endcan
                                                    @can('users.delete')
                                                        <button class="btn btn-danger btn-sm delete-user"
                                                            data-url="{{ route('users.destroy', $user) }}"
                                                            data-name="{{ $user->first_name }}">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </button>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="fas fa-user"></i></div>
                                <h3 class="empty-state-title">No Users Found</h3>
                                <p class="empty-state-description">Get started by creating your first user.</p>
                                @can('users.create')
                                    <a href="{{ route('users.create') }}" class="btn btn-primary"><i class="fas fa-plus-circle"></i>
                                        Create User</a>
                                @endcan
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Pagination -->
                @if($users->count() > 0)
                    <div class="pagination-container">
                        <div class="pagination-info">
                            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
                        </div>
                        <nav>
                            {{ $users->appends(request()->query())->links() }}
                        </nav>
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
            // Search functionality dengan server-side filtering
            const searchInput = document.getElementById('searchInput');
            const resetButton = document.getElementById('resetButton');

            // Function untuk update URL dengan filters
            function updateURL() {
                const params = new URLSearchParams();

                // Add search
                if (searchInput.value) params.append('search', searchInput.value);

                // Preserve sort parameters
                const currentUrl = new URL(window.location.href);
                const sort = currentUrl.searchParams.get('sort') || 'id';
                const direction = currentUrl.searchParams.get('direction') || 'desc';

                params.append('sort', sort);
                params.append('direction', direction);

                console.log('Redirecting to:', `{{ route('users.index') }}?${params.toString()}`);

                // Redirect ke URL baru dengan filters
                window.location.href = `{{ route('users.index') }}?${params.toString()}`;
            }

            // Event listeners untuk search dengan debounce
            let searchTimeout;
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(updateURL, 500);
            });

            // Reset button functionality
            resetButton.addEventListener('click', function (e) {
                e.preventDefault();
                window.location.href = '{{ route('users.index') }}';
            });

            // Add visual indicators untuk current sort
            const currentSort = '{{ request("sort", "id") }}';
            const currentDirection = '{{ request("direction", "desc") }}';

            document.querySelectorAll('.sort-link').forEach(link => {
                const url = new URL(link.href);
                const sort = url.searchParams.get('sort');

                if (sort === currentSort) {
                    const icon = link.querySelector('i');
                    if (icon) {
                        // Update icon berdasarkan sort direction
                        icon.className = currentDirection === 'asc' ?
                            'fas fa-sort-up sort-icon' : 'fas fa-sort-down sort-icon';
                        link.classList.add('current-sort');
                        // Tambahkan class ke parent th juga
                        link.closest('th').classList.add('current-sort');
                    }
                }
            });

            // Delete user
            document.querySelectorAll('.delete-user').forEach(btn => {
                btn.addEventListener('click', function () {
                    const url = this.dataset.url;
                    const name = this.dataset.name;
                    Swal.fire({
                        title: 'Are you sure?',
                        text: `You are about to delete user: ${name}. This action cannot be undone!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e53e3e',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = url;
                            const csrfInput = document.createElement('input');
                            csrfInput.name = '_token';
                            csrfInput.value = '{{ csrf_token() }}';
                            const methodInput = document.createElement('input');
                            methodInput.name = '_method';
                            methodInput.value = 'DELETE';
                            form.appendChild(csrfInput);
                            form.appendChild(methodInput);
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection