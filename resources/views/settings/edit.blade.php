@extends('layouts.admin')

@section('title', __('settings.Update_Settings'))
@section('content-header', __('settings.Update_Settings'))

@section('css')
    <style>
        .settings-container {
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
        
        .settings-section {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: #f8fafc;
            border-radius: 8px;
            border-left: 4px solid #4361ee;
        }
        
        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .section-title i {
            color: #4361ee;
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
        
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-1px);
        }
        
        .current-value {
            font-size: 0.8rem;
            color: #6b7280;
            margin-top: 0.25rem;
            padding: 0.25rem 0.5rem;
            background: #f1f5f9;
            border-radius: 4px;
            display: inline-block;
        }
        
        .form-hint {
            font-size: 0.8rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }
        
        .currency-preview {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
            padding: 0.5rem;
            background: #f0fff4;
            border: 1px solid #c6f6d5;
            border-radius: 6px;
            font-size: 0.9rem;
        }
        
        .currency-preview span {
            font-weight: 600;
            color: #22543d;
        }
        
        .warning-preview {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
            padding: 0.5rem;
            background: #fffaf0;
            border: 1px solid #feebcb;
            border-radius: 6px;
            font-size: 0.9rem;
        }
        
        .warning-preview span {
            font-weight: 600;
            color: #b45309;
        }
        
        .app-info-preview {
            margin-top: 1rem;
            padding: 1rem;
            background: white;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }
        
        .app-name-preview {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.5rem;
        }
        
        .app-description-preview {
            color: #6b7280;
            font-size: 0.9rem;
            line-height: 1.4;
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
            
            .settings-section {
                padding: 1rem;
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
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .required-field::after {
            content: " *";
            color: #e53e3e;
        }
    </style>
@endsection

@section('content')
    <div class="settings-container">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('settings.store') }}" method="post" id="settingsForm">
                    @csrf
                    
                    <!-- Application Settings Section -->
                    <div class="settings-section">
                        <h4 class="section-title">
                            <i class="fas fa-cog"></i>
                            {{ __('settings.Application_Settings') }}
                        </h4>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="app_name" class="form-label required-field">
                                    {{ __('settings.app_name') }}
                                </label>
                                <input type="text" name="app_name" class="form-control @error('app_name') is-invalid @enderror" 
                                    id="app_name" placeholder="{{ __('settings.App_Name') }}" 
                                    value="{{ old('app_name', config('settings.app_name')) }}" required>
                                <div class="form-hint">{{ __('settings.App_Name_Hint') }}</div>
                                @error('app_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="app_description" class="form-label">
                                    {{ __('settings.app_description') }}
                                </label>
                                <textarea name="app_description" class="form-control @error('app_description') is-invalid @enderror" 
                                    id="app_description" placeholder="{{ __('settings.app_description') }}" 
                                    rows="4">{{ old('app_description', config('settings.app_description')) }}</textarea>
                                <div class="form-hint">{{ __('settings.App_Description_Hint') }}</div>
                                @error('app_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Financial Settings Section -->
                    <div class="settings-section">
                        <h4 class="section-title">
                            <i class="fas fa-money-bill-wave"></i>
                            {{ __('settings.Financial_Settings') }}
                        </h4>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="currency_symbol" class="form-label required-field">
                                    {{ __('settings.Currency_symbol') }}
                                </label>
                                <input type="text" name="currency_symbol" class="form-control @error('currency_symbol') is-invalid @enderror" 
                                    id="currency_symbol" placeholder="{{ __('settings.Currency_Symbol') }}" 
                                    value="{{ old('currency_symbol', config('settings.currency_symbol')) }}" 
                                    maxlength="5" required>
                                <div class="form-hint">{{ __('settings.Currency_Symbol_Hint') }}</div>
                                @error('currency_symbol')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                
                                <!-- Currency Preview -->
                                <div class="currency-preview">
                                    <i class="fas fa-eye"></i>
                                    {{ __('settings.Preview') }}: 
                                    <span id="currencyPreview">{{ old('currency_symbol', config('settings.currency_symbol')) }}</span> 
                                    100.00
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Inventory Settings Section -->
                    <div class="settings-section">
                        <h4 class="section-title">
                            <i class="fas fa-boxes"></i>
                            {{ __('settings.Inventory_Settings') }}
                        </h4>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="warning_quantity" class="form-label required-field">
                                    {{ __('settings.warning_quantity') }}
                                </label>
                                <input type="number" name="warning_quantity" class="form-control @error('warning_quantity') is-invalid @enderror" 
                                    id="warning_quantity" placeholder="{{ __('settings.Warning_Quantity') }}" 
                                    value="{{ old('warning_quantity', config('settings.warning_quantity')) }}" 
                                    min="1" max="1000" required>
                                <div class="form-hint">{{ __('settings.Warning_Quantity_Hint') }}</div>
                                @error('warning_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                
                                <!-- Warning Quantity Preview -->
                                <div class="warning-preview">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    {{ __('settings.Warning_Preview') }}: 
                                    <span id="warningQuantityPreview">{{ old('warning_quantity', config('settings.warning_quantity')) }}</span> 
                                    {{ __('settings.units') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <a href="{{ route('home') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> {{ __('common.Cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save"></i> {{ __('settings.Update_Settings') }}
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
            // Real-time preview updates
            const appNameInput = document.getElementById('app_name');
            const appDescriptionInput = document.getElementById('app_description');
            const currencySymbolInput = document.getElementById('currency_symbol');
            const warningQuantityInput = document.getElementById('warning_quantity');
            
            const appNamePreview = document.getElementById('appNamePreview');
            const appDescriptionPreview = document.getElementById('appDescriptionPreview');
            const currencyPreview = document.getElementById('currencyPreview');
            const warningQuantityPreview = document.getElementById('warningQuantityPreview');
            
            // App Name Preview
            appNameInput.addEventListener('input', function() {
                appNamePreview.textContent = this.value || '{{ config('settings.app_name') }}';
            });
            
            // App Description Preview
            appDescriptionInput.addEventListener('input', function() {
                appDescriptionPreview.textContent = this.value || '{{ config('settings.app_description') }}';
            });
            
            // Currency Symbol Preview
            currencySymbolInput.addEventListener('input', function() {
                currencyPreview.textContent = this.value || '{{ config('settings.currency_symbol') }}';
            });
            
            // Warning Quantity Preview
            warningQuantityInput.addEventListener('input', function() {
                warningQuantityPreview.textContent = this.value || '{{ config('settings.warning_quantity') }}';
            });
            
            // Form submission handling
            const settingsForm = document.getElementById('settingsForm');
            const submitBtn = document.getElementById('submitBtn');
            
            settingsForm.addEventListener('submit', function() {
                // Add loading state to submit button
                submitBtn.classList.add('btn-loading');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner"></i> {{ __("settings.updating") }}';
            });
            
            // Input validation
            currencySymbolInput.addEventListener('input', function() {
                // Limit to 5 characters
                if (this.value.length > 5) {
                    this.value = this.value.slice(0, 5);
                }
            });
            
            warningQuantityInput.addEventListener('input', function() {
                // Ensure positive number
                if (this.value < 1) {
                    this.value = 1;
                }
                if (this.value > 1000) {
                    this.value = 1000;
                }
            });
            
            // Initialize previews
            appNameInput.dispatchEvent(new Event('input'));
            appDescriptionInput.dispatchEvent(new Event('input'));
            currencySymbolInput.dispatchEvent(new Event('input'));
            warningQuantityInput.dispatchEvent(new Event('input'));
        });
    </script>
@endsection