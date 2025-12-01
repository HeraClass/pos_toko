@extends('layouts.admin')

@section('title', __('purchase.Purchase_List'))
@section('content-header', __('purchase.Purchase_List'))
@section('content-actions')
    <x-export-button route="purchases" :filters="request()->all()" title="Export Purchases" filename="laporan_purchases" />

    @can('purchases.create')
        <a href="{{route('purchases.create')}}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> {{ __('purchase.Create_Purchase') }}
        </a>
    @endcan
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
    <style>
        .purchase-list-container {
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

        .purchase-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
            min-width: 1200px;
        }

        .purchase-table thead {
            background-color: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .purchase-table th {
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

        .purchase-table td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            color: #4a5568;
            font-size: 0.9rem;
            white-space: nowrap;
        }

        .purchase-table tbody tr {
            transition: all 0.3s ease;
            background-color: white;
        }

        .purchase-table tbody tr:hover {
            background-color: #f7fafc;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        .purchase-table tbody tr:last-child td {
            border-bottom: none;
        }

        .invoice-number {
            font-family: monospace;
            font-weight: 600;
            color: #4361ee;
            min-width: 120px;
        }

        .supplier-name {
            min-width: 120px;
        }

        .product-name {
            min-width: 150px;
            font-weight: 500;
        }

        .purchase-date,
        .expired-date {
            min-width: 100px;
            font-weight: 500;
        }

        .price,
        .quantity,
        .total-amount {
            font-weight: 600;
            color: #2d3748;
            min-width: 100px;
            text-align: right;
        }

        .quantity {
            text-align: center;
        }

        .created-at {
            color: #6b7280;
            font-size: 0.875rem;
            min-width: 120px;
        }

        .expired-soon {
            color: #e53e3e;
            font-weight: 600;
        }

        .expired-normal {
            color: #22543d;
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

        .date-filter-container {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .date-input-group {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .date-label {
            font-size: 0.75rem;
            color: #6b7280;
            font-weight: 500;
        }

        .date-input {
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.875rem;
            background-color: white;
            min-width: 140px;
        }

        .date-input:focus {
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
        }

        .th-sortable:hover {
            background-color: #f1f5f9;
        }

        .sort-icon {
            font-size: 0.8em;
            margin-left: 4px;
        }

        .sort-asc .sort-icon {
            color: #4361ee;
        }

        .sort-desc .sort-icon {
            color: #4361ee;
        }

        /* Current sort indicator */
        .current-sort {
            color: #4361ee;
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
            .purchase-table th,
            .purchase-table td {
                padding: 0.75rem 1rem;
            }

            .search-filter-container {
                flex-direction: column;
                align-items: stretch;
            }

            .search-box {
                min-width: 100%;
            }

            .date-filter-container {
                flex-direction: column;
                width: 100%;
            }

            .date-input-group {
                width: 100%;
            }

            .date-input {
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

        .purchase-table tbody tr {
            animation: fadeInUp 0.5s ease;
        }

        .purchase-table tbody tr:nth-child(even) {
            animation-delay: 0.1s;
        }

        .purchase-table tbody tr:nth-child(odd) {
            animation-delay: 0.2s;
        }
    </style>
@endsection

@section('content')
    <div class="purchase-list-container">
        <div class="card">
            <div class="card-body">
                <!-- Search and Filter Section -->
                <div class="search-filter-container">
                    <!-- Hidden fields untuk preserve sort parameters -->
                    <input type="hidden" name="sort_by" value="{{ request('sort_by', 'purchase_date') }}">
                    <input type="hidden" name="sort_order" value="{{ request('sort_order', 'desc') }}">

                    <div class="search-box">
                        <label class="date-label">Search</label>
                        <div class="search-input-wrapper">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" class="search-input" placeholder="Search by invoice, supplier, or product..."
                                id="searchInput" value="{{ request('search') }}">
                        </div>
                    </div>

                    <!-- Date Range Filter -->
                    <div class="date-filter-container">
                        <div class="date-input-group">
                            <label class="date-label">From Date</label>
                            <input type="date" class="date-input" id="dateFrom" value="{{ request('date_from') }}">
                        </div>
                        <div class="date-input-group">
                            <label class="date-label">To Date</label>
                            <input type="date" class="date-input" id="dateTo" value="{{ request('date_to') }}">
                        </div>
                    </div>

                    <!-- Supplier Filter -->
                    <div class="filter-group">
                        <label class="date-label">Supplier</label>
                        <select class="filter-select" id="supplierFilter">
                            <option value="">All Suppliers</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->first_name }} {{ $supplier->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Expiry Filter -->
                    <div class="filter-group">
                        <label class="date-label">Expiry Status</label>
                        <select class="filter-select" id="expiryFilter">
                            <option value="">All Expiry</option>
                            <option value="expired" {{ request('expiry_filter') == 'expired' ? 'selected' : '' }}>Expired
                            </option>
                            <option value="soon" {{ request('expiry_filter') == 'soon' ? 'selected' : '' }}>Expiring Soon
                            </option>
                            <option value="valid" {{ request('expiry_filter') == 'valid' ? 'selected' : '' }}>Valid</option>
                        </select>
                    </div>

                    <!-- Reset Button -->
                    <div class="filter-group">
                        <label class="date-label" style="visibility: hidden;">Reset</label>
                        <a href="{{ route('purchases.index') }}" class="btn btn-secondary reset-button" id="resetButton">
                            <i class="fas fa-refresh"></i> Reset
                        </a>
                    </div>
                </div>

                <!-- Table Section dengan Scroll -->
                <div class="table-container">
                    <div class="table-scroll-wrapper">
                        @if($purchases->count() > 0)
                            <table class="purchase-table">
                                <thead>
                                    <tr>
                                        <th>
                                            ID
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'id', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="sort-link">
                                                <i class="fas fa-sort"></i>
                                            </a>
                                        </th>
                                        <th>
                                            Invoice Number
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'invoice_number', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="sort-link">
                                                <i class="fas fa-sort"></i>
                                            </a>
                                        </th>
                                        <th>Supplier</th>
                                        <th>Product</th>
                                        <th>
                                            Purchase Date
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'purchase_date', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="sort-link">
                                                <i class="fas fa-sort"></i>
                                            </a>
                                        </th>
                                        <th>
                                            Expired Date
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'expired_date', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="sort-link">
                                                <i class="fas fa-sort"></i>
                                            </a>
                                        </th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>
                                            Total Amount
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'total_amount', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="sort-link">
                                                <i class="fas fa-sort"></i>
                                            </a>
                                        </th>
                                        <th>
                                            Created At
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'created_at', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="sort-link">
                                                <i class="fas fa-sort"></i>
                                            </a>
                                        </th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($purchases as $purchase)
                                        @foreach($purchase->items as $item)
                                            <tr>
                                                <td><strong>#{{ $purchase->id }}</strong></td>
                                                <td>
                                                    <div class="invoice-number">{{ $purchase->invoice_number }}</div>
                                                </td>
                                                <td>
                                                    <div class="supplier-name">
                                                        @if($purchase->supplier)
                                                            {{ $purchase->supplier->first_name }} {{ $purchase->supplier->last_name }}
                                                        @else
                                                            <span class="text-muted">No Supplier</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="product-name">
                                                        {{ $item->product->name }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="purchase-date">
                                                        {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('M d, Y') }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div
                                                        class="expired-date {{ $item->expired_date && \Carbon\Carbon::parse($item->expired_date)->isPast() ? 'expired-soon' : 'expired-normal' }}">
                                                        @if($item->expired_date)
                                                            {{ \Carbon\Carbon::parse($item->expired_date)->format('M d, Y') }}
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="price">
                                                        {{ config('settings.currency_symbol') }}{{ number_format($item->price, 2) }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="quantity">
                                                        {{ $item->quantity }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="total-amount">
                                                        {{ config('settings.currency_symbol') }}{{ number_format($item->price * $item->quantity, 2) }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="created-at">
                                                        {{ $purchase->created_at->format('M d, Y') }}
                                                    </div>
                                                </td>
                                                <td>
                                                    @canany(['purchases.edit', 'purchases.delete'])
                                                        <div class="action-buttons">
                                                            @can('purchases.edit')
                                                                <a href="{{ route('purchases.edit', $purchase) }}" class="btn btn-info btn-sm"
                                                                    title="Edit Purchase">
                                                                    <i class="fas fa-edit"></i>
                                                                    <span class="d-none d-md-inline">Edit</span>
                                                                </a>
                                                            @endcan
                                                            @can('purchases.delete')
                                                                <button class="btn btn-danger btn-sm delete-purchase"
                                                                    data-url="{{ route('purchases.destroy', $purchase) }}"
                                                                    data-invoice="{{ $purchase->invoice_number }}"
                                                                    title="Delete Purchase">
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
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <h3 class="empty-state-title">No Purchases Found</h3>
                                <p class="empty-state-description">Get started by creating your first purchase order.</p>
                                @can('purchases.create')
                                    <a href="{{ route('purchases.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus-circle"></i> Create Purchase
                                    </a>
                                @endcan
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Pagination Section -->
                @if($purchases->count() > 0)
                    <div class="pagination-container">
                        <div class="pagination-info">
                            Showing {{ $purchases->firstItem() }} to {{ $purchases->lastItem() }} of {{ $purchases->total() }} purchases
                            @isset($totalItems)
                                ({{ $totalItems }} items)
                            @endisset
                        </div>
                        <nav>
                            {{ $purchases->appends(request()->query())->links() }}
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
            const dateFrom = document.getElementById('dateFrom');
            const dateTo = document.getElementById('dateTo');
            const supplierFilter = document.getElementById('supplierFilter');
            const expiryFilter = document.getElementById('expiryFilter');
            const resetButton = document.getElementById('resetButton');

            // Function untuk update URL dengan filters
            function updateURL() {
                const params = new URLSearchParams();
                
                // Add search
                if (searchInput.value) params.append('search', searchInput.value);
                
                // Add date filters
                if (dateFrom.value) params.append('date_from', dateFrom.value);
                if (dateTo.value) params.append('date_to', dateTo.value);
                
                // Add supplier filter
                if (supplierFilter.value) params.append('supplier_id', supplierFilter.value);
                
                // Add expiry filter
                if (expiryFilter.value) params.append('expiry_filter', expiryFilter.value);
                
                // Preserve sort parameters
                const currentUrl = new URL(window.location.href);
                const sortBy = currentUrl.searchParams.get('sort_by') || 'purchase_date';
                const sortOrder = currentUrl.searchParams.get('sort_order') || 'desc';
                
                params.append('sort_by', sortBy);
                params.append('sort_order', sortOrder);

                // Redirect ke URL baru dengan filters
                window.location.href = `{{ route('purchases.index') }}?${params.toString()}`;
            }

            // Event listeners untuk filters
            let searchTimeout;
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(updateURL, 500); // Debounce 500ms
            });

            dateFrom.addEventListener('change', updateURL);
            dateTo.addEventListener('change', updateURL);
            supplierFilter.addEventListener('change', updateURL);
            expiryFilter.addEventListener('change', updateURL);

            // Add visual indicators untuk current sort
            const currentSortBy = '{{ request("sort_by", "purchase_date") }}';
            const currentSortOrder = '{{ request("sort_order", "desc") }}';
            
            document.querySelectorAll('.sort-link').forEach(link => {
                const url = new URL(link.href);
                const sortBy = url.searchParams.get('sort_by');
                
                if (sortBy === currentSortBy) {
                    const icon = link.querySelector('i');
                    if (icon) {
                        // Update icon berdasarkan sort order
                        icon.className = currentSortOrder === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down';
                        link.classList.add('current-sort');
                    }
                }
            });

            // Validate date range
            dateFrom.addEventListener('change', function () {
                if (dateTo.value && dateFrom.value > dateTo.value) {
                    alert('From date cannot be after To date');
                    dateFrom.value = dateTo.value;
                }
            });

            dateTo.addEventListener('change', function () {
                if (dateFrom.value && dateTo.value < dateFrom.value) {
                    alert('To date cannot be before From date');
                    dateTo.value = dateFrom.value;
                }
            });

            // Delete purchase functionality dengan modal SweetAlert2
            document.querySelectorAll('.delete-purchase').forEach(button => {
                button.addEventListener('click', function () {
                    const url = this.dataset.url;
                    const invoiceNumber = this.dataset.invoice;

                    Swal.fire({
                        title: 'Are you sure?',
                        text: `You are about to delete purchase with invoice: ${invoiceNumber}. This action cannot be undone!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e53e3e',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true,
                        backdrop: true,
                        allowOutsideClick: false,
                        allowEscapeKey: true,
                        allowEnterKey: false
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
                window.location.href = '{{ route('purchases.index') }}';
            });
        });
    </script>
@endsection