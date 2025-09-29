@extends('layouts.admin')

@section('title', __('customer.Create_Customer'))
@section('content-header', __('customer.Create_Customer'))

@section('css')
    <style>
        .customer-create-container {
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

        .avatar-preview {
            margin-top: 1rem;
            display: none;
        }

        .avatar-preview img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .avatar-placeholder {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: #f7fafc;
            border: 3px dashed #cbd5e0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #a0aec0;
            font-size: 0.8rem;
            text-align: center;
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

            .avatar-preview img,
            .avatar-placeholder {
                width: 100px;
                height: 100px;
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
            margin-top: 1rem;
        }
    </style>
@endsection

@section('content')
    <div class="customer-create-container">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('customers.store') }}" method="POST" enctype="multipart/form-data" id="customerForm">
                    @csrf

                    <div class="form-section">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="first_name"
                                    class="form-label required-field">{{ __('customer.First_Name') }}</label>
                                <input type="text" name="first_name"
                                    class="form-control @error('first_name') is-invalid @enderror" id="first_name"
                                    placeholder="{{ __('customer.First_Name') }}" value="{{ old('first_name') }}"
                                    required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="last_name"
                                    class="form-label required-field">{{ __('customer.Last_Name') }}</label>
                                <input type="text" name="last_name"
                                    class="form-control @error('last_name') is-invalid @enderror" id="last_name"
                                    placeholder="{{ __('customer.Last_Name') }}" value="{{ old('last_name') }}"
                                    required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="email" class="form-label">{{ __('customer.Email') }}</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" placeholder="{{ __('customer.Email') }}" value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="phone" class="form-label required-field">{{ __('customer.Phone') }}</label>
                                <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                    id="phone" placeholder="{{ __('customer.Phone') }}" value="{{ old('phone') }}"
                                    required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-group">
                            <label for="address" class="form-label">{{ __('customer.Address') }}</label>
                            <textarea name="address" class="form-control @error('address') is-invalid @enderror"
                                id="address" placeholder="{{ __('customer.Address') }}"
                                rows="3">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-group">
                            <label for="avatar" class="form-label">{{ __('customer.Avatar') }}</label>

                            <div class="avatar-preview" id="avatarPreview">
                                <img src="" alt="Avatar preview" class="img-preview">
                            </div>

                            <div class="avatar-placeholder" id="avatarPlaceholder">
                                <div>{{ __('customer.No_Avatar_Selected') }}</div>
                            </div>

                            <div class="custom-file" style="margin-top: 1rem;">
                                <input type="file" class="custom-file-input @error('avatar') is-invalid @enderror"
                                    name="avatar" id="avatar" accept="image/*">
                                <label class="custom-file-label" for="avatar" id="avatarLabel">
                                    {{ __('customer.Choose_file') }}
                                </label>
                                @error('avatar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-hint">{{ __('customer.Avatar_Hint') }}</div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('customers.index') }}" class="btn btn-danger">
                            <i class="fas fa-times"></i> {{ __('common.Cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-user-plus"></i> {{ __('common.Create') }}
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

            // Avatar preview functionality
            const avatarInput = document.getElementById('avatar');
            const avatarPreview = document.getElementById('avatarPreview');
            const avatarPlaceholder = document.getElementById('avatarPlaceholder');
            const avatarLabel = document.getElementById('avatarLabel');
            const previewImg = document.querySelector('.img-preview');

            avatarInput.addEventListener('change', function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        previewImg.src = e.target.result;
                        avatarPreview.style.display = 'block';
                        avatarPlaceholder.style.display = 'none';
                    }
                    reader.readAsDataURL(file);
                    avatarLabel.textContent = file.name;
                } else {
                    avatarPreview.style.display = 'none';
                    avatarPlaceholder.style.display = 'flex';
                    avatarLabel.textContent = '{{ __("customer.Choose_file") }}';
                }
            });

            // Form submission handling
            const customerForm = document.getElementById('customerForm');
            const submitBtn = document.getElementById('submitBtn');

            customerForm.addEventListener('submit', function () {
                // Add loading state to submit button
                submitBtn.classList.add('btn-loading');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner"></i> {{ __("common.Creating") }}';
            });

            // Phone number formatting
            const phoneInput = document.getElementById('phone');
            phoneInput.addEventListener('input', function () {
                // Remove non-numeric characters
                this.value = this.value.replace(/(?!^)\+/g, '').replace(/[^0-9+]/g, '');
            });

            // Email validation hint
            const emailInput = document.getElementById('email');
            emailInput.addEventListener('blur', function () {
                if (this.value && !this.checkValidity()) {
                    this.classList.add('is-invalid');
                } else {
                    this.classList.remove('is-invalid');
                }
            });

            // Real-time validation for required fields
            const requiredFields = document.querySelectorAll('input[required]');
            requiredFields.forEach(field => {
                field.addEventListener('blur', function () {
                    if (!this.value) {
                        this.classList.add('is-invalid');
                    } else {
                        this.classList.remove('is-invalid');
                    }
                });
            });
        });
    </script>
@endsection