@extends('layouts.admin')

@section('title', __('customer.Update_Customer'))
@section('content-header', __('customer.Update_Customer'))

@section('css')
    <style>
        .customer-edit-container {
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

        .avatar-section {
            display: flex;
            align-items: flex-start;
            gap: 2rem;
            margin-bottom: 1.5rem;
        }

        .current-avatar {
            text-align: center;
        }

        .current-avatar img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .avatar-preview {
            display: none;
            text-align: center;
        }

        .avatar-preview img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #4361ee;
            box-shadow: 0 2px 12px rgba(67, 97, 238, 0.3);
        }

        .avatar-controls {
            flex: 1;
        }

        .avatar-label {
            font-size: 0.8rem;
            color: #6b7280;
            text-align: center;
            margin-top: 0.5rem;
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

            .avatar-section {
                flex-direction: column;
                align-items: center;
                gap: 1rem;
            }

            .current-avatar img,
            .avatar-preview img {
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

        .customer-info-badge {
            display: inline-block;
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
            color: #4a5568;
            margin-top: 0.5rem;
        }
    </style>
@endsection

@section('content')
    <div class="customer-edit-container">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('customers.update', $customer) }}" method="POST" enctype="multipart/form-data"
                    id="customerForm">
                    @csrf
                    @method('PUT')

                    <div class="form-section">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="first_name"
                                    class="form-label required-field">{{ __('customer.First_Name') }}</label>
                                <input type="text" name="first_name"
                                    class="form-control @error('first_name') is-invalid @enderror" id="first_name"
                                    placeholder="{{ __('customer.First_Name') }}"
                                    value="{{ old('first_name', $customer->first_name) }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="last_name"
                                    class="form-label required-field">{{ __('customer.Last_Name') }}</label>
                                <input type="text" name="last_name"
                                    class="form-control @error('last_name') is-invalid @enderror" id="last_name"
                                    placeholder="{{ __('customer.Last_Name') }}"
                                    value="{{ old('last_name', $customer->last_name) }}" required>
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
                                    id="email" placeholder="{{ __('customer.Email') }}"
                                    value="{{ old('email', $customer->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="phone" class="form-label required-field">{{ __('customer.Phone') }}</label>
                                <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                    id="phone" placeholder="{{ __('customer.Phone') }}"
                                    value="{{ old('phone', $customer->phone) }}" required>
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
                                rows="3">{{ old('address', $customer->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="avatar-section">
                            <div class="current-avatar">
                                <img src="{{ $customer->getAvatarUrl() }}" alt="Current avatar" id="currentAvatar">
                                <div class="avatar-label">{{ __('customer.Current_Avatar') }}</div>
                            </div>

                            <div class="avatar-preview" id="avatarPreview">
                                <img src="" alt="New avatar preview" class="img-preview">
                                <div class="avatar-label">{{ __('customer.New_Avatar_Preview') }}</div>
                            </div>

                            <div class="avatar-controls">
                                <div class="form-group">
                                    <label for="avatar" class="form-label">{{ __('customer.Avatar') }}</label>
                                    <div class="custom-file">
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
                                    <button type="button" class="btn btn-outline-danger btn-sm mt-2" id="removeAvatar"
                                        style="display: none;">
                                        <i class="fas fa-trash"></i> {{ __('customer.Remove_Avatar') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('customers.index') }}" class="btn btn-secondary">
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
    <script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize custom file input
            bsCustomFileInput.init();

            // Avatar preview functionality
            const avatarInput = document.getElementById('avatar');
            const avatarPreview = document.getElementById('avatarPreview');
            const currentAvatar = document.getElementById('currentAvatar');
            const avatarLabel = document.getElementById('avatarLabel');
            const removeAvatarBtn = document.getElementById('removeAvatar');
            const previewImg = document.querySelector('.img-preview');

            avatarInput.addEventListener('change', function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        previewImg.src = e.target.result;
                        avatarPreview.style.display = 'block';
                        removeAvatarBtn.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                    avatarLabel.textContent = file.name;
                } else {
                    avatarPreview.style.display = 'none';
                    removeAvatarBtn.style.display = 'none';
                    avatarLabel.textContent = '{{ __("customer.Choose_new_file") }}';
                }
            });

            // Remove avatar functionality
            removeAvatarBtn.addEventListener('click', function () {
                avatarInput.value = '';
                avatarPreview.style.display = 'none';
                this.style.display = 'none';
                avatarLabel.textContent = '{{ __("customer.Choose_new_file") }}';

                // Create hidden input to indicate avatar removal
                if (!document.getElementById('remove_avatar_flag')) {
                    const removeFlag = document.createElement('input');
                    removeFlag.type = 'hidden';
                    removeFlag.name = 'remove_avatar';
                    removeFlag.value = '1';
                    removeFlag.id = 'remove_avatar_flag';
                    document.getElementById('customerForm').appendChild(removeFlag);
                }
            });

            // Form submission handling
            const customerForm = document.getElementById('customerForm');
            const submitBtn = document.getElementById('submitBtn');

            customerForm.addEventListener('submit', function () {
                // Add loading state to submit button
                submitBtn.classList.add('btn-loading');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner"></i> {{ __("common.Updating") }}';
            });

            // Phone number formatting
            const phoneInput = document.getElementById('phone');
            phoneInput.addEventListener('input', function () {
                // Remove non-numeric characters
                this.value = this.value.replace(/[^\d+]/g, '');
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

            // Show current customer data for reference
            console.log('Current customer data:', {
                id: {{ $customer->id }},
                name: '{{ $customer->first_name }} {{ $customer->last_name }}',
                email: '{{ $customer->email }}',
                phone: '{{ $customer->phone }}'
            });
        });
    </script>
@endsection