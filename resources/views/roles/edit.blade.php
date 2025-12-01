@extends('layouts.admin')

@section('title', __('role.Edit_Role'))
@section('content-header', __('role.Edit_Role'))

@section('css')
    <style>
        .permission-form-container {
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
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.9rem;
        }

        .form-control.is-invalid {
            border-color: #e53e3e;
        }

        .invalid-feedback {
            color: #e53e3e;
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
            margin-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
            padding-top: 1rem;
        }

        .btn-primary {
            background-color: #4361ee;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1.5rem;
        }

        .btn-secondary {
            background-color: #6b7280;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1.5rem;
        }

        .permissions-checkboxes {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .permissions-checkboxes label {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            border: 1px solid #d1d5db;
            padding: 0.5rem;
            border-radius: 8px;
            cursor: pointer;
        }

        .permissions-checkboxes input[type="checkbox"] {
            accent-color: #4361ee;
        }
    </style>
@endsection

@section('content')
    <div class="permission-form-container">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('roles.update', $role->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="name" class="form-label required-field">Role Name</label>
                        <input type="text" name="name" id="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $role->name) }}" placeholder="Enter role name" required maxlength="255">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Permissions</label>
                        <div class="permissions-checkboxes">
                            @foreach ($permissions as $permission)
                                <label>
                                    <input type="checkbox" name="permissions[]"
                                           value="{{ $permission->name }}"
                                           {{ in_array($permission->name, $role->permissions->pluck('name')->toArray()) ? 'checked' : '' }}>
                                    {{ $permission->name }}
                                </label>
                            @endforeach
                        </div>
                        @error('permissions')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
