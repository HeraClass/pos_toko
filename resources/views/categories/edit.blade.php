@extends('layouts.admin')

@section('title', isset($category) ? __('category.Update_Category') : __('category.Create_Category'))
@section('content-header', isset($category) ? __('category.Update_Category') : __('category.Create_Category'))

@section('css')
    <style>
        .category-form-container {
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

        .btn-danger {
            background-color: #e53e3e;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c53030;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(229, 62, 62, 0.3);
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

        .character-count {
            font-size: 0.75rem;
            color: #6b7280;
            text-align: right;
            margin-top: 0.25rem;
        }

        .character-count.warning {
            color: #e53e3e;
        }

        .slug-preview {
            background-color: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 0.5rem 0.75rem;
            font-size: 0.85rem;
            color: #4a5568;
            margin-top: 0.25rem;
        }

        .slug-preview strong {
            color: #2d3748;
        }
    </style>
@endsection

@section('content')
    <div class="category-form-container">
        <div class="card">
            <div class="card-body">
                <form action="{{ isset($category) ? route('categories.update', $category) : route('categories.store') }}"
                    method="POST" id="categoryForm">
                    @csrf
                    @if(isset($category))
                        @method('PUT')
                    @endif

                    <div class="form-section">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="name" class="form-label required-field">{{ __('category.Name') }}</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    id="name" placeholder="{{ __('category.Name') }}"
                                    value="{{ old('name', $category->name ?? '') }}" required maxlength="255">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-hint">{{ __('category.Name_Hint') }}</div>
                                <div class="character-count" id="nameCount">
                                    <span id="nameCurrent">0</span>/255
                                </div>
                            </div>

                            @if(isset($category) && $category->slug)
                                <div class="form-group">
                                    <label class="form-label">{{ __('category.Slug') }}</label>
                                    <div class="slug-preview">
                                        <strong>{{ $category->slug }}</strong>
                                    </div>
                                    <div class="form-hint">{{ __('category.Slug_Hint') }}</div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-group">
                            <label for="description" class="form-label">{{ __('category.Description') }}</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                id="description" placeholder="{{ __('category.Description') }}" rows="4"
                                maxlength="500">{{ old('description', $category->description ?? '') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="character-count" id="descriptionCount">
                                <span id="descriptionCurrent">0</span>/500
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">
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
            // Character count for name
            const nameInput = document.getElementById('name');
            const nameCurrent = document.getElementById('nameCurrent');
            const nameCount = document.getElementById('nameCount');

            // Character count for description
            const descriptionInput = document.getElementById('description');
            const descriptionCurrent = document.getElementById('descriptionCurrent');
            const descriptionCount = document.getElementById('descriptionCount');

            // Update character counts
            function updateCharacterCount(input, currentElement, countElement, maxLength) {
                const currentLength = input.value.length;
                currentElement.textContent = currentLength;

                if (currentLength > maxLength * 0.8) {
                    countElement.classList.add('warning');
                } else {
                    countElement.classList.remove('warning');
                }
            }

            // Initialize character counts
            updateCharacterCount(nameInput, nameCurrent, nameCount, 255);
            updateCharacterCount(descriptionInput, descriptionCurrent, descriptionCount, 500);

            // Add event listeners for real-time updates
            nameInput.addEventListener('input', function () {
                updateCharacterCount(this, nameCurrent, nameCount, 255);
            });

            descriptionInput.addEventListener('input', function () {
                updateCharacterCount(this, descriptionCurrent, descriptionCount, 500);
            });

            // Form submission handling
            const categoryForm = document.getElementById('categoryForm');
            const submitBtn = document.getElementById('submitBtn');

            categoryForm.addEventListener('submit', function () {
                // Add loading state to submit button
                submitBtn.classList.add('btn-loading');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner"></i> {{ __("common.Updating") }}';
            });

            // Real-time validation for required fields
            const requiredFields = document.querySelectorAll('input[required]');
            requiredFields.forEach(field => {
                field.addEventListener('blur', function () {
                    if (!this.value.trim()) {
                        this.classList.add('is-invalid');
                    } else {
                        this.classList.remove('is-invalid');
                    }
                });
            });

            // Prevent form submission if there are invalid fields
            categoryForm.addEventListener('submit', function (e) {
                const invalidFields = this.querySelectorAll('.is-invalid');
                if (invalidFields.length > 0) {
                    e.preventDefault();
                    // Scroll to first invalid field
                    invalidFields[0].scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            });

            // Auto-focus on name field
            if (nameInput.value === '') {
                nameInput.focus();
            }
        });
    </script>
@endsection