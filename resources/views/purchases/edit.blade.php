@extends('layouts.admin')

@section('title', __('purchase.Edit_Purchase'))
@section('content-header', __('purchase.Edit_Purchase'))

@section('css')
    <style>
        .purchase-edit-container {
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
            padding: 2rem;
        }

        .form-section {
            margin-bottom: 2rem;
            padding: 1.5rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: #f8fafc;
        }

        .form-section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            background-color: white;
            min-height: 48px;
            box-sizing: border-box;
        }

        select.form-control {
            min-height: 52px;
            padding-top: 0.875rem;
            padding-bottom: 0.875rem;
            line-height: 1.5;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
            background-size: 16px 12px;
            padding-right: 2.5rem;
        }

        .form-control:focus {
            outline: none;
            border-color: #4361ee;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        }

        .form-control.is-invalid {
            border-color: #e53e3e;
            box-shadow: 0 0 0 3px rgba(229, 62, 62, 0.15);
        }

        .invalid-feedback {
            display: block;
            color: #e53e3e;
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }

        .form-actions {
            display: flex;
            gap: 0.5rem;
            justify-content: flex-end;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
        }

        .btn {
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            text-decoration: none;
            min-height: 40px;
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
            background-color: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #374151;
            transform: translateY(-1px);
        }

        /* Table Styles */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .items-table th,
        .items-table td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        .items-table th {
            background-color: #f8fafc;
            font-weight: 600;
            color: #374151;
            font-size: 0.9rem;
        }

        .items-table input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        .items-table input:focus {
            outline: none;
            border-color: #4361ee;
        }

        .btn-danger {
            background-color: #e53e3e;
            color: white;
            padding: 0.375rem 0.75rem;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }

        .btn-danger:hover {
            background-color: #c53030;
        }

        .btn-success {
            background-color: #10b981;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .btn-success:hover {
            background-color: #059669;
        }

        .total-section {
            background: white;
            padding: 1rem;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            margin-top: 1rem;
        }

        .total-amount {
            font-size: 1.25rem;
            font-weight: 600;
            color: #059669;
        }

        .no-items {
            text-align: center;
            padding: 2rem;
            color: #6b7280;
            font-style: italic;
        }

        /* Supplier Option Style */
        .supplier-option {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .supplier-name {
            font-weight: 500;
        }

        .supplier-products {
            font-size: 0.8rem;
            color: #6b7280;
            margin-left: 1rem;
        }

        .product-badge {
            display: inline-block;
            padding: 0.1rem 0.4rem;
            background-color: #e2e8f0;
            color: #4a5568;
            border-radius: 3px;
            font-size: 0.7rem;
            margin-right: 0.2rem;
            margin-bottom: 0.1rem;
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 1.5rem;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .items-table {
                font-size: 0.8rem;
            }

            .items-table th,
            .items-table td {
                padding: 0.5rem;
            }

            .supplier-option {
                flex-direction: column;
                align-items: flex-start;
            }

            .supplier-products {
                margin-left: 0;
                margin-top: 0.25rem;
            }
        }

        .select-form-group {
            position: relative;
        }

        .btn-loading {
            position: relative;
            color: transparent;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin: -8px 0 0 -8px;
            border: 2px solid transparent;
            border-top-color: currentColor;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }
    </style>
@endsection

@section('content')
    <div class="purchase-edit-container">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('purchases.update', $purchase->id) }}" method="POST" id="purchaseForm">
                    @csrf
                    @method('PUT')

                    <!-- Purchase Information Section -->
                    <div class="form-section">
                        <h3 class="form-section-title">{{ __('purchase.Purchase_Information') }}</h3>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="supplier_id" class="form-label">{{ __('purchase.Supplier') }}</label>
                                <select name="supplier_id" class="form-control @error('supplier_id') is-invalid @enderror"
                                    id="supplier_id">
                                    <option value="">{{ __('purchase.Select_Supplier') }}</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id', $purchase->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                            <span class="supplier-name">{{ $supplier->first_name }}
                                                {{ $supplier->last_name }}</span>
                                            @if($supplier->products->count() > 0)
                                                (
                                                @foreach($supplier->products->take(3) as $index => $product)
                                                    {{ $product->name }}@if(!$loop->last), @endif
                                                @endforeach
                                                @if($supplier->products->count() > 3)
                                                    and {{ $supplier->products->count() - 3 }} more...
                                                @endif
                                                )
                                            @else
                                                (No products)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="invoice_number" class="form-label">{{ __('purchase.Invoice_Number') }} *</label>
                                <input type="text" name="invoice_number"
                                    class="form-control @error('invoice_number') is-invalid @enderror" id="invoice_number"
                                    placeholder="{{ __('purchase.Invoice_Number') }}"
                                    value="{{ old('invoice_number', $purchase->invoice_number) }}" required readonly>
                                <small class="text-muted">{{ __('purchase.Invoice_number_cannot_be_changed') }}</small>
                                @error('invoice_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="purchase_date" class="form-label">{{ __('purchase.Purchase_Date') }} *</label>
                                <input type="date" name="purchase_date"
                                    class="form-control @error('purchase_date') is-invalid @enderror" id="purchase_date"
                                    value="{{ old('purchase_date', $purchase->purchase_date) }}" required>
                                @error('purchase_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Purchase Items Section -->
                    <div class="form-section">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="form-section-title">{{ __('purchase.Purchase_Items') }}</h3>
                            <button type="button" class="btn btn-success" id="addItemBtn">
                                <i class="fas fa-plus"></i> {{ __('purchase.Add_Item') }}
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="items-table" id="itemsTable">
                                <thead>
                                    <tr>
                                        <th width="30%">{{ __('purchase.Product') }}</th>
                                        <th width="15%">{{ __('purchase.Quantity') }}</th>
                                        <th width="15%">{{ __('purchase.Price') }}</th>
                                        <th width="20%">{{ __('purchase.Expired_Date') }}</th>
                                        <th width="10%">{{ __('purchase.Subtotal') }}</th>
                                        <th width="10%">{{ __('common.Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="itemsTbody">
                                    <!-- Items will be added dynamically from existing purchase items -->
                                </tbody>
                            </table>
                        </div>

                        <div id="noItemsMessage" class="no-items">
                            {{ __('purchase.No_items_added') }}
                        </div>

                        <!-- Total Amount -->
                        <div class="total-section">
                            <div class="d-flex justify-content-between align-items-center">
                                <strong>{{ __('purchase.Total_Amount') }}:</strong>
                                <span class="total-amount" id="totalAmount">Rp 0.00</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('purchases.index') }}" class="btn btn-secondary">
                            {{ __('common.Cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            {{ __('common.Update') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let itemCounter = 0;
            const itemsTbody = document.getElementById('itemsTbody');
            const noItemsMessage = document.getElementById('noItemsMessage');
            const totalAmountElement = document.getElementById('totalAmount');
            const supplierSelect = document.getElementById('supplier_id');
            const products = @json($products ?? []);

            // Data supplier dengan produknya
            const suppliersWithProducts = @json($suppliers ?? []);

            // Data purchase items yang sudah ada
            const existingItems = @json($purchase->items ?? []);

            // Supplier change event - filter produk berdasarkan supplier
            supplierSelect.addEventListener('change', function () {
                filterProductsBySupplier(this.value);
            });

            function filterProductsBySupplier(supplierId) {
                if (!supplierId) {
                    // Jika tidak ada supplier yang dipilih, tampilkan semua produk
                    updateProductSelects(products);
                    return;
                }

                const supplier = suppliersWithProducts.find(s => s.id == supplierId);
                if (supplier && supplier.products && supplier.products.length > 0) {
                    // Filter produk berdasarkan supplier
                    updateProductSelects(supplier.products);
                } else {
                    updateProductSelects([]);
                }
            }

            function updateProductSelects(filteredProducts) {
                const productSelects = document.querySelectorAll('.product-select');
                productSelects.forEach(select => {
                    const currentValue = select.value;
                    select.innerHTML = '<option value="">{{ __('purchase.Select_Product') }}</option>';

                    filteredProducts.forEach(product => {
                        const option = document.createElement('option');
                        option.value = product.id;
                        option.textContent = product.name;
                        option.selected = (product.id == currentValue);
                        select.appendChild(option);
                    });

                    // Jika produk yang dipilih sebelumnya tidak ada di filtered products, reset value
                    if (currentValue && !filteredProducts.some(p => p.id == currentValue)) {
                        select.value = '';
                    }
                });
            }

            // Add new item row
            document.getElementById('addItemBtn').addEventListener('click', function () {
                addItemRow();
            });

            function addItemRow(productId = '', quantity = 1, price = 0, expiredDate = '') {
                itemCounter++;
                const row = document.createElement('tr');

                // Get available products based on selected supplier
                const availableProducts = getAvailableProducts();

                row.innerHTML = `
                        <td>
                            <select name="items[${itemCounter}][product_id]" class="form-control product-select" required>
                                <option value="">{{ __('purchase.Select_Product') }}</option>
                                ${availableProducts.map(product =>
                    `<option value="${product.id}" ${productId == product.id ? 'selected' : ''}>${product.name}</option>`
                ).join('')}
                            </select>
                        </td>
                        <td>
                            <input type="number" name="items[${itemCounter}][quantity]" class="form-control quantity-input" 
                                value="${quantity}" min="1" required>
                        </td>
                        <td>
                            <input type="number" name="items[${itemCounter}][price]" class="form-control price-input" 
                                value="${price}" min="0" step="0.01" required>
                        </td>
                        <td>
                            <input type="date" name="items[${itemCounter}][expired_date]" class="form-control expired-date-input" 
                                value="${expiredDate}">
                        </td>
                        <td>
                            <span class="subtotal">Rp 0.00</span>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger remove-item-btn">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    `;

                itemsTbody.appendChild(row);
                updateNoItemsMessage();
                attachEventListeners(row);
                calculateTotal();

                // Update subtotal untuk row yang baru ditambahkan
                const quantityInput = row.querySelector('.quantity-input');
                const priceInput = row.querySelector('.price-input');
                const subtotalElement = row.querySelector('.subtotal');
                updateRowSubtotal(quantityInput, priceInput, subtotalElement);
            }

            function getAvailableProducts() {
                const supplierId = supplierSelect.value;
                if (!supplierId) {
                    return products; // Return all products if no supplier selected
                }

                const supplier = suppliersWithProducts.find(s => s.id == supplierId);
                return supplier && supplier.products ? supplier.products : [];
            }

            function attachEventListeners(row) {
                // Quantity and price change events
                const quantityInput = row.querySelector('.quantity-input');
                const priceInput = row.querySelector('.price-input');
                const subtotalElement = row.querySelector('.subtotal');

                function updateSubtotal() {
                    updateRowSubtotal(quantityInput, priceInput, subtotalElement);
                }

                quantityInput.addEventListener('input', updateSubtotal);
                priceInput.addEventListener('input', updateSubtotal);

                // Price input blur event untuk format .00
                priceInput.addEventListener('blur', function () {
                    if (this.value) {
                        this.value = parseFloat(this.value).toFixed(2);
                        updateSubtotal();
                    }
                });

                // Remove item event
                row.querySelector('.remove-item-btn').addEventListener('click', function () {
                    row.remove();
                    updateNoItemsMessage();
                    calculateTotal();
                });
            }

            function updateRowSubtotal(quantityInput, priceInput, subtotalElement) {
                const quantity = parseFloat(quantityInput.value) || 0;
                const price = parseFloat(priceInput.value) || 0;
                const subtotal = quantity * price;
                subtotalElement.textContent = formatCurrency(subtotal);
                calculateTotal();
            }

            function updateNoItemsMessage() {
                if (itemsTbody.children.length === 0) {
                    noItemsMessage.style.display = 'block';
                } else {
                    noItemsMessage.style.display = 'none';
                }
            }

            function calculateTotal() {
                let total = 0;
                const rows = itemsTbody.querySelectorAll('tr');

                rows.forEach(row => {
                    const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
                    const price = parseFloat(row.querySelector('.price-input').value) || 0;
                    total += quantity * price;
                });

                totalAmountElement.textContent = formatCurrency(total);
            }

            function formatCurrency(amount) {
                return 'Rp ' + amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            }

            // Form submission handling
            document.getElementById('purchaseForm').addEventListener('submit', function (e) {
                const itemsCount = itemsTbody.children.length;
                if (itemsCount === 0) {
                    e.preventDefault();
                    alert('{{ __("purchase.Please_add_at_least_one_item") }}');
                    return;
                }

                const submitBtn = document.getElementById('submitBtn');
                submitBtn.classList.add('btn-loading');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner"></i> {{ __("common.Updating") }}';
            });

            // Load existing items when page loads
            function loadExistingItems() {
                existingItems.forEach(item => {
                    addItemRow(
                        item.product_id,
                        item.quantity,
                        item.price,
                        item.expired_date ? item.expired_date : ''
                    );
                });
            }

            // Initialize
            loadExistingItems();

            // Initialize product filtering if supplier is pre-selected
            const initialSupplierId = supplierSelect.value;
            if (initialSupplierId) {
                filterProductsBySupplier(initialSupplierId);
            }
        });
    </script>
@endsection