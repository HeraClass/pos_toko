@extends('layouts.admin')

@section('title', __('role.Role_List'))
@section('content-header', __('role.Role_List'))
@section('content-actions')
    @can('roles.create')
        <a href="{{ route('roles.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> {{ __('role.Add_Role') }}
        </a>
    @endcan
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
    <style>
        /* Reuse category styles for roles */
        .role-list-container {
            padding: 0.5rem;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .card-body {
            padding: 0;
        }

        .table-container {
            overflow: hidden;
            position: relative;
        }

        .table-scroll-wrapper {
            overflow: auto;
            max-height: 70vh;
        }

        .role-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
            min-width: 800px;
        }

        .role-table thead {
            background-color: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .role-table th {
            padding: 1rem 1.5rem;
            text-align: left;
            font-weight: 600;
            color: #4a5568;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid #e2e8f0;
            position: sticky;
            top: 0;
            white-space: nowrap;
        }

        .role-table td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            color: #4a5568;
            font-size: 0.9rem;
            white-space: nowrap;
        }

        .role-table tbody tr:hover {
            background-color: #f7fafc;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        .role-name {
            font-weight: 600;
            color: #2d3748;
            min-width: 150px;
        }

        .permission-badge {
            background-color: #4299e1;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            font-size: 0.75rem;
            margin-right: 0.25rem;
            display: inline-block;
            margin-bottom: 0.25rem;
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

        .btn-primary {
            background-color: #4361ee;
            color: white;
        }

        .btn-primary:hover {
            background-color: #3a56d4;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(67, 97, 238, 0.3);
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

        .search-input-wrapper {
            position: relative;
            width: 100%;
        }

        .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
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

        /* Sorting Styles */
        .th-sortable {
            cursor: pointer;
            user-select: none;
            position: relative;
            transition: background-color 0.3s ease;
        }

        .th-sortable:hover {
            background-color: #f1f5f9;
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

        .sort-icon {
            font-size: 0.8em;
            margin-left: 4px;
            transition: all 0.3s ease;
        }

        .current-sort .sort-icon {
            color: #4361ee;
        }

        /* Scrollbar Styling */
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

            .role-table th,
            .role-table td {
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

        /* Animation for table rows */
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

        .role-table tbody tr {
            animation: fadeInUp 0.5s ease;
        }

        .role-table tbody tr:nth-child(even) {
            animation-delay: 0.1s;
        }

        .role-table tbody tr:nth-child(odd) {
            animation-delay: 0.2s;
        }
    </style>
@endsection

@section('content')
    <div class="role-list-container">
        <div class="card">
            <div class="card-body">
                <div class="search-filter-container">
                    <input type="hidden" name="sort_by" value="{{ request('sort_by', 'created_at') }}">
                    <input type="hidden" name="sort_order" value="{{ request('sort_order', 'desc') }}">
                    <div class="search-box">
                        <label class="date-label">Search</label>
                        <div class="search-input-wrapper">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" class="search-input" placeholder="Search roles..." id="searchInput"
                                value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="filter-group">
                        <label class="date-label" style="visibility:hidden;">Reset</label>
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary reset-button" id="resetButton">
                            <i class="fas fa-refresh"></i> Reset
                        </a>
                    </div>
                </div>

                <div class="table-container">
                    <div class="table-scroll-wrapper">
                        @if($roles->count() > 0)
                            <table class="role-table">
                                <thead>
                                    <tr>
                                        <th class="th-sortable">
                                            Name
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'name', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                                class="sort-link">
                                                <i class="fas fa-sort"></i>
                                            </a>
                                        </th>
                                        <th>Permissions</th>
                                        <th class="th-sortable">
                                            Created At
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'created_at', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                                class="sort-link">
                                                <i class="fas fa-sort"></i>
                                            </a>
                                        </th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($roles as $role)
                                        <tr>
                                            <td class="role-name">{{ $role->name }}</td>
                                            <td>
                                                <div style="display: flex; flex-wrap: wrap; gap: 0.25rem; max-width: 500px;">
                                                    @forelse($role->permissions as $perm)
                                                        <span class="permission-badge">{{ $perm->name }}</span>
                                                    @empty
                                                        <span class="no-description">No Permissions</span>
                                                    @endforelse
                                                </div>
                                            </td>
                                            <td class="created-at">{{ $role->created_at->format('M d, Y') }}</td>
                                            <td>
                                                @canany(['roles.edit', 'roles.delete'])
                                                    <div class="action-buttons">
                                                        @can('roles.edit')
                                                            <a href="{{ route('roles.edit', $role) }}" class="btn btn-info btn-sm">
                                                                <i class="fas fa-edit"></i> <span class="d-none d-md-inline">Edit</span>
                                                            </a>
                                                        @endcan
                                                        @can('roles.delete')
                                                            <button class="btn btn-danger btn-sm delete-role"
                                                                data-url="{{ route('roles.destroy', $role) }}"
                                                                data-name="{{ $role->name }}">
                                                                <i class="fas fa-trash"></i> <span class="d-none d-md-inline">Delete</span>
                                                            </button>
                                                        @endcan
                                                    </div>
                                                @else
                                                    <span class="badge bg-secondary">No Action</span>
                                                @endcanany
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h3 class="empty-state-title">No Roles Found</h3>
                                <p class="empty-state-description">Create your first role.</p>
                                @can('roles.create')
                                    <a href="{{ route('roles.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus-circle"></i> Add Role
                                    </a>
                                @endcan
                            </div>
                        @endif
                    </div>
                </div>

                @if($roles->count() > 0)
                    <div class="pagination-container">
                        <div class="pagination-info">
                            Showing {{ $roles->firstItem() }} to {{ $roles->lastItem() }} of {{ $roles->total() }} roles
                        </div>
                        <nav>
                            {{ $roles->appends(request()->query())->links() }}
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
            const searchInput = document.getElementById('searchInput');
            const resetButton = document.getElementById('resetButton');

            // Debounce search
            let timeout;
            searchInput.addEventListener('input', function () {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    const params = new URLSearchParams();
                    if (searchInput.value) params.append('search', searchInput.value);

                    const currentUrl = new URL(window.location.href);
                    params.append('sort_by', currentUrl.searchParams.get('sort_by') || 'created_at');
                    params.append('sort_order', currentUrl.searchParams.get('sort_order') || 'desc');

                    window.location.href = `{{ route('roles.index') }}?${params.toString()}`;
                }, 500);
            });

            // Delete role
            document.querySelectorAll('.delete-role').forEach(btn => {
                btn.addEventListener('click', function () {
                    const url = this.dataset.url;
                    const name = this.dataset.name;

                    Swal.fire({
                        title: 'Delete Role?',
                        text: `You are about to delete role: ${name}. This action cannot be undone!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e53e3e',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true
                    }).then(result => {
                        if (result.isConfirmed) {
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = url;
                            form.innerHTML = `
                                        @csrf
                                        @method('DELETE')
                                    `;
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                });
            });

            // Reset search
            resetButton.addEventListener('click', function (e) {
                e.preventDefault();
                window.location.href = '{{ route('roles.index') }}';
            });
        });
    </script>
@endsection