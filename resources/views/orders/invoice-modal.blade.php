{{-- 
    File: resources/views/orders/invoice-modal.blade.php
    Ini adalah partial view untuk ditampilkan di modal
--}}

<div class="invoice-wrapper">
    <style>
        .invoice-wrapper {
            padding: 20px;
        }

        .invoice-header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #4361ee;
        }

        .invoice-header h3 {
            margin: 0 0 5px 0;
            color: #2d3748;
            font-size: 1.8rem;
            font-weight: 600;
        }

        .invoice-header p {
            margin: 3px 0;
            color: #6b7280;
            font-size: 0.95rem;
        }

        .invoice-header .invoice-title {
            font-size: 1.3rem;
            color: #4361ee;
            font-weight: 600;
            margin-top: 10px;
        }

        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            padding: 15px;
            background: #f8fafc;
            border-radius: 8px;
        }

        .invoice-info-left,
        .invoice-info-right {
            flex: 1;
        }

        .invoice-info-right {
            text-align: right;
        }

        .invoice-info p {
            margin: 6px 0;
            font-size: 0.9rem;
            color: #4a5568;
        }

        .invoice-info strong {
            color: #2d3748;
            font-weight: 600;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-top: 3px;
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

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .invoice-table thead {
            background: #f8fafc;
        }

        .invoice-table th {
            padding: 10px;
            text-align: left;
            font-weight: 600;
            color: #4a5568;
            border-bottom: 2px solid #e2e8f0;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .invoice-table td {
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
            color: #4a5568;
            font-size: 0.9rem;
        }

        .invoice-table tbody tr:hover {
            background-color: #f7fafc;
        }

        .invoice-table tfoot {
            font-weight: 600;
            border-top: 2px solid #e2e8f0;
        }

        .invoice-table tfoot td {
            padding: 10px;
            font-size: 0.95rem;
            background-color: #f8fafc;
        }

        .text-right {
            text-align: right !important;
        }

        .total-row {
            background-color: #e6f2ff !important;
            color: #2d3748;
            font-weight: 700;
        }

        .invoice-footer {
            text-align: center;
            margin-top: 25px;
            padding-top: 15px;
            border-top: 2px solid #e2e8f0;
        }

        .invoice-footer .thank-you {
            font-size: 1.1rem;
            font-weight: 600;
            color: #4361ee;
            margin-bottom: 8px;
        }

        .invoice-footer p {
            color: #6b7280;
            margin: 3px 0;
            font-size: 0.9rem;
        }

        @media print {
            .invoice-wrapper {
                padding: 0;
            }
        }

        @media (max-width: 768px) {
            .invoice-info {
                flex-direction: column;
                gap: 15px;
            }

            .invoice-info-right {
                text-align: left;
            }

            .invoice-table {
                font-size: 0.8rem;
            }

            .invoice-table th,
            .invoice-table td {
                padding: 6px;
            }
        }
    </style>

    <!-- Invoice Header -->
    <div class="invoice-header">
        <h3>Toko Pak Dedy</h3>
        <p>Point of Sale System</p>
        <p class="invoice-title">INVOICE</p>
    </div>

    <!-- Invoice Info -->
    <div class="invoice-info">
        <div class="invoice-info-left">
            <p><strong>Order ID:</strong> #{{ $order->id }}</p>
            <p><strong>Date:</strong> {{ $order->created_at->format('l, F d, Y') }}</p>
            <p><strong>Time:</strong> {{ $order->created_at->format('H:i:s') }}</p>
        </div>
        <div class="invoice-info-right">
            <p><strong>Customer:</strong> {{ $order->getCustomerName() }}</p>
            <p><strong>Type:</strong> 
                @if($order->customer_id === null)
                    Walk-in Customer
                @else
                    Registered Customer
                @endif
            </p>
            <p><strong>Status:</strong>
                @php
                    $received = $order->receivedAmount();
                    $total = $order->total();
                @endphp
                @if($received == 0)
                    <span class="status-badge badge-not-paid">Not Paid</span>
                @elseif($received < $total)
                    <span class="status-badge badge-partial">Partial</span>
                @elseif($received == $total)
                    <span class="status-badge badge-paid">Paid</span>
                @else
                    <span class="status-badge badge-change">Change</span>
                @endif
            </p>
        </div>
    </div>

    <!-- Invoice Items Table -->
    <div class="invoice-items">
        <table class="invoice-table">
            <thead>
                <tr>
                    <th style="width: 40px;">#</th>
                    <th>Item Name</th>
                    <th>Description</th>
                    <th style="width: 100px;" class="text-right">Price</th>
                    <th style="width: 60px;" class="text-right">Qty</th>
                    <th style="width: 110px;" class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($order->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $item->product ? $item->product->name : 'N/A' }}</strong></td>
                        <td>{{ $item->product ? $item->product->name : 'Product not available' }}</td>
                        <td class="text-right">{{ config('settings.currency_symbol') }} {{ number_format($item->price, 2) }}</td>
                        <td class="text-right">{{ $item->quantity }}</td>
                        <td class="text-right">{{ config('settings.currency_symbol') }} {{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #9ca3af; padding: 30px;">
                            <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 10px; display: block;"></i>
                            No items found in this order
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="text-right"><strong>Subtotal:</strong></td>
                    <td class="text-right"><strong>{{ config('settings.currency_symbol') }} {{ number_format($order->total(), 2) }}</strong></td>
                </tr>
                <tr>
                    <td colspan="5" class="text-right"><strong>Paid Amount:</strong></td>
                    <td class="text-right"><strong>{{ config('settings.currency_symbol') }} {{ number_format($order->receivedAmount(), 2) }}</strong></td>
                </tr>
                <tr class="total-row">
                    <td colspan="5" class="text-right"><strong>Balance Due:</strong></td>
                    <td class="text-right">
                        <strong>{{ config('settings.currency_symbol') }} {{ number_format($order->total() - $order->receivedAmount(), 2) }}</strong>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Invoice Footer -->
    <div class="invoice-footer">
        <p class="thank-you">Thank You for Your Order!</p>
        <p>For questions about this invoice, please contact us</p>
        <p style="margin-top: 10px; font-size: 0.85rem; color: #9ca3af;">
            www.tokopakdedy.my.id
        </p>
    </div>
</div>