@extends('layouts.admin')

@section('title', __('permission.Permission_List'))
@section('content-header', __('permission.Permission_List'))
@section('content-actions')
    @can('permissions.create')
        <a href="{{route('permissions.create')}}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> {{ __('permission.Add_Permission') }}
        </a>
    @endcan
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
    <style>
        .permission-list-container {
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

        /* Table Container dengan Scroll */
        .table-container {
            overflow: hidden;
            position: relative;
        }

        .table-scroll-wrapper {
            overflow: auto;
            max-height: 70vh;
        }

        .permission-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
            min-width: 800px;
        }

        .permission-table thead {
            background-color: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .permission-table th {
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

        .permission-table td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            color: #4a5568;
            font-size: 0.9rem;
            white-space: nowrap;
        }

        .permission-table tbody tr {
            transition: all 0.3s ease;
            background-color: white;
        }

        .permission-table tbody tr:hover {
            background-color: #f7fafc;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        .permission-table tbody tr:last-child td {
            border-bottom: none;
        }

        .permission-name {
            font-weight: 600;
            color: #2d3748;
            min-width: 150px;
        }

        .no-description {
            color: #9ca3af;
            font-style: italic;
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
        }

        .search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.875rem;
            transition: all 0.3s ease;
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

        .search-box {
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

            .permission-table th,
            .permission-table td {
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

        .permission-table tbody tr {
            animation: fadeInUp 0.5s ease;
        }

        .permission-table tbody tr:nth-child(even) {
            animation-delay: 0.1s;
        }

        .permission-table tbody tr:nth-child(odd) {
            animation-delay: 0.2s;
        }
    </style>
@endsection

@section('content')
    <div class="permission-list-container">
        <div class="card">
            <div class="card-body">

                <!-- Search + Reset -->
                <div class="search-filter-container">
                    <input type="hidden" name="sort_by" value="{{ request('sort_by', 'created_at') }}">
                    <input type="hidden" name="sort_order" value="{{ request('sort_order', 'desc') }}">

                    <div class="search-box">
                        <label class="date-label">Search</label>
                        <div class="search-input-wrapper">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" class="search-input" placeholder="Search permissions..." id="searchInput"
                                value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="filter-group">
                        <label class="date-label" style="visibility:hidden;">Reset</label>
                        <a href="{{ route('permissions.index') }}" class="btn btn-secondary reset-button">
                            <i class="fas fa-refresh"></i> Reset
                        </a>
                    </div>
                </div>

                <!-- TABLE -->
                <div class="table-container">
                    <div class="table-scroll-wrapper">

                        @if ($permissions->count() > 0)
                            <table class="permission-table">
                                <thead>
                                    <tr>
                                        <th class="th-sortable">
                                            Name
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'name', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                                class="sort-link">
                                                <i class="fas fa-sort"></i>
                                            </a>
                                        </th>
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
                                    @foreach ($permissions as $p)
                                        <tr>
                                            <td><strong>{{ $p->name }}</strong></td>
                                            <td>{{ $p->created_at->format('M d, Y') }}</td>
                                            <td>
                                                @canany(['permissions.edit', 'permissions.delete'])
                                                    <div class="action-buttons">

                                                        @can('permissions.edit')
                                                            <a href="{{ route('permissions.edit', $p) }}" class="btn btn-info btn-sm">
                                                                <i class="fas fa-edit"></i>
                                                                <span class="d-none d-md-inline">Edit</span>
                                                            </a>
                                                        @endcan

                                                        @can('permissions.delete')
                                                            <button class="btn btn-danger btn-sm delete-permission"
                                                                data-url="{{ route('permissions.destroy', $p) }}"
                                                                data-name="{{ $p->name }}">
                                                                <i class="fas fa-trash"></i>
                                                                <span class="d-none d-md-inline">Delete</span>
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
                                    <i class="fas fa-lock"></i>
                                </div>
                                <h3 class="empty-state-title">No Permissions Found</h3>
                                <p class="empty-state-description">Create your first permission.</p>

                                @can('permissions.create')
                                    <a href="{{ route('permissions.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus-circle"></i> Add Permission
                                    </a>
                                @endcan
                            </div>
                        @endif

                    </div>
                </div>

                <!-- PAGINATION -->
                @if($permissions->count() > 0)
                    <div class="pagination-container">
                        <div class="pagination-info">
                            Showing {{ $permissions->firstItem() }} to {{ $permissions->lastItem() }} of
                            {{ $permissions->total() }} permissions
                        </div>
                        <nav>
                            {{ $permissions->appends(request()->query())->links() }}
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

            function updateURL() {
                const params = new URLSearchParams();

                if (searchInput.value) params.append('search', searchInput.value);

                const currentUrl = new URL(window.location.href);
                params.append('sort_by', currentUrl.searchParams.get('sort_by') || 'created_at');
                params.append('sort_order', currentUrl.searchParams.get('sort_order') || 'desc');

                window.location.href = `{{ route('permissions.index') }}?${params.toString()}`;
            }

            let timeout;
            searchInput.addEventListener('input', function () {
                clearTimeout(timeout);
                timeout = setTimeout(updateURL, 500);
            });

            // DELETE CONFIRM
            document.querySelectorAll('.delete-permission').forEach(btn => {
                btn.addEventListener('click', function () {
                    const url = this.dataset.url;
                    const name = this.dataset.name;

                    Swal.fire({
                        title: 'Delete Permission?',
                        text: `You are about to delete permission: ${name}. This action cannot be undone!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e53e3e',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true
                    }).then(result => {
                        if (result.isConfirmed) {
                            fetch(url, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            }).then(() => window.location.reload());
                        }
                    });
                });
            });

        });
    </script>
@endsection