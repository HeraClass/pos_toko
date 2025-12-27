@extends('layouts.admin')

@section('title', __('expense.Operational_Expenses'))
@section('content-header', __('expense.Operational_Expenses'))
@section('content-actions')
    <a href="{{ route('expenses.create') }}" class="btn btn-primary">
        <i class="fas fa-plus-circle"></i> {{ __('expense.Add_Expense') }}
    </a>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
    <style>
        .expense-list-container {
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

        .expense-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
            min-width: 800px;
        }

        .expense-table thead {
            background-color: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .expense-table th {
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

        .expense-table td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            color: #4a5568;
            font-size: 0.9rem;
            white-space: nowrap;
        }

        .expense-table tbody tr {
            transition: all 0.3s ease;
            background-color: white;
        }

        .expense-table tbody tr:hover {
            background-color: #f7fafc;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        .expense-table tbody tr:last-child td {
            border-bottom: none;
        }

        .expense-name {
            font-weight: 600;
            color: #2d3748;
            min-width: 150px;
        }

        .expense-notes {
            color: #6b7280;
            max-width: 300px;
            min-width: 150px;
            white-space: normal !important;
        }

        .no-notes {
            color: #9ca3af;
            font-style: italic;
        }

        .expense-date {
            color: #6b7280;
            font-size: 0.875rem;
            min-width: 120px;
        }

        .expense-amount {
            font-weight: 600;
            color: #2d3748;
            min-width: 120px;
        }

        .amount-positive {
            color: #e53e3e;
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

        /* Success Message */
        .alert-success {
            background-color: #d1fae5;
            border: 1px solid #a7f3d0;
            color: #065f46;
            padding: 1rem;
            border-radius: 8px;
            margin: 1rem 1.5rem;
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

            .expense-table th,
            .expense-table td {
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

        .expense-table tbody tr {
            animation: fadeInUp 0.5s ease;
        }

        .expense-table tbody tr:nth-child(even) {
            animation-delay: 0.1s;
        }

        .expense-table tbody tr:nth-child(odd) {
            animation-delay: 0.2s;
        }
    </style>
@endsection

@section('content')
    <div class="expense-list-container">
        <div class="card">
            <div class="card-body">
                <!-- Search and Filter Section -->
                <div class="search-filter-container">
                    <!-- Hidden fields untuk preserve sort parameters -->
                    <input type="hidden" name="sort_by" value="{{ request('sort_by', 'expense_date') }}">
                    <input type="hidden" name="sort_order" value="{{ request('sort_order', 'desc') }}">

                    <div class="search-box">
                        <label class="date-label">Search</label>
                        <div class="search-input-wrapper">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" class="search-input" placeholder="{{ __('expense.Search_placeholder') }}"
                                id="searchInput" value="{{ request('search') }}">
                        </div>
                    </div>

                    <!-- Reset Button -->
                    <div class="filter-group">
                        <label class="date-label" style="visibility: hidden;">Reset</label>
                        <a href="{{ route('expenses.index') }}" class="btn btn-secondary reset-button" id="resetButton">
                            <i class="fas fa-refresh"></i> {{ __('expense.Reset') }}
                        </a>
                    </div>
                </div>

                <!-- Table Section dengan Scroll -->
                <div class="table-container">
                    <div class="table-scroll-wrapper">
                        @if($expenses->count() > 0)
                            <table class="expense-table">
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
                                            Amount
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'amount', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                                class="sort-link">
                                                <i class="fas fa-sort"></i>
                                            </a>
                                        </th>
                                        <th>Notes</th>
                                        <th class="th-sortable">
                                            Date
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'expense_date', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                                class="sort-link">
                                                <i class="fas fa-sort"></i>
                                            </a>
                                        </th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($expenses as $expense)
                                        <tr>
                                            <td>
                                                <div class="expense-name">{{ $expense->name }}</div>
                                            </td>
                                            <td>
                                                <div class="expense-amount amount-positive">
                                                    Rp{{ number_format($expense->amount, 2, '.', ',') }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="expense-notes">
                                                    @if(!empty(trim($expense->notes)))
                                                        {{ Str::limit($expense->notes, 100) }}
                                                    @else
                                                        <span class="no-notes">-</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="expense-date">
                                                    {{ $expense->expense_date->format('M d, Y') }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-info btn-sm"
                                                        title="Edit Expense">
                                                        <i class="fas fa-edit"></i>
                                                        <span class="d-none d-md-inline">{{ __('expense.Edit') }}</span>
                                                    </a>
                                                    <button class="btn btn-danger btn-sm delete-expense"
                                                        data-url="{{ route('expenses.destroy', $expense) }}"
                                                        data-name="{{ $expense->name }}" title="Delete Expense">
                                                        <i class="fas fa-trash"></i>
                                                        <span class="d-none d-md-inline">{{ __('expense.Delete') }}</span>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-receipt"></i>
                                </div>
                                <h3 class="empty-state-title">{{ __('expense.No_Expenses_Found') }}</h3>
                                <p class="empty-state-description">{{ __('expense.Get_started_by_adding_your_first_expense') }}
                                </p>
                                <a href="{{ route('expenses.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus-circle"></i> Add Expense
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Pagination Section -->
                @if($expenses->count() > 0)
                    <div class="pagination-container">
                        <div class="pagination-info">
                            {{ __('expense.Showing') }} {{ $expenses->firstItem() }} {{ __('expense.to') }}
                            {{ $expenses->lastItem() }} {{ __('expense.of') }}
                            {{ $expenses->total() }} {{ __('expense.expenses') }}
                        </div>
                        <nav>
                            {{ $expenses->appends(request()->query())->links() }}
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
                const sortBy = currentUrl.searchParams.get('sort_by') || 'expense_date';
                const sortOrder = currentUrl.searchParams.get('sort_order') || 'desc';

                params.append('sort_by', sortBy);
                params.append('sort_order', sortOrder);

                // Redirect ke URL baru dengan filters
                window.location.href = `{{ route('expenses.index') }}?${params.toString()}`;
            }

            // Event listeners untuk search dengan debounce
            let searchTimeout;
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(updateURL, 500); // Debounce 500ms
            });

            // Add visual indicators untuk current sort
            const currentSortBy = '{{ request("sort_by", "expense_date") }}';
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

            // Delete expense functionality
            document.querySelectorAll('.delete-expense').forEach(button => {
                button.addEventListener('click', function () {
                    const url = this.dataset.url;
                    const expenseName = this.dataset.name;

                    Swal.fire({
                        title: 'Are you sure?',
                        text: `You are about to delete expense: ${expenseName}. This action cannot be undone!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e53e3e',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true
                    })
                        .then((result) => {
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
                window.location.href = '{{ route('expenses.index') }}';
            });

            // Submit search on Enter key
            searchInput.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    updateURL();
                }
            });
        });
    </script>
@endsection