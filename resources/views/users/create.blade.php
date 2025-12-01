@extends('layouts.admin')

@section('title', __('Create User'))
@section('content-header', __('Create User'))

@section('css')
    <style>
        .user-form-container {
            padding: 0.5rem;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .card-body {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #374151;
            font-size: 0.9rem;
        }

        .required-field::after {
            content: " *";
            color: #e53e3e;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            background-color: white;
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
            color: #e53e3e;
            font-size: 0.8rem;
            margin-top: 0.25rem;
            display: block;
        }

        .form-text {
            color: #6b7280;
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 2rem;
            border-top: 1px solid #e2e8f0;
            padding-top: 1.5rem;
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
            text-decoration: none;
            min-height: 44px;
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

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .form-actions {
                flex-direction: column-reverse;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        .password-toggle {
            position: relative;
        }

        .password-toggle-icon {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6b7280;
            transition: color 0.3s ease;
        }

        .password-toggle-icon:hover {
            color: #4361ee;
        }

        .role-options {
            display: grid;
            gap: 0.75rem;
            margin-top: 0.5rem;
        }

        .role-option {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            background-color: white;
        }

        .role-option:hover {
            border-color: #4361ee;
            background-color: #f8fafc;
        }

        .role-option.selected {
            border-color: #4361ee;
            background-color: #4361ee;
            color: white;
        }

        .role-option input[type="radio"] {
            margin-right: 0.75rem;
            transform: scale(1.1);
        }

        .role-option .role-name {
            font-weight: 500;
            flex: 1;
        }

        .role-option .role-description {
            font-size: 0.8rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }

        .role-option.selected .role-description {
            color: rgba(255, 255, 255, 0.8);
        }
    </style>
@endsection

@section('content')
    <div class="user-form-container">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf

                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name" class="form-label required-field">First Name</label>
                            <input type="text" name="first_name" id="first_name" 
                                class="form-control @error('first_name') is-invalid @enderror"
                                value="{{ old('first_name') }}" 
                                placeholder="Enter first name" 
                                required 
                                maxlength="100">
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="last_name" class="form-label required-field">Last Name</label>
                            <input type="text" name="last_name" id="last_name" 
                                class="form-control @error('last_name') is-invalid @enderror"
                                value="{{ old('last_name') }}" 
                                placeholder="Enter last name" 
                                required 
                                maxlength="100">
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label required-field">Email Address</label>
                        <input type="email" name="email" id="email" 
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" 
                            placeholder="Enter email address" 
                            required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">The email must be unique and will be used for login.</div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label required-field">Password</label>
                        <div class="password-toggle">
                            <input type="password" name="password" id="password" 
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Enter password" 
                                required 
                                minlength="6">
                            <i class="fas fa-eye password-toggle-icon" id="togglePassword"></i>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Password must be at least 6 characters long.</div>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label required-field">Confirm Password</label>
                        <div class="password-toggle">
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                class="form-control"
                                placeholder="Confirm password" 
                                required 
                                minlength="6">
                            <i class="fas fa-eye password-toggle-icon" id="togglePasswordConfirmation"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label required-field">Role</label>
                        <div class="role-options">
                            @foreach($roles as $role)
                                <label class="role-option {{ old('role') == $role->name ? 'selected' : '' }}">
                                    <input type="radio" name="role" value="{{ $role->name }}" 
                                        {{ old('role') == $role->name ? 'checked' : '' }} required>
                                    <div>
                                        <div class="role-name">{{ $role->name }}</div>
                                        <div class="role-description">
                                            {{ $role->description ?? 'No description available' }}
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('role')
                            <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus-circle"></i> Create User
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
            // Password toggle functionality
            const togglePassword = document.getElementById('togglePassword');
            const togglePasswordConfirmation = document.getElementById('togglePasswordConfirmation');
            const password = document.getElementById('password');
            const passwordConfirmation = document.getElementById('password_confirmation');

            togglePassword.addEventListener('click', function () {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });

            togglePasswordConfirmation.addEventListener('click', function () {
                const type = passwordConfirmation.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordConfirmation.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });

            // Role selection styling
            const roleOptions = document.querySelectorAll('.role-option');
            roleOptions.forEach(option => {
                option.addEventListener('click', function () {
                    // Remove selected class from all options
                    roleOptions.forEach(opt => opt.classList.remove('selected'));
                    // Add selected class to clicked option
                    this.classList.add('selected');
                    // Check the radio button
                    const radio = this.querySelector('input[type="radio"]');
                    radio.checked = true;
                });
            });

            // Form validation enhancement
            const form = document.querySelector('form');
            form.addEventListener('submit', function (e) {
                const password = document.getElementById('password').value;
                const passwordConfirmation = document.getElementById('password_confirmation').value;
                
                if (password !== passwordConfirmation) {
                    e.preventDefault();
                    alert('Password and confirmation password do not match.');
                    return false;
                }
            });
        });
    </script>
@endsection