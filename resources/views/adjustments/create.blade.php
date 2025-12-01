@extends('layouts.admin')

@section('title', __('adjustment.Create_Adjustment'))
@section('content-header', __('adjustment.Create_Adjustment'))

@section('css')
    <style>
        .adjustment-form-container {
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
            margin-bottom: 1rem;
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
            margin-bottom: 0.5rem;
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

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
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
            background-color: #4b5563;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(107, 114, 128, 0.3);
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
        }

        /* Animation for form elements */
        .form-group {
            animation: fadeInUp 0.5s ease;
        }

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

        /* Loading state */
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

        .required-field::after {
            content: " *";
            color: #e53e3e;
        }

        .form-hint {
            font-size: 0.8rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }

        .stock-info {
            background-color: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 0.5rem 0.75rem;
            font-size: 0.85rem;
            color: #4a5568;
            margin-top: 0.25rem;
        }

        .stock-info strong {
            color: #2d3748;
        }

        .character-count {
            font-size: 0.75rem;
            color: #6b7280;
            text-align: right;
            margin-top: 0.25rem;
        }

        .character-count.warning {
            color: #e53e3e;
        }
    </style>
@endsection

@section('content')
    <div class="adjustment-form-container">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('adjustments.store') }}" method="POST" id="adjustmentForm">
                    @csrf

                    <div class="form-section">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="product_id"
                                    class="form-label required-field">{{ __('adjustment.Product') }}</label>
                                <select name="product_id" id="product_id"
                                    class="form-control @error('product_id') is-invalid @enderror" required>
                                    <option value="">{{ __('adjustment.Select_Product') }}</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }} data-stock="{{ $product->quantity }}">
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="stock-info" id="stockInfo">
                                    <strong>{{ __('product.Current_Stock') }}:</strong>
                                    <span id="currentStock">-</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="type" class="form-label required-field">{{ __('adjustment.Type') }}</label>
                                <select name="type" id="type" class="form-control @error('type') is-invalid @enderror"
                                    required>
                                    <option value="">{{ __('adjustment.Select_Type') }}</option>
                                    <option value="increase" {{ old('type') == 'increase' ? 'selected' : '' }}>
                                        {{ __('adjustment.Increase') }}
                                    </option>
                                    <option value="decrease" {{ old('type') == 'decrease' ? 'selected' : '' }}>
                                        {{ __('adjustment.Decrease') }}
                                    </option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="quantity"
                                    class="form-label required-field">{{ __('adjustment.Quantity') }}</label>
                                <input type="number" name="quantity" id="quantity"
                                    class="form-control @error('quantity') is-invalid @enderror"
                                    value="{{ old('quantity') }}" min="1" required>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-hint">{{ __('adjustment.Quantity_Hint') }}</div>
                            </div>

                            <div class="form-group">
                                <label for="adjusted_at"
                                    class="form-label required-field">{{ __('adjustment.Adjusted_At') }}</label>
                                <input type="datetime-local" name="adjusted_at" id="adjusted_at"
                                    class="form-control @error('adjusted_at') is-invalid @enderror"
                                    value="{{ old('adjusted_at', now()->format('Y-m-d\TH:i')) }}" required>
                                @error('adjusted_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-group">
                            <label for="reason" class="form-label">{{ __('adjustment.Reason') }}</label>
                            <textarea name="reason" id="reason" class="form-control @error('reason') is-invalid @enderror"
                                rows="4" placeholder="{{ __('adjustment.Enter_reason') }}"
                                maxlength="500">{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="character-count" id="reasonCount">
                                <span id="reasonCurrent">0</span>/500
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('adjustments.index') }}" class="btn btn-secondary">
                            {{ __('common.Cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            {{ __('common.Create') }}
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
            const productSelect = document.getElementById('product_id');
            const typeSelect = document.getElementById('type');
            const quantityInput = document.getElementById('quantity');
            const currentStockSpan = document.getElementById('currentStock');
            const stockInfo = document.getElementById('stockInfo');
            const reasonTextarea = document.getElementById('reason');
            const reasonCurrent = document.getElementById('reasonCurrent');
            const reasonCount = document.getElementById('reasonCount');
            const adjustmentForm = document.getElementById('adjustmentForm');
            const submitBtn = document.getElementById('submitBtn');

            // Update stock info when product is selected
            function updateStockInfo() {
                const selectedOption = productSelect.options[productSelect.selectedIndex];
                const stock = selectedOption.getAttribute('data-stock');

                if (stock !== null) {
                    currentStockSpan.textContent = stock;
                    stockInfo.style.display = 'block';
                } else {
                    stockInfo.style.display = 'none';
                }
            }

            // Validate quantity based on type and current stock
            function validateQuantity() {
                const selectedOption = productSelect.options[productSelect.selectedIndex];
                const currentStock = parseInt(selectedOption.getAttribute('data-stock')) || 0;
                const quantity = parseInt(quantityInput.value) || 0;
                const type = typeSelect.value;

                if (type === 'decrease' && quantity > currentStock) {
                    quantityInput.classList.add('is-invalid');
                    quantityInput.setCustomValidity('Quantity cannot exceed current stock for decrease adjustments');
                } else {
                    quantityInput.classList.remove('is-invalid');
                    quantityInput.setCustomValidity('');
                }
            }

            // Update character count for reason
            function updateCharacterCount() {
                const currentLength = reasonTextarea.value.length;
                reasonCurrent.textContent = currentLength;

                if (currentLength > 450) {
                    reasonCount.classList.add('warning');
                } else {
                    reasonCount.classList.remove('warning');
                }
            }

            // Initialize
            updateStockInfo();
            updateCharacterCount();

            // Event listeners
            productSelect.addEventListener('change', updateStockInfo);
            typeSelect.addEventListener('change', validateQuantity);
            quantityInput.addEventListener('input', validateQuantity);
            reasonTextarea.addEventListener('input', updateCharacterCount);

            // Form submission handling
            adjustmentForm.addEventListener('submit', function (e) {
                // Final validation
                validateQuantity();

                if (!adjustmentForm.checkValidity()) {
                    e.preventDefault();
                    // Scroll to first invalid field
                    const firstInvalid = adjustmentForm.querySelector('.is-invalid');
                    if (firstInvalid) {
                        firstInvalid.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }
                    return;
                }

                // Add loading state to submit button
                submitBtn.classList.add('btn-loading');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner"></i> {{ __("common.Creating") }}';
            });

            // Real-time validation for required fields
            const requiredFields = document.querySelectorAll('input[required], select[required]');
            requiredFields.forEach(field => {
                field.addEventListener('blur', function () {
                    if (!this.value) {
                        this.classList.add('is-invalid');
                    } else {
                        this.classList.remove('is-invalid');
                    }
                });
            });

            // Auto-focus on first field
            if (productSelect.value === '') {
                productSelect.focus();
            }
        });
    </script>
@endsection