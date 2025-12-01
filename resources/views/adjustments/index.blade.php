@extends('layouts.admin')

@section('title', __('adjustment.Adjustments'))
@section('content-header', __('adjustment.Adjustments'))
@section('content-actions')
    <x-export-button route="adjustments" :filters="request()->all()" title="Export Adjustment" filename="laporan_adjustments" />
    @can('adjustments.create')
        <a href="{{route('adjustments.create')}}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> {{ __('adjustment.Create_Adjustment') }}
        </a>
    @endcan
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
    <style>
        .adjustment-list-container {
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

        .adjustment-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
            min-width: 1000px;
        }

        .adjustment-table thead {
            background-color: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .adjustment-table th {
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

        .adjustment-table td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            color: #4a5568;
            font-size: 0.9rem;
            white-space: nowrap;
        }

        .adjustment-table tbody tr {
            transition: all 0.3s ease;
            background-color: white;
        }

        .adjustment-table tbody tr:hover {
            background-color: #f7fafc;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        .adjustment-table tbody tr:last-child td {
            border-bottom: none;
        }

        .adjustment-id {
            font-weight: 600;
            color: #2d3748;
            min-width: 80px;
        }

        .adjustment-product {
            font-weight: 600;
            color: #2d3748;
            min-width: 150px;
        }

        .adjustment-type {
            min-width: 100px;
        }

        .adjustment-quantity {
            text-align: center;
            min-width: 80px;
        }

        .adjustment-reason {
            color: #6b7280;
            max-width: 300px;
            min-width: 200px;
            white-space: normal !important;
        }

        .no-reason {
            color: #9ca3af;
            font-style: italic;
        }

        .adjustment-user {
            color: #4a5568;
            min-width: 120px;
        }

        .adjustment-date {
            color: #6b7280;
            font-size: 0.875rem;
            min-width: 140px;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            min-width: 120px;
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

        .badge {
            padding: 0.35em 0.65em;
            font-size: 0.75em;
            font-weight: 600;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .badge-success {
            background-color: #10b981;
            color: white;
        }

        .badge-danger {
            background-color: #ef4444;
            color: white;
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

            .adjustment-table th,
            .adjustment-table td {
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

        .adjustment-table tbody tr {
            animation: fadeInUp 0.5s ease;
        }

        .adjustment-table tbody tr:nth-child(even) {
            animation-delay: 0.1s;
        }

        .adjustment-table tbody tr:nth-child(odd) {
            animation-delay: 0.2s;
        }
    </style>
@endsection

@section('content')
    <div class="adjustment-list-container">
        <div class="card">
            <div class="card-body">
                <!-- Search and Filter Section -->
                <div class="search-filter-container">
                    <!-- Hidden fields untuk preserve sort parameters -->
                    <input type="hidden" name="sort_by" value="{{ request('sort_by', 'adjusted_at') }}">
                    <input type="hidden" name="sort_order" value="{{ request('sort_order', 'desc') }}">

                    <div class="search-box">
                        <label class="date-label">Search</label>
                        <div class="search-input-wrapper">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" class="search-input" placeholder="Search by product name, reason, or user..."
                                id="searchInput" value="{{ request('search') }}">
                        </div>
                    </div>

                    <!-- Type Filter -->
                    <div class="filter-group">
                        <label class="date-label">Type</label>
                        <select class="filter-select" id="typeFilter">
                            <option value="">All Types</option>
                            <option value="increase" {{ request('type') == 'increase' ? 'selected' : '' }}>Increase</option>
                            <option value="decrease" {{ request('type') == 'decrease' ? 'selected' : '' }}>Decrease</option>
                        </select>
                    </div>

                    <!-- Date Filter -->
                    <div class="filter-group">
                        <label class="date-label">Date From</label>
                        <input type="date" class="filter-select" id="dateFrom" value="{{ request('date_from') }}">
                    </div>

                    <div class="filter-group">
                        <label class="date-label">Date To</label>
                        <input type="date" class="filter-select" id="dateTo" value="{{ request('date_to') }}">
                    </div>

                    <!-- Reset Button -->
                    <div class="filter-group">
                        <label class="date-label" style="visibility: hidden;">Reset</label>
                        <a href="{{ route('adjustments.index') }}" class="btn btn-secondary reset-button" id="resetButton">
                            <i class="fas fa-refresh"></i> Reset
                        </a>
                    </div>
                </div>

                <!-- Table Section dengan Scroll -->
                <div class="table-container">
                    <div class="table-scroll-wrapper">
                        @if($adjustments->count() > 0)
                            <table class="adjustment-table">
                                <thead>
                                    <tr>
                                        <th class="th-sortable">
                                            ID
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'id', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                                class="sort-link">
                                                <i class="fas fa-sort"></i>
                                            </a>
                                        </th>
                                        <th class="th-sortable">
                                            Product
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'product_id', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                                class="sort-link">
                                                <i class="fas fa-sort"></i>
                                            </a>
                                        </th>
                                        <th class="th-sortable">
                                            Type
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'type', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                                class="sort-link">
                                                <i class="fas fa-sort"></i>
                                            </a>
                                        </th>
                                        <th class="th-sortable">
                                            Quantity
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'quantity', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                                class="sort-link">
                                                <i class="fas fa-sort"></i>
                                            </a>
                                        </th>
                                        <th>Reason</th>
                                        <th class="th-sortable">
                                            Adjusted By
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'user_id', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                                class="sort-link">
                                                <i class="fas fa-sort"></i>
                                            </a>
                                        </th>
                                        <th class="th-sortable">
                                            Adjusted At
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'adjusted_at', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                                class="sort-link">
                                                <i class="fas fa-sort"></i>
                                            </a>
                                        </th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($adjustments as $adjustment)
                                        <tr>
                                            <td>
                                                <div class="adjustment-id">#{{ $adjustment->id }}</div>
                                            </td>
                                            <td>
                                                <div class="adjustment-product">{{ $adjustment->product->name }}</div>
                                            </td>
                                            <td>
                                                <div class="adjustment-type">
                                                    <span
                                                        class="badge badge-{{ $adjustment->type === 'increase' ? 'success' : 'danger' }}">
                                                        {{ ucfirst($adjustment->type) }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="adjustment-quantity">{{ $adjustment->quantity }}</div>
                                            </td>
                                            <td>
                                                <div class="adjustment-reason">
                                                    @if(!empty(trim($adjustment->reason)))
                                                        {{ Str::limit($adjustment->reason, 80) }}
                                                    @else
                                                        <span class="no-reason">No reason provided</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="adjustment-user">{{ $adjustment->user->getFullname() }}</div>
                                            </td>
                                            <td>
                                                <div class="adjustment-date">{{ $adjustment->adjusted_at->format('M d, Y H:i') }}
                                                </div>
                                            </td>
                                            <td>
                                                @canany(['adjustments.edit', 'adjustments.delete'])
                                                    <div class="action-buttons">
                                                        @can('adjustments.edit')
                                                            <a href="{{ route('adjustments.edit', $adjustment) }}"
                                                                class="btn btn-info btn-sm" title="Edit Adjustment">
                                                                <i class="fas fa-edit"></i>
                                                                <span class="d-none d-md-inline">Edit</span>
                                                            </a>
                                                        @endcan
                                                        @can('adjustments.delete')
                                                            <button class="btn btn-danger btn-sm delete-adjustment"
                                                                data-url="{{ route('adjustments.destroy', $adjustment) }}"
                                                                data-product="{{ $adjustment->product->name }}"
                                                                data-type="{{ $adjustment->type }}"
                                                                data-quantity="{{ $adjustment->quantity }}" title="Delete Adjustment">
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
                                    <i class="fas fa-exchange-alt"></i>
                                </div>
                                <h3 class="empty-state-title">No Adjustments Found</h3>
                                <p class="empty-state-description">Get started by creating your first stock adjustment.</p>
                                @can('adjustments.create')
                                    <a href="{{ route('adjustments.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus-circle"></i> Create Adjustment
                                    </a>
                                @endcan
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Pagination Section -->
                @if($adjustments->count() > 0)
                    <div class="pagination-container">
                        <div class="pagination-info">
                            Showing {{ $adjustments->firstItem() }} to {{ $adjustments->lastItem() }} of
                            {{ $adjustments->total() }} adjustments
                        </div>
                        <nav>
                            {{ $adjustments->appends(request()->query())->links() }}
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
            const typeFilter = document.getElementById('typeFilter');
            const dateFrom = document.getElementById('dateFrom');
            const dateTo = document.getElementById('dateTo');
            const resetButton = document.getElementById('resetButton');

            // Function untuk update URL dengan filters
            function updateURL() {
                const params = new URLSearchParams();

                // Add search
                if (searchInput.value) params.append('search', searchInput.value);

                // Add type filter
                if (typeFilter.value) params.append('type', typeFilter.value);

                // Add date filters
                if (dateFrom.value) params.append('date_from', dateFrom.value);
                if (dateTo.value) params.append('date_to', dateTo.value);

                // Preserve sort parameters
                const currentUrl = new URL(window.location.href);
                const sortBy = currentUrl.searchParams.get('sort_by') || 'adjusted_at';
                const sortOrder = currentUrl.searchParams.get('sort_order') || 'desc';

                params.append('sort_by', sortBy);
                params.append('sort_order', sortOrder);

                // Redirect ke URL baru dengan filters
                window.location.href = `{{ route('adjustments.index') }}?${params.toString()}`;
            }

            // Event listeners untuk search dengan debounce
            let searchTimeout;
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(updateURL, 500); // Debounce 500ms
            });

            // Event listeners untuk filters
            typeFilter.addEventListener('change', updateURL);
            dateFrom.addEventListener('change', updateURL);
            dateTo.addEventListener('change', updateURL);

            // Add visual indicators untuk current sort
            const currentSortBy = '{{ request("sort_by", "adjusted_at") }}';
            const currentSortOrder = '{{ request("sort_order", "desc") }}';

            document.querySelectorAll('.sort-link').forEach(link => {
                const url = new URL(link.href);
                const sortBy = url.searchParams.get('sort_by');

                if (sortBy === currentSortBy) {
                    const icon = link.querySelector('i');
                    if (icon) {
                        // Update icon berdasarkan sort order
                        icon.className = currentSortOrder === 'asc' ? 'fas fa-sort-up sort-icon' : 'fas fa-sort-down sort-icon';
                        link.classList.add('current-sort');
                        // Tambahkan class ke parent th juga
                        link.closest('th').classList.add('current-sort');
                    }
                }
            });

            // Delete adjustment functionality
            document.querySelectorAll('.delete-adjustment').forEach(button => {
                button.addEventListener('click', function () {
                    const url = this.dataset.url;
                    const productName = this.dataset.product;
                    const type = this.dataset.type;
                    const quantity = this.dataset.quantity;

                    Swal.fire({
                        title: 'Are you sure?',
                        text: `You are about to delete ${type} adjustment of ${quantity} units for ${productName}. This action cannot be undone!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e53e3e',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Create form and submit
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = url;

                            const csrfToken = document.createElement('input');
                            csrfToken.type = 'hidden';
                            csrfToken.name = '_token';
                            csrfToken.value = '{{ csrf_token() }}';

                            const methodField = document.createElement('input');
                            methodField.type = 'hidden';
                            methodField.name = '_method';
                            methodField.value = 'DELETE';

                            form.appendChild(csrfToken);
                            form.appendChild(methodField);
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                });
            });

            // Reset button functionality
            resetButton.addEventListener('click', function (e) {
                e.preventDefault();
                window.location.href = '{{ route('adjustments.index') }}';
            });

            // Set max date untuk dateTo berdasarkan dateFrom
            dateFrom.addEventListener('change', function () {
                dateTo.min = this.value;
            });

            // Set min date untuk dateFrom berdasarkan dateTo
            dateTo.addEventListener('change', function () {
                dateFrom.max = this.value;
            });
        });
    </script>
@endsection