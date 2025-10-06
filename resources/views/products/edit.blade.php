@extends('layouts.admin')

@section('title', __('product.Edit_Product'))
@section('content-header', __('product.Edit_Product'))

@section('css')
    <style>
        .product-edit-container {
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
            /* Tinggi minimum untuk semua input */
            box-sizing: border-box;
        }

        /* Form Select yang Lebih Tinggi */
        .form-control.select {
            min-height: 52px;
            /* Lebih tinggi dari input biasa */
            padding: 0.875rem 1rem;
            /* Padding yang lebih besar */
            appearance: none;
            /* Hilangkan style default browser */
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
            background-size: 16px 12px;
            padding-right: 2.5rem;
            /* Space untuk dropdown arrow */
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
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            min-height: 48px;
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
        }

        .image-preview img {
            max-width: 200px;
            max-height: 200px;
            border-radius: 8px;
            border: 2px solid #e2e8f0;
        }

        .current-image {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 0.5rem;
        }

        .current-image-label {
            font-size: 0.8rem;
            color: #6b7280;
        }

        .character-count {
            font-size: 0.8rem;
            color: #6b7280;
            text-align: right;
            margin-top: 0.25rem;
        }

        /* Style khusus untuk select yang lebih tinggi */
        select.form-control {
            min-height: 52px;
            padding-top: 0.875rem;
            padding-bottom: 0.875rem;
            line-height: 1.5;
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

        /* Hover effect untuk option */
        select.form-control option:hover {
            background-color: #4361ee;
            color: white;
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

            .current-image {
                flex-direction: column;
                align-items: flex-start;
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
    </style>
@endsection

@section('content')
    <div class="product-edit-container">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('product.Edit_Product') }}</h3>
            </div>

            <div class="card-body">
                <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data"
                    id="productForm">
                    @csrf
                    @method('PUT')

                    <div class="form-section">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="name" class="form-label">{{ __('product.Name') }} *</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    id="name" placeholder="{{ __('product.Enter_Product_Name') }}"
                                    value="{{ old('name', $product->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="barcode" class="form-label">{{ __('product.Barcode') }}</label>
                                <input type="text" name="barcode"
                                    class="form-control @error('barcode') is-invalid @enderror" id="barcode"
                                    placeholder="{{ __('product.Enter_Barcode') }}"
                                    value="{{ old('barcode', $product->barcode) }}">
                                @error('barcode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-grid select-form-group">
                            <div class="form-group">
                                <label for="status" class="form-label">{{ __('product.Category') }}</label>
                                <select name="category_id" class="form-control @error('category_id') is-invalid @enderror"
                                    id="category_id">
                                    <option value="">{{ __('product.Select_Category') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group select-form-group">
                                <label for="status" class="form-label">{{ __('product.Status') }}</label>
                                <select name="status" class="form-control @error('status') is-invalid @enderror"
                                    id="status">
                                    <option value="1" {{ old('status', $product->status) == 1 ? 'selected' : ''}}>
                                        {{ __('common.Active') }}
                                    </option>
                                    <option value="0" {{ old('status', $product->status) == 0 ? 'selected' : ''}}>
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
                                <div class="price-input-container">
                                    <span class="currency-symbol">{{ config('settings.currency_symbol') }}</span>
                                    <input type="number" name="price"
                                        class="form-control price-input @error('price') is-invalid @enderror" id="price"
                                        placeholder="{{ __('product.Enter_Price') }}"
                                        value="{{ old('price', $product->price) }}" step="0.01" min="0" required>
                                </div>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="quantity" class="form-label">{{ __('product.Quantity') }} *</label>
                                <input type="number" name="quantity"
                                    class="form-control @error('quantity') is-invalid @enderror" id="quantity"
                                    placeholder="{{ __('product.Enter_Quantity') }}"
                                    value="{{ old('quantity', $product->quantity) }}" min="0" required>
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
                                id="description" placeholder="{{ __('product.Enter_Description') }}"
                                rows="4">{{ old('description', $product->description) }}</textarea>
                            <div class="character-count" id="descriptionCount">
                                {{ strlen(old('description', $product->description)) }} characters
                            </div>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-group">
                            <label for="image" class="form-label">{{ __('product.Image') }}</label>

                            @if($product->image)
                                <div class="current-image">
                                    <img src="{{ Storage::url($product->image) }}" alt="Current product image"
                                        style="max-width: 100px; border-radius: 8px; border: 2px solid #e2e8f0;">
                                    <span class="current-image-label">{{ old('current_image', $product->image) }}</span>
                                </div>
                            @endif

                            <div class="custom-file" style="margin-top: 1rem;">
                                <input type="file" class="custom-file-input @error('image') is-invalid @enderror"
                                    name="image" id="image" accept="image/*">
                                <label class="custom-file-label" for="image" id="imageLabel">
                                    {{ __('product.Choose_file') }}
                                </label>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="image-preview" id="imagePreview" style="display: none;">
                                <img src="" alt="Image preview" class="img-preview">
                                <div class="current-image-label">{{ __('product.New_Image_Preview') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('products.index') }}" class="btn btn-danger">
                            <i class="fas fa-times"></i> {{ __('common.Cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save"></i> {{ __('common.Update') }}
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
                    imageLabel.textContent = '{{ __("product.Choose_new_file") }}';
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
        });
    </script>
@endsection