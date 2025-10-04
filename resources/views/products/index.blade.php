@extends('layouts.admin')

@section('title', __('product.Product_List'))
@section('content-header', __('product.Product_List'))
@section('content-actions')
    <a href="{{route('products.create')}}" class="btn btn-primary">
        <i class="fas fa-plus-circle"></i> {{ __('product.Create_Product') }}
    </a>
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

        .filter-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #2d3748;
            margin: 0;
        }

        .product-table-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .product-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .product-table th {
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

        .product-table td {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
            color: #4a5568;
        }

        .product-table tr:last-child td {
            border-bottom: none;
        }

        .product-table tr:hover {
            background-color: #f7fafc;
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

        .btn-barcode {
            background-color: #e6fffa;
            color: #234e52;
        }

        .btn-barcode:hover {
            background-color: #b2f5ea;
            transform: translateY(-1px);
        }

        .pagination-container {
            display: flex;
            justify-content: center;
            padding: 1.5rem 0;
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

        .search-filter {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .search-input {
            flex: 1;
            min-width: 250px;
            position: relative;
        }

        .search-input input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.9rem;
        }

        .search-input i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
        }

        .filter-select {
            min-width: 150px;
        }

        .filter-select select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.9rem;
            background: white;
        }

        @media (max-width: 768px) {
            .product-table-container {
                margin: 0 -1rem;
            }

            .product-table {
                min-width: 800px;
            }

            .search-filter {
                flex-direction: column;
            }

            .search-input,
            .filter-select {
                min-width: 100%;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-action {
                width: 32px;
                height: 32px;
            }
        }

        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
        }

        .product-name {
            font-weight: 500;
            color: #2d3748;
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .text-price {
            font-weight: 600;
            color: #2d3748;
        }

        .text-quantity {
            font-weight: 500;
        }

        .low-stock {
            color: #e53e3e;
            font-weight: 600;
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
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
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
        .empty-state {
            text-align: center;
            padding: 60px 40px;
        }

        .empty-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: #a0aec0;
        }

        .empty-state p {
            color: #a0b3c6;
            font-size: 16px;
            margin-bottom: 28px;
            font-weight: 500;
        }

        .create-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #4A90E2;
            color: white;
            padding: 14px 28px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(74, 144, 226, 0.25);
        }

        .create-btn:hover {
            background: #3d7bc7;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(74, 144, 226, 0.35);
            color: white;
            text-decoration: none;
        }

        .create-btn:active {
            transform: translateY(0);
        }

        .plus-circle {
            width: 28px;
            height: 28px;
            background: rgba(255, 255, 255, 0.25);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 600;
        }

        /* Print-specific styles */
        @media print {
            body * {
                visibility: hidden;
            }
            .barcode-print-section, .barcode-print-section * {
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
        <div class="filter-card">
            <!-- Search and Filter -->
            <div class="search-filter">
                <div class="search-input">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="{{ __('product.Search_Products') }}"
                        onkeyup="filterProducts()">
                </div>
                <div class="filter-select">
                    <select id="statusFilter" onchange="filterProducts()">
                        <option value="">{{ __('product.Filter_Status') }}</option>
                        <option value="active">{{ __('common.Active') }}</option>
                        <option value="inactive">{{ __('common.Inactive') }}</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="product-table-container">
            <!-- Products Table -->
            <div class="table-responsive">
                <table class="product-table">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="selectAllCheckbox" class="product-checkbox">
                            </th>
                            <th>{{ __('product.ID') }}</th>
                            <th>{{ __('product.Name') }}</th>
                            <th>{{ __('product.Image') }}</th>
                            <th>{{ __('product.Barcode') }}</th>
                            <th>{{ __('product.Price') }}</th>
                            <th>{{ __('product.Quantity') }}</th>
                            <th>{{ __('product.Status') }}</th>
                            <th>{{ __('product.Created_At') }}</th>
                            <th>{{ __('product.Updated_At') }}</th>
                            <th>{{ __('product.Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>
                                    <input type="checkbox" class="product-checkbox product-select" data-id="{{ $product->id }}" data-barcode="{{ $product->barcode }}" data-name="{{ $product->name }}">
                                </td>
                                <td>{{$product->id}}</td>
                                <td>
                                    <span class="product-name" title="{{$product->name}}">{{$product->name}}</span>
                                </td>
                                <td>
                                    <img class="product-img" src="{{ Storage::url($product->image) }}" alt="{{$product->name}}">
                                </td>
                                <td>
                                    <code>{{$product->barcode}}</code>
                                </td>
                                <td>
                                    <span class="text-price">{{config('settings.currency_symbol')}}
                                        {{number_format($product->price, 2)}}</span>
                                </td>
                                <td>
                                    <span
                                        class="text-quantity {{ $product->quantity <= config('settings.warning_quantity') ? 'low-stock' : '' }}">
                                        {{$product->quantity}}
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge {{ $product->status ? 'status-active' : 'status-inactive' }}">
                                        {{$product->status ? __('common.Active') : __('common.Inactive') }}
                                    </span>
                                </td>
                                <td>{{$product->created_at->format('M d, Y')}}</td>
                                <td>{{$product->updated_at->format('M d, Y')}}</td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('products.edit', $product) }}" class="btn-action btn-edit"
                                            title="{{ __('product.Edit_Product') }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn-action btn-delete" data-url="{{route('products.destroy', $product)}}"
                                            title="{{ __('product.Delete_Product') }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <button class="btn-action btn-barcode print-single-barcode" 
                                            data-barcode="{{$product->barcode}}" 
                                            data-name="{{$product->name}}"
                                            title="{{ __('product.Print_Barcode') }}">
                                            <i class="fas fa-barcode"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        @if($products->count() === 0)
                            <tr>
                                <td colspan="11">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <i class="fas fa-box-open"></i>
                                        </div>
                                        <p>{{ __('product.No_Products_Found') }}</p>
                                        <a href="{{route('products.create')}}" class="create-btn">
                                            <span class="plus-circle">+</span>
                                            <span>{{ __('product.Create_First_Product') }}</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($products->count() > 0)
                <div class="pagination-container">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Barcode Print Modal -->
    <div id="barcodeModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">{{ __('product.Print_Barcode') }}</h3>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <div class="barcode-options">
                    <div class="barcode-option">
                        <label for="barcodeType">{{ __('product.Barcode_Type') }}</label>
                        <select id="barcodeType">
                            <option value="CODE128">CODE128</option>
                            <option value="CODE39">CODE39</option>
                            <option value="EAN13">EAN-13</option>
                            <option value="UPC">UPC</option>
                        </select>
                    </div>
                    <div class="barcode-option">
                        <label for="barcodeSize">{{ __('product.Barcode_Size') }}</label>
                        <select id="barcodeSize">
                            <option value="small">{{ __('product.Small') }}</option>
                            <option value="medium" selected>{{ __('product.Medium') }}</option>
                            <option value="large">{{ __('product.Large') }}</option>
                        </select>
                    </div>
                    <div class="barcode-option">
                        <label for="barcodeCount">{{ __('product.Copies_Per_Product') }}</label>
                        <input type="number" id="barcodeCount" min="1" max="20" value="1">
                    </div>
                    <div class="barcode-option">
                        <label for="showProductName">{{ __('product.Show_Product_Name') }}</label>
                        <select id="showProductName">
                            <option value="yes">{{ __('product.Yes') }}</option>
                            <option value="no">{{ __('product.No') }}</option>
                        </select>
                    </div>
                </div>
                
                <div class="select-all-container">
                    <input type="checkbox" id="selectAllModal">
                    <label for="selectAllModal">{{ __('product.Select_All_Products') }}</label>
                </div>
                
                <div class="barcode-preview">
                    <h4>{{ __('product.Preview') }}</h4>
                    <div id="barcodePreview" class="barcode-grid">
                        <p class="text-muted">{{ __('product.No_Products_Selected') }}</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer no-print">
                <button class="btn btn-secondary" id="cancelPrint">{{ __('common.Cancel') }}</button>
                <button class="btn btn-success" id="printBarcodes">{{ __('common.Print') }}</button>
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
            // SweetAlert delete confirmation
            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', function () {
                    const url = this.dataset.url;
                    const productName = this.closest('tr').querySelector('.product-name').textContent;

                    Swal.fire({
                        title: '{{ __("product.sure") }}',
                        text: '{{ __("product.really_delete") }}: ' + productName + '?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#4361ee',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: '{{ __("product.yes_delete") }}',
                        cancelButtonText: '{{ __("product.No") }}',
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
                                            title: '{{ __("product.Deleted") }}',
                                            text: '{{ __("product.Deleted_Message") }}',
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
                                        title: '{{ __("product.Error") }}',
                                        text: '{{ __("product.Delete_Error") }}',
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

            // Barcode Printing Functionality
            const modal = document.getElementById('barcodeModal');
            const printBtn = document.getElementById('printBarcodeBtn');
            const closeBtn = document.querySelector('.close');
            const cancelBtn = document.getElementById('cancelPrint');
            const printBarcodesBtn = document.getElementById('printBarcodes');
            const selectAllCheckbox = document.getElementById('selectAllCheckbox');
            const selectAllModal = document.getElementById('selectAllModal');
            
            // Open modal when print button is clicked
            printBtn.addEventListener('click', function() {
                modal.style.display = 'block';
                updateBarcodePreview();
            });
            
            // Close modal when X is clicked
            closeBtn.addEventListener('click', function() {
                modal.style.display = 'none';
            });
            
            // Close modal when cancel button is clicked
            cancelBtn.addEventListener('click', function() {
                modal.style.display = 'none';
            });
            
            // Close modal when clicking outside the modal
            window.addEventListener('click', function(event) {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
            
            // Select all products in the table
            selectAllCheckbox.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.product-select');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
                updateBarcodePreview();
            });
            
            // Select all products in the modal
            selectAllModal.addEventListener('change', function() {
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
                button.addEventListener('click', function() {
                    const barcode = this.dataset.barcode;
                    const name = this.dataset.name;
                    
                    // Select only this product
                    document.querySelectorAll('.product-select').forEach(checkbox => {
                        checkbox.checked = false;
                    });
                    
                    // Find and check the checkbox for this product
                    const productCheckbox = document.querySelector(`.product-select[data-barcode="${barcode}"]`);
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
            printBarcodesBtn.addEventListener('click', function() {
                const selectedProducts = getSelectedProducts();
                
                if (selectedProducts.length === 0) {
                    Swal.fire({
                        title: '{{ __("product.No_Products_Selected") }}',
                        text: '{{ __("product.Please_Select_Products_To_Print") }}',
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
                    barcodePreview.innerHTML = '<p class="text-muted">{{ __("product.No_Products_Selected") }}</p>';
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
                            errorText.textContent = '{{ __("product.Barcode_Error") }}';
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
                            errorText.textContent = '{{ __("product.Barcode_Error") }}';
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
            window.addEventListener('afterprint', function() {
                document.getElementById('barcodePrintSection').style.display = 'none';
            });
        });

        function filterProducts() {
            const searchText = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;

            document.querySelectorAll('.product-table tbody tr').forEach(row => {
                if (row.querySelector('.empty-state')) return;

                const name = row.querySelector('.product-name').textContent.toLowerCase();

                // Cara yang lebih reliable untuk mendapatkan status
                // Cek apakah badge memiliki kelas status-active
                const statusBadge = row.querySelector('.status-badge');
                let statusValue = '';

                if (statusBadge.classList.contains('status-active')) {
                    statusValue = 'active';
                } else if (statusBadge.classList.contains('status-inactive')) {
                    statusValue = 'inactive';
                }

                const nameMatch = name.includes(searchText);
                const statusMatch = statusFilter === '' || statusValue === statusFilter;

                row.style.display = (nameMatch && statusMatch) ? '' : 'none';
            });
        }
    </script>
@endsection