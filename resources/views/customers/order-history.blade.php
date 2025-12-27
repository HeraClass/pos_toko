@extends('layouts.admin')

@section('title', 'Riwayat Pesanan' . $customer->full_name)
@section('content-header', 'Riwayat Pesanan')
@section('content-actions')
@can('customers.view')
    <a href="{{ route('customers.index', $customer) }}" class="btn btn-secondary">
       <i class="fas fa-arrow-left"></i> Kembali
    </a>
@endcan
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
    <style>
        .order-history-container {
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

        .stats-card {
            background: linear-gradient(135deg, #4361ee, #3a56d4);
            color: white;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.3);
        }

        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stats-label {
            font-size: 0.875rem;
            opacity: 0.9;
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

        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
            min-width: 1200px;
        }

        .order-table thead {
            background-color: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .order-table th {
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

        .order-table td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            color: #4a5568;
            font-size: 0.9rem;
            white-space: nowrap;
        }

        .order-table tbody tr {
            transition: all 0.3s ease;
            background-color: white;
        }

        .order-table tbody tr:hover {
            background-color: #f7fafc;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        .order-table tbody tr:last-child td {
            border-bottom: none;
        }

        .order-id {
            font-family: monospace;
            font-weight: 600;
            color: #4361ee;
            min-width: 80px;
        }

        .product-name {
            min-width: 150px;
            font-weight: 500;
        }

        .order-date {
            min-width: 120px;
            font-weight: 500;
        }

        .price,
        .subtotal,
        .amount {
            font-weight: 600;
            color: #2d3748;
            min-width: 100px;
            text-align: right;
        }

        .quantity {
            text-align: center;
            min-width: 80px;
        }

        .cashier-name {
            min-width: 120px;
        }

        .created-at {
            color: #6b7280;
            font-size: 0.875rem;
            min-width: 120px;
        }

        /* Status Badge Styles */
        .status-badge {
            padding: 0.5rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }

        .badge-not-paid {
            background-color: #fed7d7;
            color: #c53030;
        }

        .badge-partial {
            background-color: #feebcb;
            color: #b45309;
        }

        .badge-paid {
            background-color: #c6f6d5;
            color: #22543d;
        }

        .badge-change {
            background-color: #bee3f8;
            color: #2c5282;
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
            z-index: 2;
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
            .order-table th,
            .order-table td {
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

        .order-table tbody tr {
            animation: fadeInUp 0.5s ease;
        }

        .order-table tbody tr:nth-child(even) {
            animation-delay: 0.1s;
        }

        .order-table tbody tr:nth-child(odd) {
            animation-delay: 0.2s;
        }
    </style>
@endsection

@section('content')
    <div class="order-history-container">
        <!-- Statistik -->
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="stats-card">
                            <div class="stats-number">{{ $totalOrders }}</div>
                            <div class="stats-label">Total Pesanan</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="stats-card">
                            <div class="stats-number">{{ config('settings.currency_symbol') }}{{ number_format($totalSpent, 2) }}</div>
                            <div class="stats-label">Total Belanja</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="stats-card">
                            <div class="stats-number">{{ config('settings.currency_symbol') }}{{ number_format($averageOrder, 2) }}</div>
                            <div class="stats-label">Rata-rata Pesanan</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter dan Pencarian -->
        <div class="card">
            <div class="card-body">
                <div class="search-filter-container">
                    <!-- Hidden fields untuk preserve sort parameters -->
                    <input type="hidden" name="sort_by" value="{{ request('sort_by', 'created_at') }}">
                    <input type="hidden" name="sort_order" value="{{ request('sort_order', 'desc') }}">

                    <div class="search-box">
                        <label for="searchInput" class="date-label">Search</label>
                        <div class="search-input-wrapper">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" class="search-input" placeholder="Search by order ID or product name..."
                                id="searchInput" value="{{ request('search') }}">
                        </div>
                    </div>

                    <!-- Date Range Filter -->
                    <div class="date-filter-container">
                        <div class="date-input-group">
                            <label for="dateFrom" class="date-label">From Date</label>
                            <input type="date" class="date-input" id="dateFrom" value="{{ request('date_from') }}">
                        </div>
                        <div class="date-input-group">
                            <label for="dateTo" class="date-label">To Date</label>
                            <input type="date" class="date-input" id="dateTo" value="{{ request('date_to') }}">
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div class="filter-group">
                        <label for="statusFilter" class="date-label">Status</label>
                        <select class="filter-select" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="not_paid" {{ request('status') == 'not_paid' ? 'selected' : '' }}>Not Paid</option>
                            <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Partial</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="change" {{ request('status') == 'change' ? 'selected' : '' }}>Change</option>
                        </select>
                    </div>

                    <!-- Sort Order -->
                    <div class="filter-group">
                        <label for="sortOrder" class="date-label">Sort Order</label>
                        <select class="filter-select" id="sortOrder">
                            <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Newest First</option>
                            <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Oldest First</option>
                        </select>
                    </div>

                    <!-- Reset Button -->
                    <div class="filter-group">
                        <label class="date-label" style="visibility: hidden;">Reset</label>
                        <a href="{{ route('customers.order-history', $customer) }}" class="btn btn-secondary reset-button" id="resetButton">
                            <i class="fas fa-refresh"></i> Reset
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Riwayat Pesanan -->
        <div class="card">
            <div class="card-body">
                @if($orders->count() > 0)
                    <div class="table-container">
                        <div class="table-scroll-wrapper">
                            <table class="order-table">
                                <thead>
                                    <tr>
                                        <th class="th-sortable {{ request('sort_by') == 'id' ? 'current-sort' : '' }}">
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'id', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="sort-link">
                                                ID
                                                <i class="fas fa-sort sort-icon"></i>
                                            </a>
                                        </th>
                                        <th class="th-sortable {{ request('sort_by') == 'created_at' ? 'current-sort' : '' }}">
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'created_at', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="sort-link">
                                                Date
                                                <i class="fas fa-sort sort-icon"></i>
                                            </a>
                                        </th>
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th>Unit Price</th>
                                        <th>SubTotal</th>
                                        <th class="th-sortable {{ request('sort_by') == 'received_amount' ? 'current-sort' : '' }}">
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'received_amount', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="sort-link">
                                                Amount
                                                <i class="fas fa-sort sort-icon"></i>
                                            </a>
                                        </th>
                                        <th>Status</th>
                                        <th>Kasir</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        @foreach($order->items as $item)
                                            <tr>
                                                <td>
                                                    <div class="order-id">#{{ $order->id }}</div>
                                                </td>
                                                <td>
                                                    <div class="order-date">{{ $order->created_at->format('M d, Y H:i') }}</div>
                                                </td>
                                                <td>
                                                    <div class="product-name">{{ $item->product->name }}</div>
                                                </td>
                                                <td>
                                                    <div class="quantity">{{ $item->quantity }}</div>
                                                </td>
                                                <td>
                                                    <div class="price">{{ config('settings.currency_symbol') }}{{ number_format($item->unit_price, 2) }}</div>
                                                </td>
                                                <td>
                                                    <div class="subtotal">{{ config('settings.currency_symbol') }}{{ number_format($item->subtotal, 2) }}</div>
                                                </td>
                                                <td>
                                                    @php
                                                        $receivedAmount = $order->receivedAmount();
                                                    @endphp
                                                    <div class="amount">{{ config('settings.currency_symbol') }}{{ number_format($receivedAmount, 2) }}</div>
                                                </td>
                                                <td>
                                                    @php
                                                        $receivedAmount = $order->receivedAmount();
                                                        $total = $order->total();
                                                    @endphp
                                                    @if($receivedAmount == 0)
                                                        <span class="status-badge badge-not-paid">Not Paid</span>
                                                    @elseif($receivedAmount < $total)
                                                        <span class="status-badge badge-partial">Partial</span>
                                                    @elseif($receivedAmount == $total)
                                                        <span class="status-badge badge-paid">Paid</span>
                                                    @elseif($receivedAmount > $total)
                                                        <span class="status-badge badge-change">Change</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="cashier-name">{{ $order->user->getFullname() ?? 'N/A' }}</div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination Section -->
                    <div class="pagination-container">
                        <div class="pagination-info">
                            Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} orders
                        </div>
                        <nav>
                            {{ $orders->appends(request()->query())->links() }}
                        </nav>
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <h3 class="empty-state-title">No Orders Found</h3>
                        <p class="empty-state-description">This customer hasn't made any orders yet.</p>
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
            const dateFrom = document.getElementById('dateFrom');
            const dateTo = document.getElementById('dateTo');
            const statusFilter = document.getElementById('statusFilter');
            const sortOrder = document.getElementById('sortOrder');
            const resetButton = document.getElementById('resetButton');

            // Function untuk update URL dengan filters
            function updateURL() {
                const params = new URLSearchParams();
                
                // Add search
                if (searchInput.value) params.append('search', searchInput.value);
                
                // Add date filters
                if (dateFrom.value) params.append('date_from', dateFrom.value);
                if (dateTo.value) params.append('date_to', dateTo.value);
                
                // Add status filter
                if (statusFilter.value) params.append('status', statusFilter.value);
                
                // Add sort order
                if (sortOrder.value) params.append('sort_order', sortOrder.value);
                
                // Preserve sort parameter
                const urlParams = new URLSearchParams(window.location.search);
                const currentSortBy = urlParams.get('sort_by') || 'created_at';
                params.append('sort_by', currentSortBy);

                // Redirect ke URL baru dengan filters
                window.location.href = `{{ route('customers.order-history', $customer) }}?${params.toString()}`;
            }

            // Event listeners untuk filters dengan debounce
            let searchTimeout;
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(updateURL, 800); // Debounce 800ms
            });

            dateFrom.addEventListener('change', updateURL);
            dateTo.addEventListener('change', updateURL);
            statusFilter.addEventListener('change', updateURL);
            sortOrder.addEventListener('change', updateURL);

            // Add visual indicators untuk current sort
            const urlParams = new URLSearchParams(window.location.search);
            const currentSortBy = urlParams.get('sort_by') || 'created_at';
            const currentSortOrder = urlParams.get('sort_order') || 'desc';
            
            document.querySelectorAll('.sort-link').forEach(link => {
                const url = new URL(link.href);
                const sortBy = url.searchParams.get('sort_by');
                
                if (sortBy === currentSortBy) {
                    const icon = link.querySelector('i');
                    if (icon) {
                        // Update icon berdasarkan sort order
                        icon.className = currentSortOrder === 'asc' ? 'fas fa-sort-up sort-icon' : 'fas fa-sort-down sort-icon';
                        link.classList.add('current-sort');
                        
                        // Update parent th class untuk styling
                        const th = link.closest('th');
                        if (th) {
                            th.classList.add('current-sort');
                        }
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

            // Reset button functionality
            resetButton.addEventListener('click', function (e) {
                e.preventDefault();
                window.location.href = '{{ route('customers.order-history', $customer) }}';
            });

            // Enter key untuk search
            searchInput.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    updateURL();
                }
            });
        });
    </script>
@endsection