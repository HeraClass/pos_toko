@extends('layouts.admin')

@section('title', __('product.Create_Product'))
@section('content-header', __('product.Create_Product'))

@section('css')
    <style>
        .product-create-container {
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

        /* Form Select yang Lebih Tinggi */
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

        .custom-file {
            position: relative;
            display: block;
        }

        .custom-file-input {
            position: relative;
            z-index: 2;
            width: 100%;
            height: calc(2.25rem + 2px);
            margin: 0;
            opacity: 0;
        }

        .custom-file-label {
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            z-index: 1;
            height: calc(2.25rem + 2px);
            padding: 0.75rem 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #495057;
            background-color: white;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            transition: all 0.3s ease;
            min-height: 48px;
            display: flex;
            align-items: center;
        }

        .custom-file-label::after {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            z-index: 3;
            display: block;
            height: calc(calc(2.25rem + 2px) - 1px * 2);
            padding: 0.75rem 1rem;
            line-height: 1.5;
            color: #495057;
            content: "Browse";
            background-color: #f9fafb;
            border-left: 1px solid #d1d5db;
            border-radius: 0 8px 8px 0;
            display: flex;
            align-items: center;
        }

        .custom-file-input:focus~.custom-file-label {
            border-color: #4361ee;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
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

        .btn-danger {
            background-color: #e53e3e;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c53030;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(229, 62, 62, 0.3);
        }

        .image-preview {
            margin-top: 1rem;
            display: none;
        }

        .image-preview img {
            max-width: 200px;
            max-height: 200px;
            border-radius: 8px;
            border: 2px solid #e2e8f0;
        }

        .character-count {
            font-size: 0.8rem;
            color: #6b7280;
            text-align: right;
            margin-top: 0.25rem;
        }

        /* Style untuk form group yang khusus select */
        .select-form-group {
            position: relative;
        }

        .select-form-group::after {
            content: '';
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 5px solid #6b7280;
            pointer-events: none;
        }

        /* Untuk browser yang mendukung ::-webkit-scrollbar */
        select.form-control::-webkit-scrollbar {
            width: 8px;
        }

        select.form-control::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        select.form-control::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        select.form-control::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Style untuk option yang lebih mudah dibaca */
        select.form-control option {
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
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

            /* Di mobile, tinggi bisa disesuaikan */
            .form-control {
                min-height: 44px;
            }

            select.form-control {
                min-height: 48px;
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

        /* Currency symbol untuk price input */
        .price-input-container {
            position: relative;
        }

        .currency-symbol {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            font-weight: 500;
            z-index: 2;
        }

        .price-input {
            padding-left: 30px;
        }

        /* Style untuk category badge */
        .category-badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            background-color: #e2e8f0;
            color: #4a5568;
            border-radius: 4px;
            font-size: 0.8rem;
            margin-left: 0.5rem;
        }
    </style>
@endsection

@section('content')
    <div class="product-create-container">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
                    @csrf

                    <div class="form-section">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="name" class="form-label">{{ __('product.Name') }} *</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    id="name" placeholder="{{ __('product.Name') }}" value="{{ old('name') }}"
                                    required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="barcode" class="form-label">{{ __('product.Barcode') }}</label>
                                <input type="text" name="barcode"
                                    class="form-control @error('barcode') is-invalid @enderror" id="barcode"
                                    placeholder="{{ __('product.Barcode') }}" value="{{ old('barcode') }}">
                                @error('barcode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-grid">
                            
                            <!-- FIELD CATEGORY_ID -->
                            <div class="form-group select-form-group">
                                <label for="category_id" class="form-label">{{ __('product.Category') }}</label>
                                <select name="category_id" class="form-control @error('category_id') is-invalid @enderror"
                                    id="category_id">
                                    <option value="">{{ __('product.Select_Category') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" 
                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group select-form-group">
                                <label for="status" class="form-label">{{ __('product.Status') }}</label>
                                <select name="status" class="form-control @error('status') is-invalid @enderror"
                                    id="status">
                                    <option value="1" {{ old('status', 1) == 1 ? 'selected' : ''}}>
                                        {{ __('common.Active') }}
                                    </option>
                                    <option value="0" {{ old('status') == 0 ? 'selected' : ''}}>
                                        {{ __('common.Inactive') }}
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="price" class="form-label">{{ __('product.Price') }} *</label>
                                <div class="form-group">
                                    <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                                        id="price" placeholder="{{ __('product.Price') }}" value="{{ old('price') }}"
                                        min="0" step="0.01" required>
                                </div>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="quantity" class="form-label">{{ __('product.Quantity') }} *</label>
                                <input type="number" name="quantity"
                                    class="form-control @error('quantity') is-invalid @enderror" id="quantity"
                                    placeholder="{{ __('product.Quantity') }}" value="{{ old('quantity', 1) }}"
                                    min="0" required>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-group">
                            <label for="description" class="form-label">{{ __('product.Description') }}</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                id="description" placeholder="{{ __('product.Description') }}"
                                rows="4">{{ old('description') }}</textarea>
                            <div class="character-count" id="descriptionCount">0 characters</div>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-group">
                            <label for="image" class="form-label">{{ __('product.Image') }}</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('image') is-invalid @enderror"
                                    name="image" id="image" accept="image/*">
                                <label class="custom-file-label" for="image" id="imageLabel">
                                    {{ __('product.Choose_file') }}
                                </label>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="image-preview" id="imagePreview">
                                <img src="" alt="Image preview" class="img-preview">
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">
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
    <script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize custom file input
            bsCustomFileInput.init();

            // Image preview functionality
            const imageInput = document.getElementById('image');
            const imagePreview = document.getElementById('imagePreview');
            const imageLabel = document.getElementById('imageLabel');
            const previewImg = document.querySelector('.img-preview');

            imageInput.addEventListener('change', function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        previewImg.src = e.target.result;
                        imagePreview.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                    imageLabel.textContent = file.name;
                } else {
                    imagePreview.style.display = 'none';
                    imageLabel.textContent = '{{ __("product.Choose_file") }}';
                }
            });

            // Description character count
            const descriptionTextarea = document.getElementById('description');
            const descriptionCount = document.getElementById('descriptionCount');

            descriptionTextarea.addEventListener('input', function () {
                const length = this.value.length;
                descriptionCount.textContent = `${length} characters`;

                if (length > 500) {
                    descriptionCount.style.color = '#e53e3e';
                } else {
                    descriptionCount.style.color = '#6b7280';
                }
            });

            // Trigger input event to update count initially
            descriptionTextarea.dispatchEvent(new Event('input'));

            // Form submission handling
            const productForm = document.getElementById('productForm');
            const submitBtn = document.getElementById('submitBtn');

            productForm.addEventListener('submit', function () {
                // Add loading state to submit button
                submitBtn.classList.add('btn-loading');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner"></i> {{ __("common.Creating") }}';
            });

            // Price input formatting
            const priceInput = document.getElementById('price');
            priceInput.addEventListener('blur', function () {
                if (this.value) {
                    this.value = parseFloat(this.value).toFixed(2);
                }
            });

            // Quantity input validation
            const quantityInput = document.getElementById('quantity');
            quantityInput.addEventListener('input', function () {
                if (this.value < 0) {
                    this.value = 0;
                }
            });

            // Category search functionality (optional enhancement)
            const categorySelect = document.getElementById('category_id');
        });
    </script>
@endsection