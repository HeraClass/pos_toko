@extends('layouts.admin')

@section('title', __('order.Orders_List'))
@section('content-header', __('order.Orders_List'))
@section('content-actions')
    <x-export-button route="orders" :filters="request()->all()" title="Export Orders" filename="laporan_orders" />
    @can('orders.create')
        <a href="{{route('cart.index')}}" class="btn btn-primary">
            <i class="fas fa-shopping-cart"></i> {{ __('cart.title') }}
        </a>
    @endcan
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
    <style>
        .orders-list-container {
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

        .orders-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
            min-width: 1000px;
        }

        .orders-table thead {
            background-color: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .orders-table th {
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

        .orders-table td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            color: #4a5568;
            font-size: 0.9rem;
            white-space: nowrap;
        }

        .orders-table tbody tr {
            transition: all 0.3s ease;
            background-color: white;
        }

        .orders-table tbody tr:hover {
            background-color: #f7fafc;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        .orders-table tbody tr:last-child td {
            border-bottom: none;
        }

        .customer-name {
            font-weight: 600;
            color: #2d3748;
            min-width: 120px;
        }

        .amount-cell {
            font-weight: 600;
            color: #2d3748;
            text-align: right;
        }

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

        .badge-secondary {
            background-color: #e2e8f0;
            color: #4a5568;
        }

        .badge-primary {
            background-color: #ebf8ff;
            color: #3182ce;
        }

        .to-pay-cell {
            font-weight: 700;
            text-align: right;
        }

        .to-pay-positive {
            color: #e53e3e;
        }

        .to-pay-zero {
            color: #38a169;
        }

        .to-pay-negative {
            color: #3182ce;
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

        .btn-success {
            background-color: #48bb78;
            color: white;
        }

        .btn-success:hover {
            background-color: #38a169;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(72, 187, 120, 0.3);
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

        .table-footer {
            background-color: #f7fafc;
            border-top: 2px solid #e2e8f0;
        }

        .table-footer td {
            border-bottom: none !important;
            padding: 1rem 1.5rem;
            font-weight: 600;
        }

        .text-total {
            font-weight: 700;
            color: #2d3748;
        }

        /* Delete Confirmation Modal Styles */
        .delete-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1050;
            animation: fadeIn 0.3s ease;
        }

        .delete-modal {
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 500px;
            animation: slideUp 0.3s ease;
            overflow: hidden;
        }

        .delete-modal-header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #e2e8f0;
            background-color: #f8fafc;
        }

        .delete-modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #2d3748;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .delete-modal-body {
            padding: 2rem;
            text-align: center;
        }

        .delete-modal-icon {
            font-size: 3rem;
            color: #e53e3e;
            margin-bottom: 1rem;
        }

        .delete-modal-text {
            color: #4a5568;
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .delete-modal-order-id {
            font-weight: 600;
            color: #2d3748;
            background-color: #f7fafc;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            display: inline-block;
            margin: 0.5rem 0;
        }

        .delete-modal-warning {
            color: #e53e3e;
            font-size: 0.875rem;
            font-weight: 500;
            margin-top: 1rem;
        }

        .delete-modal-footer {
            padding: 1.5rem 2rem;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            background-color: #f8fafc;
        }

        .delete-modal-actions {
            display: flex;
            gap: 0.75rem;
            justify-content: flex-end;
            width: 100%;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {

            .orders-table th,
            .orders-table td {
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

            .delete-modal {
                width: 95%;
                margin: 1rem;
            }

            .delete-modal-footer {
                flex-direction: column;
            }

            .delete-modal-actions {
                flex-direction: column;
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

        .orders-table tbody tr {
            animation: fadeInUp 0.5s ease;
        }

        .orders-table tbody tr:nth-child(even) {
            animation-delay: 0.1s;
        }

        .orders-table tbody tr:nth-child(odd) {
            animation-delay: 0.2s;
        }

        /* Modal Styles */
        .modal-content {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            background-color: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 1.5rem;
        }

        .modal-title {
            font-weight: 600;
            color: #2d3748;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            border-top: 1px solid #e2e8f0;
            padding: 1.5rem;
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
        }
    </style>
@endsection

@section('content')
    <div class="orders-list-container">
        <div class="card">
            <div class="card-body">
                <!-- Search and Filter Section -->
                <div class="search-filter-container">
                    <!-- Hidden fields untuk preserve sort parameters -->
                    <input type="hidden" name="sort_by" value="{{ request('sort_by', 'created_at') }}">
                    <input type="hidden" name="sort_order" value="{{ request('sort_order', 'desc') }}">

                    <div class="search-box">
                        <label class="date-label">Search</label>
                        <div class="search-input-wrapper">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" class="search-input" placeholder="Search by order ID or customer name..."
                                id="searchInput" value="{{ request('search') }}">
                        </div>
                    </div>

                    <!-- Date Range Filter -->
                    <div class="date-filter-container">
                        <div class="date-input-group">
                            <label class="date-label">From Date</label>
                            <input type="date" class="date-input" id="startDate" value="{{ request('start_date') }}">
                        </div>
                        <div class="date-input-group">
                            <label class="date-label">To Date</label>
                            <input type="date" class="date-input" id="endDate" value="{{ request('end_date') }}">
                        </div>
                    </div>

                    <!-- Customer Type Filter -->
                    <div class="filter-group">
                        <label class="date-label">Customer Type</label>
                        <select class="filter-select" id="customerType">
                            <option value="">All Customers</option>
                            <option value="walk_in" {{ request('customer_type') == 'walk_in' ? 'selected' : '' }}>
                                Walk-in Customer
                            </option>
                            <option value="registered" {{ request('customer_type') == 'registered' ? 'selected' : '' }}>
                                Registered Customer
                            </option>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div class="filter-group">
                        <label class="date-label">Status</label>
                        <select class="filter-select" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="not_paid" {{ request('status') == 'not_paid' ? 'selected' : '' }}>
                                Not Paid
                            </option>
                            <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>
                                Partial
                            </option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>
                                Paid
                            </option>
                            <option value="change" {{ request('status') == 'change' ? 'selected' : '' }}>
                                Change
                            </option>
                        </select>
                    </div>

                    <!-- Reset Button -->
                    <div class="filter-group">
                        <label class="date-label" style="visibility: hidden;">Reset</label>
                        <a href="{{ route('orders.index') }}" class="btn btn-secondary reset-button" id="resetButton">
                            <i class="fas fa-refresh"></i> Reset
                        </a>
                    </div>
                </div>

                <!-- Table Section dengan Scroll -->
                <div class="table-container">
                    <div class="table-scroll-wrapper">
                        @if($orders->count() > 0)
                            <table id="ordersTable" class="orders-table">
                                <thead>
                                    <tr>
                                        <th class="th-sortable">
                                            ID
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'id', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                                class="sort-link">
                                                <i class="fas fa-sort"></i>
                                            </a>
                                        </th>
                                        <th>Customer Name</th>
                                        <th>Customer Type</th>
                                        <th>Total</th>
                                        <th>Received Amount</th>
                                        <th>Status</th>
                                        <th>To Pay</th>
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
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td><strong>#{{$order->id}}</strong></td>
                                            <td>
                                                <div class="customer-name">{{$order->getCustomerName()}}</div>
                                            </td>
                                            <td>
                                                @if($order->customer_id === null)
                                                    <span class="status-badge badge-secondary">Walk-in Customer</span>
                                                @else
                                                    <span class="status-badge badge-primary">Registered Customer</span>
                                                @endif
                                            </td>
                                            <td class="amount-cell">
                                                {{ config('settings.currency_symbol') }} {{ number_format($order->total(), 2) }}
                                            </td>
                                            <td class="amount-cell">
                                                {{ config('settings.currency_symbol') }}
                                                {{ number_format($order->receivedAmount(), 2) }}
                                            </td>
                                            <td>
                                                @if($order->receivedAmount() == 0)
                                                    <span class="status-badge badge-not-paid">Not Paid</span>
                                                @elseif($order->receivedAmount() < $order->total())
                                                    <span class="status-badge badge-partial">Partial</span>
                                                @elseif($order->receivedAmount() == $order->total())
                                                    <span class="status-badge badge-paid">Paid</span>
                                                @elseif($order->receivedAmount() > $order->total())
                                                    <span class="status-badge badge-change">Change</span>
                                                @endif
                                            </td>
                                            <td
                                                class="to-pay-cell {{ $order->total() - $order->receivedAmount() > 0 ? 'to-pay-positive' : ($order->total() - $order->receivedAmount() == 0 ? 'to-pay-zero' : 'to-pay-negative') }}">
                                                {{config('settings.currency_symbol')}}
                                                {{number_format($order->total() - $order->receivedAmount(), 2)}}
                                            </td>
                                            <td>
                                                <div class="created-at">{{$order->created_at->format('M d, Y H:i')}}</div>
                                            </td>
                                            <td>
                                                @canany(['orders.view', 'orders.edit', 'orders.delete'])
                                                    <div class="action-buttons">
                                                        @can('orders.view')
                                                            <button class="btn btn-info btn-sm btnShowInvoice" data-toggle="modal"
                                                                data-target="#modalInvoice" data-order-id="{{ $order->id }}"
                                                                title="View Invoice">
                                                                <i class="fas fa-eye"></i>
                                                                <span class="d-none d-md-inline">View</span>
                                                            </button>
                                                        @endcan

                                                        @can('orders.edit')
                                                            @if($order->total() > $order->receivedAmount())
                                                                <button class="btn btn-success btn-sm btnPartialPayment" data-toggle="modal"
                                                                    data-target="#partialPaymentModal" data-order-id="{{ $order->id }}"
                                                                    data-remaining-amount="{{ $order->total() - $order->receivedAmount() }}"
                                                                    title="Pay Partial">
                                                                    <i class="fas fa-money-bill-wave"></i>
                                                                    <span class="d-none d-md-inline">Pay</span>
                                                                </button>
                                                            @endif
                                                        @endcan

                                                        @can('orders.delete')
                                                            <button class="btn btn-danger btn-sm btnDeleteOrder"
                                                                data-order-id="{{ $order->id }}" data-order-number="#{{ $order->id }}"
                                                                data-customer-name="{{ $order->getCustomerName() }}" title="Delete Order">
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
                                <tfoot class="table-footer">
                                    <tr>
                                        <td colspan="3"><strong>Total Summary</strong></td>
                                        <td class="text-total">{{ config('settings.currency_symbol') }}
                                            {{ number_format($total, 2) }}
                                        </td>
                                        <td class="text-total">{{ config('settings.currency_symbol') }}
                                            {{ number_format($receivedAmount, 2) }}
                                        </td>
                                        <td colspan="4"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        @else
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-receipt"></i>
                                </div>
                                <h3 class="empty-state-title">No Orders Found</h3>
                                <p class="empty-state-description">Get started by creating your first order.</p>
                                @can('orders.create')
                                    <a href="{{route('cart.index')}}" class="btn btn-primary">
                                        <i class="fas fa-shopping-cart"></i> Create First Order
                                    </a>
                                @endcan
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Pagination Section -->
                @if($orders->count() > 0)
                    <div class="pagination-container">
                        <div class="pagination-info">
                            Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} orders
                        </div>
                        <nav>
                            {{ $orders->appends(request()->query())->links() }}
                        </nav>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Invoice Modal -->
    <div class="modal fade" id="modalInvoice" tabindex="-1" role="dialog" aria-labelledby="modalInvoiceLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalInvoiceLabel">
                        <i class="fas fa-file-invoice"></i> Invoice Details
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="invoiceModalBody">
                    <!-- Invoice content akan di-load di sini via AJAX -->
                    <div class="text-center py-5">
                        <i class="fas fa-spinner fa-spin fa-3x text-primary"></i>
                        <p class="mt-3">Loading invoice...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Close
                    </button>
                    <button type="button" class="btn btn-primary" id="btnPrintInvoice">
                        <i class="fas fa-print"></i> Print Invoice
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Partial Payment Modal -->
    <div class="modal fade" id="partialPaymentModal" tabindex="-1" role="dialog" aria-labelledby="partialPaymentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="partialPaymentModalLabel">Partial Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="partialPaymentForm" action="{{ route('orders.partial-payment') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="order_id" id="modalOrderId">
                        <div class="form-group">
                            <label for="remainingAmountText">Remaining Amount</label>
                            <p id="remainingAmountText" class="form-control-plaintext font-weight-bold"></p>
                        </div>
                        <div class="form-group">
                            <label for="partialAmount">Payment Amount</label>
                            <input type="number" step="0.01" class="form-control" id="partialAmount" name="amount" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Process Payment</button>
                    </div>
                </form>
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
            const startDate = document.getElementById('startDate');
            const endDate = document.getElementById('endDate');
            const customerType = document.getElementById('customerType');
            const statusFilter = document.getElementById('statusFilter');
            const resetButton = document.getElementById('resetButton');

            // Function untuk update URL dengan filters
            function updateURL() {
                const params = new URLSearchParams();

                // Add search
                if (searchInput.value) params.append('search', searchInput.value);

                // Add date filters
                if (startDate.value) params.append('start_date', startDate.value);
                if (endDate.value) params.append('end_date', endDate.value);

                // Add customer type filter
                if (customerType.value) params.append('customer_type', customerType.value);

                // Add status filter
                if (statusFilter.value) params.append('status', statusFilter.value);

                // Preserve sort parameters
                const currentUrl = new URL(window.location.href);
                const sortBy = currentUrl.searchParams.get('sort_by') || 'created_at';
                const sortOrder = currentUrl.searchParams.get('sort_order') || 'desc';

                params.append('sort_by', sortBy);
                params.append('sort_order', sortOrder);

                // Redirect ke URL baru dengan filters
                window.location.href = `{{ route('orders.index') }}?${params.toString()}`;
            }

            // Event listeners untuk filters dengan debounce
            let searchTimeout;
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(updateURL, 500);
            });

            startDate.addEventListener('change', updateURL);
            endDate.addEventListener('change', updateURL);
            customerType.addEventListener('change', updateURL);
            statusFilter.addEventListener('change', updateURL);

            // Delete Order Functionality dengan SweetAlert2
            document.querySelectorAll('.btnDeleteOrder').forEach(button => {
                button.addEventListener('click', function () {
                    const orderId = this.dataset.orderId;
                    const orderNumber = this.dataset.orderNumber;
                    const customerName = this.dataset.customerName;

                    Swal.fire({
                        title: 'Delete Order?',
                        html: `
                            <div style="text-align: center;">
                                <p style="margin-bottom: 10px;">You are about to delete the following order:</p>
                                <div style="background: #f8f9fa; padding: 12px; border-radius: 6px; margin: 10px 0;">
                                    <strong>Order ID:</strong> ${orderNumber}<br>
                                    <strong>Customer:</strong> ${customerName}
                                </div>
                                <p style="margin-top: 10px;">
                                    This action cannot be undone!
                                </p>
                            </div>
                        `,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e53e3e',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true,
                        customClass: {
                            popup: 'sweetalert-custom',
                            title: 'sweetalert-title',
                            htmlContainer: 'sweetalert-content'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Create form untuk submit delete
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = `/admin/orders/${orderId}`;
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

            // Add visual indicators untuk current sort
            const currentSortBy = '{{ request("sort_by", "created_at") }}';
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
                        // Tambahkan class ke parent th juga
                        link.closest('th').classList.add('current-sort');
                    }
                }
            });

            // Validate date range
            startDate.addEventListener('change', function () {
                if (endDate.value && startDate.value > endDate.value) {
                    alert('From date cannot be after To date');
                    endDate.value = '';
                }
            });

            endDate.addEventListener('change', function () {
                if (startDate.value && endDate.value < startDate.value) {
                    alert('To date cannot be before From date');
                    endDate.value = '';
                }
            });

            // Reset button functionality
            resetButton.addEventListener('click', function (e) {
                e.preventDefault();
                window.location.href = '{{ route('orders.index') }}';
            });

            // Partial Payment Modal
            $(document).on('click', '.btnPartialPayment', function (event) {
                var button = $(event.currentTarget);
                var orderId = button.data('order-id');
                var remainingAmount = button.data('remaining-amount');

                var modal = $('#partialPaymentModal');
                modal.find('#modalOrderId').val(orderId);
                modal.find('#partialAmount').val(remainingAmount);
                modal.find('#partialAmount').attr('max', remainingAmount);
                modal.find('#remainingAmountText').text('Remaining Amount: ' +
                    '{{ config("settings.currency_symbol") }}' + remainingAmount);
            });

            // Prevent entering amount more than remaining
            $('#partialAmount').on('input', function () {
                var max = parseFloat($(this).attr('max'));
                var value = parseFloat($(this).val());

                if (value > max) {
                    $(this).val(max);
                }
            });

            // Invoice Modal - Load via AJAX
            var currentOrderId = null;

            $(document).on('click', '.btnShowInvoice', function(event) {
                event.preventDefault();
                
                var orderId = $(this).data('order-id');
                currentOrderId = orderId;
                
                var modalBody = $('#invoiceModalBody');
                
                // Show loading state
                modalBody.html(`
                    <div class="text-center py-5">
                        <i class="fas fa-spinner fa-spin fa-3x text-primary"></i>
                        <p class="mt-3 text-muted">Loading invoice...</p>
                    </div>
                `);
                
                // Load invoice via AJAX
                $.ajax({
                    url: '/admin/orders/' + orderId + '/invoice-modal',
                    method: 'GET',
                    success: function(response) {
                        modalBody.html(response);
                        console.log('Invoice loaded successfully for Order #' + orderId);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading invoice:', error);
                        console.log('Status:', xhr.status);
                        console.log('Response:', xhr.responseText);
                        
                        modalBody.html(`
                            <div class="alert alert-danger m-4">
                                <h5 class="alert-heading">
                                    <i class="fas fa-exclamation-triangle"></i> 
                                    Failed to Load Invoice
                                </h5>
                                <p class="mb-0">
                                    Unable to load invoice data. Please try again.
                                </p>
                                <hr>
                                <small class="text-muted">Status: ${xhr.status}</small><br>
                                <small class="text-muted">Error: ${error}</small><br>
                                <small class="text-muted">Response: ${xhr.responseText}</small>
                            </div>
                        `);
                    }
                });
            });

            // Print Invoice Function
            $(document).on('click', '#btnPrintInvoice', function() {
                if (!currentOrderId) {
                    alert('No invoice selected');
                    return;
                }
                
                var printUrl = '/admin/orders/' + currentOrderId + '/invoice-print';
                window.open(printUrl, '_blank');
            });

            // Reset saat modal ditutup
            $('#modalInvoice').on('hidden.bs.modal', function() {
                currentOrderId = null;
                $('#invoiceModalBody').html(`
                    <div class="text-center py-5">
                        <i class="fas fa-spinner fa-spin fa-3x text-primary"></i>
                        <p class="mt-3">Loading invoice...</p>
                    </div>
                `);
            });
        });
    </script>
@endsection