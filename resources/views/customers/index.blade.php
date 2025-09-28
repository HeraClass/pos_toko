@extends('layouts.admin')

@section('title', __('customer.Customer_List'))
@section('content-header', __('customer.Customer_List'))
@section('content-actions')
    <div style="display: flex; align-items: center; gap: 1rem;">
        <div class="search-input" style="min-width: 250px;">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="{{ __('customer.Search_Customers') }}"
                onkeyup="filterCustomers()">
        </div>
        <a href="{{route('customers.create')}}" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> {{ __('customer.Add_Customer') }}
        </a>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
    <style>
        .customers-container {
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
            display: flex;
            justify-content: between;
            align-items: center;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #2d3748;
            margin: 0;
        }

        .card-body {
            padding: 0;
        }

        .customers-table {
            width: 100%;
            border-collapse: collapse;
        }

        .customers-table th {
            background-color: #f7fafc;
            padding: 1rem 1.25rem;
            text-align: left;
            font-weight: 600;
            color: #4a5568;
            border-bottom: 2px solid #e2e8f0;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .customers-table td {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
            color: #4a5568;
        }

        .customers-table tr:last-child td {
            border-bottom: none;
        }

        .customers-table tr:hover {
            background-color: #f8f9fa;
        }

        .avatar-container {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid #e2e8f0;
            transition: transform 0.3s ease;
        }

        .customers-table tr:hover .avatar-container {
            transform: scale(1.05);
        }

        .avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .customer-name {
            font-weight: 500;
            color: #2d3748;
        }

        .customer-email {
            color: #4a5568;
            font-size: 0.9rem;
        }

        .customer-phone {
            font-weight: 500;
            color: #2d3748;
        }

        .customer-address {
            color: #4a5568;
            font-size: 0.9rem;
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn-action {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-edit {
            background-color: #ebf8ff;
            color: #3182ce;
        }

        .btn-edit:hover {
            background-color: #bee3f8;
            transform: translateY(-1px);
        }

        .btn-delete {
            background-color: #fed7d7;
            color: #e53e3e;
        }

        .btn-delete:hover {
            background-color: #feb2b2;
            transform: translateY(-1px);
        }

        .pagination-container {
            display: flex;
            justify-content: center;
            padding: 1.5rem;
            background: white;
            border-top: 1px solid #e2e8f0;
        }

        .pagination {
            display: flex;
            gap: 0.5rem;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .page-item {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .page-link {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            color: #4a5568;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .page-link:hover {
            background-color: #f7fafc;
            border-color: #cbd5e0;
        }

        .page-item.active .page-link {
            background-color: #4361ee;
            border-color: #4361ee;
            color: white;
        }

        .page-item.disabled .page-link {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #a0aec0;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-state p {
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
        }

        .search-input {
            position: relative;
        }

        .search-input input {
            width: 100%;
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.9rem;
            height: 38px;
            transition: all 0.3s ease;
        }

        .search-input input:focus {
            outline: none;
            border-color: #4361ee;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        }

        .search-input i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
        }

        @media (max-width: 768px) {
            .customers-table-container {
                margin: 0 -1rem;
            }

            .customers-table {
                min-width: 800px;
            }

            .search-input {
                min-width: 100%;
                margin-bottom: 1rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-action {
                width: 32px;
                height: 32px;
            }

            @section('content-actions')
                <div style="display: flex; flex-direction: column; gap: 1rem; width: 100%;"><div class="search-input"><i class="fas fa-search"></i><input type="text" id="searchInput" placeholder="{{ __('customer.Search_Customers') }}"
                onkeyup="filterCustomers()"></div><a href="{{route('customers.create')}}" class="btn btn-primary" style="align-self: flex-start;"><i class="fas fa-user-plus"></i>
                {{ __('customer.Add_Customer') }}
            </a></div>@endsection
        }

        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
        }

        .created-at {
            color: #718096;
            font-size: 0.875rem;
        }

        /* Style untuk header actions */
        .card-header .card-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .btn i {
            font-size: 14px;   /* kecilin ukuran icon */
            margin-right: 6px; /* kasih jarak biar gak nempel teks */
            padding: auto
        }
    </style>
@endsection

@section('content')
    <div class="customers-container">
        <div class="card">
            <div class="card-body">
                <!-- Customers Table -->
                <div class="table-responsive">
                    <table class="customers-table">
                        <thead>
                            <tr>
                                <th>{{ __('customer.ID') }}</th>
                                <th>{{ __('customer.Avatar') }}</th>
                                <th>{{ __('customer.Name') }}</th>
                                <th>{{ __('customer.Email') }}</th>
                                <th>{{ __('customer.Phone') }}</th>
                                <th>{{ __('customer.Address') }}</th>
                                <th>{{ __('common.Created_At') }}</th>
                                <th>{{ __('customer.Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customers as $customer)
                                <tr>
                                    <td>{{$customer->id}}</td>
                                    <td>
                                        <div class="avatar-container">
                                            <img class="avatar-img" src="{{$customer->getAvatarUrl()}}"
                                                alt="{{$customer->first_name}} {{$customer->last_name}}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="customer-name">{{$customer->first_name}} {{$customer->last_name}}</div>
                                    </td>
                                    <td>
                                        <div class="customer-email">{{$customer->email}}</div>
                                    </td>
                                    <td>
                                        <div class="customer-phone">{{$customer->phone}}</div>
                                    </td>
                                    <td>
                                        <div class="customer-address" title="{{$customer->address}}">{{$customer->address}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="created-at">{{$customer->created_at->format('M d, Y')}}</div>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('customers.edit', $customer) }}" class="btn-action btn-edit"
                                                title="{{ __('customer.Edit_Customer') }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn-action btn-delete"
                                                data-url="{{route('customers.destroy', $customer)}}"
                                                title="{{ __('customer.Delete_Customer') }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            @if($customers->count() === 0)
                                <tr>
                                    <td colspan="8">
                                        <div class="empty-state">
                                            <i class="fas fa-users fa-2x"></i>
                                            <p>{{ __('customer.No_Customers_Found') }}</p>
                                            <a href="{{route('customers.create')}}" class="btn btn-primary">
                                                <i class="fas fa-user-plus fa-sm"></i> {{ __('customer.Add_Customer') }}
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($customers->count() > 0)
                    <div class="pagination-container">
                        {{ $customers->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // SweetAlert delete confirmation
            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', function () {
                    const url = this.dataset.url;
                    const customerName = this.closest('tr').querySelector('.customer-name').textContent;

                    Swal.fire({
                        title: '{{ __("customer.sure") }}',
                        text: '{{ __("customer.really_delete") }}: ' + customerName + '?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#4361ee',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: '{{ __("customer.yes_delete") }}',
                        cancelButtonText: '{{ __("customer.No") }}',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(url, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                }
                            })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire({
                                            title: '{{ __("customer.Deleted") }}',
                                            text: '{{ __("customer.Deleted_Message") }}',
                                            icon: 'success',
                                            confirmButtonColor: '#4361ee'
                                        });

                                        // Fade out and remove the row
                                        const row = button.closest('tr');
                                        row.style.opacity = 0;
                                        setTimeout(() => row.remove(), 500);
                                    }
                                })
                                .catch(error => {
                                    Swal.fire({
                                        title: '{{ __("customer.Error") }}',
                                        text: '{{ __("customer.Delete_Error") }}',
                                        icon: 'error',
                                        confirmButtonColor: '#4361ee'
                                    });
                                });
                        }
                    });
                });
            });

            // Enhance pagination styling
            const pagination = document.querySelector('.pagination');
            if (pagination) {
                pagination.classList.add('pagination');
                pagination.querySelectorAll('li').forEach(li => {
                    li.classList.add('page-item');
                    const link = li.querySelector('a, span');
                    if (link) {
                        link.classList.add('page-link');
                        if (li.classList.contains('active')) {
                            link.classList.add('active');
                        }
                        if (li.classList.contains('disabled')) {
                            link.classList.add('disabled');
                        }
                    }
                });
            }
        });

        function filterCustomers() {
            const searchText = document.getElementById('searchInput').value.toLowerCase();

            document.querySelectorAll('.customers-table tbody tr').forEach(row => {
                if (row.querySelector('.empty-state')) return;

                const name = row.querySelector('.customer-name').textContent.toLowerCase();
                const email = row.querySelector('.customer-email').textContent.toLowerCase();
                const phone = row.querySelector('.customer-phone').textContent.toLowerCase();
                const address = row.querySelector('.customer-address').textContent.toLowerCase();

                const nameMatch = name.includes(searchText);
                const emailMatch = email.includes(searchText);
                const phoneMatch = phone.includes(searchText);
                const addressMatch = address.includes(searchText);

                row.style.display = (nameMatch || emailMatch || phoneMatch || addressMatch) ? '' : 'none';
            });
        }
    </script>
@endsection