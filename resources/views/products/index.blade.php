@extends('layouts.admin')

@section('title', __('product.Product_List'))
@section('content-header', __('product.Product_List'))
@section('content-actions')
    <x-export-button route="products" :filters="request()->all()" title="Export Product" filename="laporan_products" />

    @can('products.create')
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> {{ __('product.Create_Product') }}
        </a>
    @endcan
    <button class="btn btn-success" id="printBarcodeBtn">
        <i class="fas fa-barcode"></i> {{ __('product.Print_Barcode') }}
    </button>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
    <style>
        .product-list-container {
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

        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
            min-width: 1200px;
        }

        .product-table thead {
            background-color: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .product-table th {
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

        .product-table td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            color: #4a5568;
            font-size: 0.9rem;
            white-space: nowrap;
        }

        .product-table tbody tr {
            transition: all 0.3s ease;
            background-color: white;
        }

        .product-table tbody tr:hover {
            background-color: #f7fafc;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        .product-table tbody tr:last-child td {
            border-bottom: none;
        }

        .product-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #e2e8f0;
            transition: transform 0.3s ease;
        }

        .product-table tr:hover .product-img {
            transform: scale(1.05);
        }

        .product-name {
            font-weight: 600;
            color: #2d3748;
            min-width: 150px;
        }

        .category-badge {
            padding: 0.375rem 0.75rem;
            background-color: #e2e8f0;
            color: #4a5568;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-block;
        }

        .no-category {
            color: #9ca3af;
            font-style: italic;
        }

        .status-badge {
            padding: 0.375rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }

        .status-active {
            background-color: #c6f6d5;
            color: #22543d;
        }

        .status-inactive {
            background-color: #fed7d7;
            color: #742a2a;
        }

        .price {
            font-weight: 600;
            color: #2d3748;
        }

        .quantity {
            font-weight: 500;
        }

        .low-stock {
            color: #e53e3e;
            font-weight: 600;
        }

        .created-at {
            color: #6b7280;
            font-size: 0.875rem;
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

        .btn-success {
            background-color: #48bb78;
            color: white;
        }

        .btn-success:hover {
            background-color: #38a169;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(72, 187, 120, 0.3);
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

        .date-label {
            font-size: 0.75rem;
            color: #6b7280;
            font-weight: 500;
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

            .product-table th,
            .product-table td {
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

        .product-table tbody tr {
            animation: fadeInUp 0.5s ease;
        }

        .product-table tbody tr:nth-child(even) {
            animation-delay: 0.1s;
        }

        .product-table tbody tr:nth-child(odd) {
            animation-delay: 0.2s;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            overflow-y: auto;
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 0;
            border-radius: 12px;
            width: 90%;
            max-width: 800px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            animation: modalFadeIn 0.3s;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #2d3748;
            margin: 0;
        }

        .close {
            font-size: 1.5rem;
            color: #a0aec0;
            cursor: pointer;
            transition: color 0.3s;
        }

        .close:hover {
            color: #4a5568;
        }

        .modal-body {
            padding: 1.5rem;
            max-height: 70vh;
            overflow-y: auto;
        }

        .modal-footer {
            padding: 1.5rem;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
        }

        .barcode-options {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .barcode-option {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .barcode-option label {
            font-weight: 500;
            color: #4a5568;
            font-size: 0.9rem;
        }

        .barcode-option select,
        .barcode-option input {
            padding: 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.9rem;
        }

        .barcode-preview {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            background: white;
            text-align: center;
        }

        .barcode-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .barcode-item {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
            background: white;
            break-inside: avoid;
        }

        .barcode-image {
            max-width: 100%;
            height: auto;
            margin-bottom: 0.5rem;
        }

        .barcode-text {
            font-size: 0.8rem;
            word-break: break-all;
            margin-top: 0.5rem;
        }

        .product-checkbox {
            margin-right: 0.5rem;
        }

        .select-all-container {
            margin-bottom: 1rem;
            padding: 0.5rem 0;
        }

        .text-muted {
            color: #a0aec0;
            text-align: center;
            padding: 2rem;
        }

        /* Print-specific styles */
        @media print {
            body * {
                visibility: hidden;
            }

            .barcode-print-section,
            .barcode-print-section * {
                visibility: visible;
            }

            .barcode-print-section {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .no-print {
                display: none !important;
            }

            .barcode-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }
    </style>
@endsection

@section('content')
    <div class="product-list-container">
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
                            <input type="text" class="search-input" placeholder="Search products by name or barcode..."
                                id="searchInput" value="{{ request('search') }}">
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div class="filter-group">
                        <label class="date-label">Status</label>
                        <select class="filter-select" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <!-- Category Filter -->
                    <div class="filter-group">
                        <label class="date-label">Category</label>
                        <select class="filter-select" id="categoryFilter">
                            <option value="">All Categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Reset Button -->
                    <div class="filter-group">
                        <label class="date-label" style="visibility: hidden;">Reset</label>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary reset-button" id="resetButton">
                            <i class="fas fa-refresh"></i> Reset
                        </a>
                    </div>
                </div>

                <!-- Table Section dengan Scroll -->
                <div class="table-container">
                    <div class="table-scroll-wrapper">
                        @if($products->count() > 0)
                            <table class="product-table">
                                <thead>
                                    <tr>
                                        <th style="width: 40px;">
                                            <input type="checkbox" id="selectAllCheckbox" class="product-checkbox">
                                        </th>
                                        <th class="th-sortable">
                                            ID
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'id', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                                class="sort-link">
                                                <i class="fas fa-sort"></i>
                                            </a>
                                        </th>
                                        <th>Image</th>
                                        <th class="th-sortable">
                                            Name
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'name', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                                class="sort-link">
                                                <i class="fas fa-sort"></i>
                                            </a>
                                        </th>
                                        <th>Category</th>
                                        <th class="th-sortable">
                                            Barcode
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'barcode', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                                class="sort-link">
                                                <i class="fas fa-sort"></i>
                                            </a>
                                        </th>
                                        <th class="th-sortable">
                                            Price
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'price', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
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
                                        <th>Status</th>
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
                                    @foreach ($products as $product)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="product-checkbox product-select"
                                                    data-id="{{ $product->id }}" data-barcode="{{ $product->barcode }}"
                                                    data-name="{{ $product->name }}">
                                            </td>
                                            <td><strong>#{{ $product->id }}</strong></td>
                                            <td>
                                                <img class="product-img" src="{{ Storage::url($product->image) }}"
                                                    alt="{{ $product->name }}"
                                                    onerror="this.src='https://via.placeholder.com/60x60?text=No+Image'">
                                            </td>
                                            <td>
                                                <div class="product-name">{{ $product->name }}</div>
                                            </td>
                                            <td>
                                                @if ($product->category)
                                                    <span class="category-badge">{{ $product->category->name }}</span>
                                                @else
                                                    <span class="no-category">No Category</span>
                                                @endif
                                            </td>
                                            <td>
                                                <code>{{ $product->barcode }}</code>
                                            </td>
                                            <td>
                                                <div class="price">
                                                    {{ config('settings.currency_symbol') }}{{ number_format($product->price, 2) }}
                                                </div>
                                            </td>
                                            <td>
                                                <div
                                                    class="quantity {{ $product->quantity <= config('settings.warning_quantity') ? 'low-stock' : '' }}">
                                                    {{ $product->quantity }}
                                                </div>
                                            </td>
                                            <td>
                                                <span
                                                    class="status-badge {{ $product->status ? 'status-active' : 'status-inactive' }}">
                                                    {{ $product->status ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="created-at">
                                                    {{ $product->created_at->format('M d, Y') }}
                                                </div>
                                            </td>
                                            <td>
                                                @canany(['products.view', 'products.edit', 'products.delete'])
                                                    <div class="action-buttons">
                                                        @can('products.edit')
                                                            <a href="{{ route('products.edit', $product) }}" class="btn btn-info btn-sm"
                                                                title="Edit Product">
                                                                <i class="fas fa-edit"></i>
                                                                <span class="d-none d-md-inline">Edit</span>
                                                            </a>
                                                        @endcan
                                                        @can('products.delete')
                                                            <button class="btn btn-danger btn-sm delete-product"
                                                                data-url="{{ route('products.destroy', $product) }}"
                                                                data-name="{{ $product->name }}" title="Delete Product">
                                                                <i class="fas fa-trash"></i>
                                                                <span class="d-none d-md-inline">Delete</span>
                                                            </button>
                                                        @endcan
                                                        @can('products.view')
                                                            <button class="btn btn-success btn-sm print-single-barcode"
                                                                data-barcode="{{ $product->barcode }}" data-name="{{ $product->name }}"
                                                                title="Print Barcode">
                                                                <i class="fas fa-barcode"></i>
                                                                <span class="d-none d-md-inline">Barcode</span>
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
                                    <i class="fas fa-box-open"></i>
                                </div>
                                <h3 class="empty-state-title">No Products Found</h3>
                                <p class="empty-state-description">Get started by creating your first product.</p>
                                @can('products.create')
                                    <a href="{{ route('products.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus-circle"></i> Create Product
                                    </a>
                                @endcan
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Pagination Section -->
                @if($products->count() > 0)
                    <div class="pagination-container">
                        <div class="pagination-info">
                            Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }}
                            products
                        </div>
                        <nav>
                            {{ $products->appends(request()->query())->links() }}
                        </nav>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Barcode Print Modal -->
    <div id="barcodeModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Print Barcode</h3>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <div class="barcode-options">
                    <div class="barcode-option">
                        <label for="barcodeType">Barcode Type</label>
                        <select id="barcodeType">
                            <option value="CODE128">CODE128</option>
                            <option value="CODE39">CODE39</option>
                            <option value="EAN13">EAN-13</option>
                            <option value="UPC">UPC</option>
                        </select>
                    </div>
                    <div class="barcode-option">
                        <label for="barcodeSize">Barcode Size</label>
                        <select id="barcodeSize">
                            <option value="small">Small</option>
                            <option value="medium" selected>Medium</option>
                            <option value="large">Large</option>
                        </select>
                    </div>
                    <div class="barcode-option">
                        <label for="barcodeCount">Copies Per Product</label>
                        <input type="number" id="barcodeCount" min="1" max="20" value="1">
                    </div>
                    <div class="barcode-option">
                        <label for="showProductName">Show Product Name</label>
                        <select id="showProductName">
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                    </div>
                </div>

                <div class="select-all-container">
                    <input type="checkbox" id="selectAllModal">
                    <label for="selectAllModal">Select All Products</label>
                </div>

                <div class="barcode-preview">
                    <h4>Preview</h4>
                    <div id="barcodePreview" class="barcode-grid">
                        <p class="text-muted">No Products Selected</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer no-print">
                <button class="btn btn-secondary" id="cancelPrint">Cancel</button>
                <button class="btn btn-success" id="printBarcodes">Print</button>
            </div>
        </div>
    </div>

    <!-- Barcode Print Section (Hidden until printing) -->
    <div id="barcodePrintSection" class="barcode-print-section" style="display: none;">
        <!-- Barcodes will be generated here for printing -->
    </div>
@endsection

@section('js')
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Search functionality dengan server-side filtering
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const categoryFilter = document.getElementById('categoryFilter');
            const resetButton = document.getElementById('resetButton');

            // Function untuk update URL dengan filters
            function updateURL() {
                const params = new URLSearchParams();

                // Add search
                if (searchInput.value) params.append('search', searchInput.value);

                // Add status filter
                if (statusFilter.value) params.append('status', statusFilter.value);

                // Add category filter
                if (categoryFilter.value) params.append('category_id', categoryFilter.value);

                // Preserve sort parameters
                const currentUrl = new URL(window.location.href);
                const sortBy = currentUrl.searchParams.get('sort_by') || 'created_at';
                const sortOrder = currentUrl.searchParams.get('sort_order') || 'desc';

                params.append('sort_by', sortBy);
                params.append('sort_order', sortOrder);

                // Redirect ke URL baru dengan filters
                window.location.href = `{{ route('products.index') }}?${params.toString()}`;
            }

            // Event listeners untuk filters dengan debounce
            let searchTimeout;
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(updateURL, 500);
            });

            statusFilter.addEventListener('change', updateURL);
            categoryFilter.addEventListener('change', updateURL);

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

            // Delete product functionality dengan modal SweetAlert2
            document.querySelectorAll('.delete-product').forEach(button => {
                button.addEventListener('click', function () {
                    const url = this.dataset.url;
                    const productName = this.dataset.name;

                    Swal.fire({
                        title: 'Are you sure?',
                        text: `You are about to delete product: ${productName}. This action cannot be undone!`,
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
                window.location.href = '{{ route('products.index') }}';
            });

            // Barcode Printing Functionality
            const modal = document.getElementById('barcodeModal');
            const printBtn = document.getElementById('printBarcodeBtn');
            const closeBtn = document.querySelector('.close');
            const cancelBtn = document.getElementById('cancelPrint');
            const printBarcodesBtn = document.getElementById('printBarcodes');
            const selectAllCheckbox = document.getElementById('selectAllCheckbox');
            const selectAllModal = document.getElementById('selectAllModal');

            // Open modal when print button is clicked
            printBtn.addEventListener('click', function () {
                modal.style.display = 'block';
                updateBarcodePreview();
            });

            // Close modal when X is clicked
            closeBtn.addEventListener('click', function () {
                modal.style.display = 'none';
            });

            // Close modal when cancel button is clicked
            cancelBtn.addEventListener('click', function () {
                modal.style.display = 'none';
            });

            // Close modal when clicking outside the modal
            window.addEventListener('click', function (event) {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });

            // Select all products in the table
            selectAllCheckbox.addEventListener('change', function () {
                const checkboxes = document.querySelectorAll('.product-select');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
                updateBarcodePreview();
            });

            // Select all products in the modal
            selectAllModal.addEventListener('change', function () {
                const checkboxes = document.querySelectorAll('.product-select');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = selectAllModal.checked;
                });
                selectAllCheckbox.checked = selectAllModal.checked;
                updateBarcodePreview();
            });

            // Update barcode preview when options change
            document.getElementById('barcodeType').addEventListener('change', updateBarcodePreview);
            document.getElementById('barcodeSize').addEventListener('change', updateBarcodePreview);
            document.getElementById('barcodeCount').addEventListener('change', updateBarcodePreview);
            document.getElementById('showProductName').addEventListener('change', updateBarcodePreview);

            // Update barcode preview when product selection changes
            document.querySelectorAll('.product-select').forEach(checkbox => {
                checkbox.addEventListener('change', updateBarcodePreview);
            });

            // Print single barcode
            document.querySelectorAll('.print-single-barcode').forEach(button => {
                button.addEventListener('click', function () {
                    const barcode = this.dataset.barcode;
                    const name = this.dataset.name;

                    // Select only this product
                    document.querySelectorAll('.product-select').forEach(checkbox => {
                        checkbox.checked = false;
                    });

                    // Find and check the checkbox for this product
                    const productCheckbox = document.querySelector(
                        `.product-select[data-barcode="${barcode}"]`);
                    if (productCheckbox) {
                        productCheckbox.checked = true;
                        selectAllCheckbox.checked = false;
                        selectAllModal.checked = false;
                    }

                    // Open modal and generate preview
                    modal.style.display = 'block';
                    updateBarcodePreview();
                });
            });

            // Print barcodes button
            printBarcodesBtn.addEventListener('click', function () {
                const selectedProducts = getSelectedProducts();

                if (selectedProducts.length === 0) {
                    Swal.fire({
                        title: 'No Products Selected',
                        text: 'Please select products to print barcodes.',
                        icon: 'warning',
                        confirmButtonColor: '#4361ee'
                    });
                    return;
                }

                // Generate barcodes for printing
                generateBarcodesForPrinting();

                // Wait a moment for DOM updates, then print
                setTimeout(() => {
                    window.print();
                }, 500);
            });

            // Function to update barcode preview
            function updateBarcodePreview() {
                const barcodePreview = document.getElementById('barcodePreview');
                const selectedProducts = getSelectedProducts();

                if (selectedProducts.length === 0) {
                    barcodePreview.innerHTML = '<p class="text-muted">No products selected</p>';
                    return;
                }

                // Generate preview barcode
                generateBarcodePreview(selectedProducts);
            }

            // Function generate preview
            function generateBarcodePreview(products) {
                const barcodePreview = document.getElementById('barcodePreview');
                barcodePreview.innerHTML = '';

                const barcodeType = document.getElementById('barcodeType').value;
                const barcodeSize = document.getElementById('barcodeSize').value;
                const copies = parseInt(document.getElementById('barcodeCount').value);
                const showName = document.getElementById('showProductName').value === 'yes';

                const sizeMap = {
                    small: { width: 1, height: 50 },
                    medium: { width: 2, height: 100 },
                    large: { width: 3, height: 150 }
                };

                const size = sizeMap[barcodeSize];

                products.forEach(product => {
                    for (let i = 0; i < copies; i++) {
                        const barcodeItem = document.createElement('div');
                        barcodeItem.className = 'barcode-item';

                        const barcodeCanvas = document.createElement('canvas');
                        barcodeItem.appendChild(barcodeCanvas);

                        if (showName) {
                            const nameElement = document.createElement('div');
                            nameElement.className = 'barcode-text';
                            nameElement.textContent = product.name;
                            barcodeItem.appendChild(nameElement);
                        }

                        const barcodeText = document.createElement('div');
                        barcodeText.className = 'barcode-text';
                        barcodeText.textContent = product.barcode;
                        barcodeItem.appendChild(barcodeText);

                        barcodePreview.appendChild(barcodeItem);

                        // Generate barcode
                        try {
                            JsBarcode(barcodeCanvas, product.barcode, {
                                format: barcodeType,
                                width: size.width,
                                height: size.height,
                                displayValue: false
                            });
                        } catch (error) {
                            console.error('Barcode generation error:', error);
                            barcodeCanvas.parentNode.removeChild(barcodeCanvas);
                            const errorText = document.createElement('div');
                            errorText.textContent = 'Barcode Error';
                            barcodeItem.appendChild(errorText);
                        }
                    }
                });
            }

            // Function untuk print
            function generateBarcodesForPrinting() {
                const printSection = document.getElementById('barcodePrintSection');
                printSection.innerHTML = '';

                const selectedProducts = getSelectedProducts();
                const barcodeType = document.getElementById('barcodeType').value;
                const barcodeSize = document.getElementById('barcodeSize').value;
                const copies = parseInt(document.getElementById('barcodeCount').value);
                const showName = document.getElementById('showProductName').value === 'yes';

                // Size untuk print (lebih besar)
                const sizeMap = {
                    small: { width: 1.5, height: 60 },
                    medium: { width: 2.5, height: 120 },
                    large: { width: 3.5, height: 180 }
                };

                const size = sizeMap[barcodeSize];

                const barcodeGrid = document.createElement('div');
                barcodeGrid.className = 'barcode-grid';

                selectedProducts.forEach(product => {
                    for (let i = 0; i < copies; i++) {
                        const barcodeItem = document.createElement('div');
                        barcodeItem.className = 'barcode-item';

                        const barcodeCanvas = document.createElement('canvas');
                        barcodeItem.appendChild(barcodeCanvas);

                        if (showName) {
                            const nameElement = document.createElement('div');
                            nameElement.className = 'barcode-text';
                            nameElement.textContent = product.name;
                            barcodeItem.appendChild(nameElement);
                        }

                        const barcodeText = document.createElement('div');
                        barcodeText.className = 'barcode-text';
                        barcodeText.textContent = product.barcode;
                        barcodeItem.appendChild(barcodeText);

                        barcodeGrid.appendChild(barcodeItem);

                        // Generate barcode untuk print
                        try {
                            JsBarcode(barcodeCanvas, product.barcode, {
                                format: barcodeType,
                                width: size.width,
                                height: size.height,
                                displayValue: false
                            });
                        } catch (error) {
                            console.error('Barcode generation error:', error);
                            barcodeCanvas.parentNode.removeChild(barcodeCanvas);
                            const errorText = document.createElement('div');
                            errorText.textContent = 'Barcode Error';
                            barcodeItem.appendChild(errorText);
                        }
                    }
                });

                printSection.appendChild(barcodeGrid);
                printSection.style.display = 'block';
            }

            // Function to get selected products
            function getSelectedProducts() {
                const selectedProducts = [];
                document.querySelectorAll('.product-select:checked').forEach(checkbox => {
                    selectedProducts.push({
                        id: checkbox.dataset.id,
                        barcode: checkbox.dataset.barcode,
                        name: checkbox.dataset.name
                    });
                });
                return selectedProducts;
            }

            // After print event, hide the print section
            window.addEventListener('afterprint', function () {
                document.getElementById('barcodePrintSection').style.display = 'none';
            });
        });
    </script>
@endsection