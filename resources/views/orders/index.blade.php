@extends('layouts.admin')

@section('title', __('order.Orders_List'))
@section('content-header', __('order.Orders_List'))
@section('content-actions')
    <a href="{{route('cart.index')}}" class="btn btn-primary">
        <i class="fas fa-shopping-cart"></i> {{ __('cart.title') }}
    </a>
@endsection

@section('css')
    <style>
        .orders-container {
            padding: 0.5rem;
        }

        .filter-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .filter-form .row {
            align-items: end;
        }

        .date-input {
            position: relative;
        }

        .date-input i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .date-input input {
            padding-left: 40px;
        }

        .btn-filter {
            height: 38px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 16px;
        }

        .orders-table-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .table-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            background: #f8f9fa;
        }

        .table-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2d3748;
            margin: 0;
        }

        .orders-table {
            width: 100%;
            border-collapse: collapse;
        }

        .orders-table th {
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

        .orders-table td {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
            color: #4a5568;
        }

        .orders-table tr:last-child td {
            border-bottom: none;
        }

        .orders-table tr:hover {
            background-color: #f8f9fa;
        }

        .status-badge {
            padding: 0.5rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }

        .badge-not-paid {
            background-color: #fed7d7;
            color: #c53030;
        }

        .badge-partial {
            background-color: #feebcb;
            color: #b45309;
        }

        .badge-paid {
            background-color: #c6f6d5;
            color: #22543d;
        }

        .badge-change {
            background-color: #bee3f8;
            color: #2c5282;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .btn-action {
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            border: none;
            font-size: 0.875rem;
            gap: 0.25rem;
        }

        .btn-view {
            background-color: #ebf8ff;
            color: #3182ce;
        }

        .btn-view:hover {
            background-color: #bee3f8;
            transform: translateY(-1px);
        }

        .btn-pay {
            background-color: #c6f6d5;
            color: #22543d;
        }

        .btn-pay:hover {
            background-color: #9ae6b4;
            transform: translateY(-1px);
        }

        .table-footer {
            background-color: #f7fafc;
            padding: 1rem 1.25rem;
            border-top: 2px solid #e2e8f0;
            font-weight: 600;
        }

        .table-footer td {
            border-bottom: none !important;
        }

        .text-total {
            font-weight: 700;
            color: #2d3748;
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

        @media (max-width: 768px) {
            .orders-table-container {
                margin: 0 -1rem;
                border-radius: 0;
            }

            .orders-table {
                min-width: 1000px;
            }

            .filter-form .row {
                flex-direction: column;
                gap: 1rem;
            }

            .date-input,
            .btn-filter {
                width: 100%;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-action {
                width: 100%;
                justify-content: flex-start;
            }
        }

        .customer-name {
            font-weight: 500;
            color: #2d3748;
        }

        .amount-cell {
            font-weight: 600;
            color: #2d3748;
        }

        .to-pay-cell {
            font-weight: 700;
        }

        .to-pay-positive {
            color: #e53e3e;
        }

        .to-pay-zero {
            color: #38a169;
        }

        .to-pay-negative {
            color: #3182ce;
        }
    </style>
@endsection

@section('content')
    <div class="orders-container">
        <!-- Filter Card -->
        <div class="filter-card">
            <form action="{{route('orders.index')}}" class="filter-form">
                <div class="row">
                    <div class="col-md-3">
                        <label for="start date" class="form-label">{{ __('order.start_date') }}</label>
                        <div class="form-group date-input">
                            <i class="fas fa-calendar-alt"></i>
                            <input type="date" name="start_date" class="form-control" value="{{request('start_date')}}"
                                placeholder="{{ __('order.Start_Date') }}" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="end date" class="form-label">{{ __('order.end_date') }}</label>
                        <div class="form-group date-input">
                            <i class="fas fa-calendar-alt"></i>
                            <input type="date" name="end_date" class="form-control" value="{{request('end_date')}}"
                                placeholder="{{ __('order.End_Date') }}" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-primary btn-filter" type="submit">
                            <i class="fas fa-filter"></i> {{ __('order.filter') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Orders Table -->
        <div class="orders-table-container">
            <div class="table-responsive">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>{{ __('order.ID') }}</th>
                            <th>{{ __('order.Customer_Name') }}</th>
                            <th>{{ __('order.Total') }}</th>
                            <th>{{ __('order.Received_Amount') }}</th>
                            <th>{{ __('order.Status') }}</th>
                            <th>{{ __('order.To_Pay') }}</th>
                            <th>{{ __('order.Created_At') }}</th>
                            <th>{{ __('order.Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>{{$order->id}}</td>
                                <td>
                                    <span class="customer-name">{{$order->getCustomerName()}}</span>
                                </td>
                                <td class="amount-cell">
                                    {{ config('settings.currency_symbol') }} {{ number_format($order->total(), 2) }}
                                </td>
                                <td class="amount-cell">
                                    {{ config('settings.currency_symbol') }} {{ number_format($order->receivedAmount(), 2) }}
                                </td>
                                <td>
                                    @if($order->receivedAmount() == 0)
                                        <span class="status-badge badge-not-paid">{{ __('order.Not_Paid') }}</span>
                                    @elseif($order->receivedAmount() < $order->total())
                                        <span class="status-badge badge-partial">{{ __('order.Partial') }}</span>
                                    @elseif($order->receivedAmount() == $order->total())
                                        <span class="status-badge badge-paid">{{ __('order.Paid') }}</span>
                                    @elseif($order->receivedAmount() > $order->total())
                                        <span class="status-badge badge-change">{{ __('order.Change') }}</span>
                                    @endif
                                </td>
                                <td
                                    class="to-pay-cell {{ $order->total() - $order->receivedAmount() > 0 ? 'to-pay-positive' : ($order->total() - $order->receivedAmount() == 0 ? 'to-pay-zero' : 'to-pay-negative') }}">
                                    {{config('settings.currency_symbol')}}
                                    {{number_format($order->total() - $order->receivedAmount(), 2)}}
                                </td>
                                <td>{{$order->created_at->format('M d, Y H:i')}}</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-action btn-view btnShowInvoice" data-toggle="modal"
                                            data-target="#modalInvoice" data-order-id="{{ $order->id }}"
                                            data-customer-name="{{ $order->getCustomerName() }}"
                                            data-total="{{ $order->total() }}" data-received="{{ $order->receivedAmount() }}"
                                            data-items="{{ json_encode($order->items) }}"
                                            data-created-at="{{ $order->created_at }}"
                                            data-payment="{{ isset($order->payments) && count($order->payments) > 0 ? $order->payments[0]->amount : 0 }}"
                                            title="{{ __('order.View_Invoice') }}">
                                            <i class="fas fa-eye"></i>
                                            <span class="d-none d-md-inline">{{ __('order.View_Invoice') }}</span>
                                        </button>

                                        @if($order->total() > $order->receivedAmount())
                                            <button class="btn-action btn-pay" data-toggle="modal"
                                                data-target="#partialPaymentModal" data-orders-id="{{ $order->id }}"
                                                data-remaining-amount="{{ $order->total() - $order->receivedAmount() }}"
                                                title="{{ __('order.Pay_Partial') }}">
                                                <i class="fas fa-money-bill-wave"></i>
                                                <span class="d-none d-md-inline">{{ __('order.Pay') }}</span>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        @if($orders->count() === 0)
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <i class="fas fa-receipt"></i>
                                        <p>{{ __('order.No_Orders_Found') }}</p>
                                        <a href="{{route('cart.index')}}" class="btn btn-primary">
                                            <i class="fas fa-shopping-cart"></i> {{ __('order.Create_First_Order') }}
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        <tr class="table-footer">
                            <td colspan="2"><strong>{{ __('order.Total_Summary') }}</strong></td>
                            <td class="text-total">{{ config('settings.currency_symbol') }} {{ number_format($total, 2) }}
                            </td>
                            <td class="text-total">{{ config('settings.currency_symbol') }}
                                {{ number_format($receivedAmount, 2) }}
                            </td>
                            <td colspan="4"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Pagination -->
            @if($orders->count() > 0)
                <div class="pagination-container">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@section('model')
    <!-- Partial Payment Modal -->
    <div class="modal fade" id="partialPaymentModal" tabindex="-1" role="dialog" aria-labelledby="partialPaymentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="partialPaymentModalLabel">{{ __('order.Partial_Payment') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="partialPaymentForm" method="POST" action="{{ route('orders.partial-payment') }}">
                        @csrf
                        <input type="hidden" name="order_id" id="modalOrderId" value="">
                        <div class="form-group">
                            <label for="partialAmount">{{ __('order.Amount_To_Pay') }}</label>
                            <input type="number" class="form-control" step="0.01" id="partialAmount" name="amount"
                                min="0.01" required>
                            <small class="form-text text-muted" id="remainingAmountText"></small>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-check-circle"></i> {{ __('order.Submit_Payment') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoice Modal -->
    <div class="modal fade" id="modalInvoice" tabindex="-1" role="dialog" aria-labelledby="modalInvoiceLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalInvoiceLabel">{{ __('order.Invoice') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Dynamic content will be inserted here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> {{ __('common.Close') }}
                    </button>
                    <button type="button" class="btn btn-primary" onclick="printInvoice()">
                        <i class="fas fa-print"></i> {{ __('common.Print') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Partial Payment Modal
        $(document).ready(function () {
            $('#partialPaymentModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var orderId = button.data('orders-id');
                var remainingAmount = button.data('remaining-amount');

                var modal = $(this);
                modal.find('#modalOrderId').val(orderId);
                modal.find('#partialAmount').val(remainingAmount);
                modal.find('#partialAmount').attr('max', remainingAmount);
                modal.find('#remainingAmountText').text('{{ __("order.Remaining_Amount") }}: ' + remainingAmount);
            });

            // Prevent entering amount more than remaining
            $('#partialAmount').on('input', function () {
                var max = parseFloat($(this).attr('max'));
                var value = parseFloat($(this).val());

                if (value > max) {
                    $(this).val(max);
                }
            });
        });

        // Invoice Modal
        $(document).on('click', '.btnShowInvoice', function (event) {
            var button = $(this);
            var orderId = button.data('order-id');
            var customerName = button.data('customer-name');
            var totalAmount = button.data('total');
            var receivedAmount = button.data('received');
            var createdAt = button.data('created-at');
            var items = button.data('items');

            // Format tanggal dan waktu Indonesia
            var orderDate = new Date(createdAt);
            var formattedDate = orderDate.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            var formattedTime = orderDate.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });

            // Construct items HTML
            var itemsHTML = '';
            if (items) {
                items.forEach(function (item, index) {
                    itemsHTML += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.product?.name || 'N/A'}</td>
                            <td>${item.description || 'N/A'}</td>
                            <td>{{ config('settings.currency_symbol') }} ${parseFloat(item.product?.price || 0).toFixed(2)}</td>
                            <td>${item.quantity}</td>
                            <td>{{ config('settings.currency_symbol') }} ${(parseFloat(item.product?.price || 0) * item.quantity).toFixed(2)}</td>
                        </tr>`;
                });
            }

            // Determine status
            var statusHTML = '';
            if (receivedAmount == 0) {
                statusHTML = '<span class="badge badge-not-paid">{{ __("order.Not_Paid") }}</span>';
            } else if (receivedAmount < totalAmount) {
                statusHTML = '<span class="badge badge-partial">{{ __("order.Partial") }}</span>';
            } else if (receivedAmount == totalAmount) {
                statusHTML = '<span class="badge badge-paid">{{ __("order.Paid") }}</span>';
            } else {
                statusHTML = '<span class="badge badge-change">{{ __("order.Change") }}</span>';
            }

            // Update modal content
            var modalBody = $('#modalInvoice').find('.modal-body');
            modalBody.html(`
                <div class="invoice-container">
                    <div class="invoice-header">
                        <h4>Toko Kelontong Pak Dedi</h4>
                        <p>Point of Sale System</p>
                    </div>

                    <div class="invoice-info">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>{{ __("order.Order_ID") }}:</strong> ${orderId}</p>
                                <p><strong>{{ __("order.Date") }}:</strong> ${formattedDate}</p>
                                <p><strong>{{ __("order.Time") }}:</strong> ${formattedTime}</p>
                            </div>
                            <div class="col-md-6 text-right">
                                <p><strong>{{ __("order.Customer") }}:</strong> ${customerName || 'N/A'}</p>
                                <p><strong>{{ __("order.Status") }}:</strong> ${statusHTML}</p>
                            </div>
                        </div>
                    </div>

                    <div class="invoice-items">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __("order.Item") }}</th>
                                    <th>{{ __("order.Description") }}</th>
                                    <th>{{ __("order.Price") }}</th>
                                    <th>{{ __("order.Quantity") }}</th>
                                    <th>{{ __("order.Total") }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${itemsHTML}
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-right">{{ __("order.Total") }}:</th>
                                    <th>{{ config('settings.currency_symbol') }} ${parseFloat(totalAmount).toFixed(2)}</th>
                                </tr>
                                <tr>
                                    <th colspan="5" class="text-right">{{ __("order.Paid") }}:</th>
                                    <th>{{ config('settings.currency_symbol') }} ${parseFloat(receivedAmount).toFixed(2)}</th>
                                </tr>
                                <tr>
                                    <th colspan="5" class="text-right">{{ __("order.Balance") }}:</th>
                                    <th>{{ config('settings.currency_symbol') }} ${parseFloat(totalAmount - receivedAmount).toFixed(2)}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="invoice-footer">
                        <p class="text-center">{{ __("common.Thank_You") }}</p>
                    </div>
                </div>
                `);

            // Store current invoice data for printing
            window.currentInvoiceData = {
                orderId: orderId,
                customerName: customerName,
                totalAmount: totalAmount,
                receivedAmount: receivedAmount,
                createdAt: createdAt,
                formattedDate: formattedDate,
                formattedTime: formattedTime,
                items: items,
                statusHTML: statusHTML,
                itemsHTML: itemsHTML
            };

            $('#modalInvoice').modal('show');
        });

        // Function to print only the invoice
        function printInvoice() {
            // Create a new window for printing
            var printWindow = window.open('', '_blank');

            // Get the invoice data
            var data = window.currentInvoiceData;

            // Create print content
            var printContent = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Invoice #${data.orderId}</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            margin: 0;
                            padding: 20px;
                            color: #333;
                        }
                        .invoice-container {
                            max-width: 800px;
                            margin: 0 auto;
                            border: 1px solid #ddd;
                            padding: 20px;
                            background: white;
                        }
                        .invoice-header {
                            text-align: center;
                            margin-bottom: 20px;
                            border-bottom: 2px solid #333;
                            padding-bottom: 10px;
                        }
                        .invoice-header h2 {
                            margin: 0;
                            color: #333;
                        }
                        .invoice-header p {
                            margin: 5px 0;
                            color: #666;
                        }
                        .invoice-info {
                            margin-bottom: 20px;
                        }
                        .invoice-info .row {
                            display: flex;
                            justify-content: space-between;
                        }
                        .invoice-info p {
                            margin: 5px 0;
                        }
                        .invoice-items {
                            width: 100%;
                            border-collapse: collapse;
                            margin-bottom: 20px;
                        }
                        .invoice-items th,
                        .invoice-items td {
                            border: 1px solid #ddd;
                            padding: 8px;
                            text-align: left;
                        }
                        .invoice-items th {
                            background-color: #f5f5f5;
                            font-weight: bold;
                        }
                        .invoice-items tfoot th {
                            text-align: right;
                            background-color: #e9ecef;
                        }
                        .invoice-footer {
                            text-align: center;
                            margin-top: 30px;
                            padding-top: 20px;
                            border-top: 1px solid #ddd;
                        }
                        .text-right {
                            text-align: right;
                        }
                        .badge {
                            padding: 4px 8px;
                            border-radius: 4px;
                            font-size: 12px;
                            font-weight: bold;
                        }
                        .badge-not-paid { background-color: #fed7d7; color: #c53030; }
                        .badge-partial { background-color: #feebcb; color: #b45309; }
                        .badge-paid { background-color: #c6f6d5; color: #22543d; }
                        .badge-change { background-color: #bee3f8; color: #2c5282; }

                        @media print {
                            body {
                                padding: 0;
                                margin: 0;
                            }
                            .invoice-container {
                                border: none;
                                padding: 0;
                            }
                            .no-print {
                                display: none !important;
                            }
                        }
                    </style>
                </head>
                <body>
                    <div class="invoice-container">
                        <div class="invoice-header">
                            <h2>Toko Kelontong Pak Dedi</h2>
                            <p>Point of Sale System</p>
                            <h3>INVOICE</h3>
                        </div>

                        <div class="invoice-info">
                            <div class="row">
                                <div>
                                    <p><strong>Order ID:</strong> ${data.orderId}</p>
                                    <p><strong>Tanggal:</strong> ${data.formattedDate}</p>
                                    <p><strong>Waktu:</strong> ${data.formattedTime}</p>
                                </div>
                                <div>
                                    <p><strong>Customer:</strong> ${data.customerName || 'N/A'}</p>
                                    <p><strong>Status:</strong> ${data.statusHTML}</p>
                                </div>
                            </div>
                        </div>

                        <table class="invoice-items">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Item</th>
                                    <th>Deskripsi</th>
                                    <th>Harga</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${data.itemsHTML}
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5">Total:</th>
                                    <th>{{ config('settings.currency_symbol') }} ${parseFloat(data.totalAmount).toFixed(2)}</th>
                                </tr>
                                <tr>
                                    <th colspan="5">Dibayar:</th>
                                    <th>{{ config('settings.currency_symbol') }} ${parseFloat(data.receivedAmount).toFixed(2)}</th>
                                </tr>
                                <tr>
                                    <th colspan="5">Sisa:</th>
                                    <th>{{ config('settings.currency_symbol') }} ${parseFloat(data.totalAmount - data.receivedAmount).toFixed(2)}</th>
                                </tr>
                            </tfoot>
                        </table>

                        <div class="invoice-footer">
                            <p>Terima kasih atas kunjungan Anda</p>
                            <p>www.tokokelontongpakdedi.com</p>
                        </div>
                    </div>

                    <script>
                        window.onload = function() {
                            window.print();
                            setTimeout(function() {
                                window.close();
                            }, 100);
                        }
                    <\/script>
                </body>
                </html>
                `;

            // Write the content to the new window
            printWindow.document.open();
            printWindow.document.write(printContent);
            printWindow.document.close();
        }
    </script>
@endsection