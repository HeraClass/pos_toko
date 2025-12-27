{{--
File: resources/views/orders/invoice-print.blade.php
Halaman dedicated untuk print (dibuka dari modal via button Print)
FILE INI OPTIONAL - hanya jika ingin halaman print terpisah
--}}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->id }} - Toko Kelontong Pak Dedi</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            padding: 30px;
            background: white;
            color: #333;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border: 2px solid #333;
        }

        .invoice-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #333;
        }

        .invoice-header h1 {
            font-size: 2rem;
            margin-bottom: 5px;
            color: #000;
        }

        .invoice-header p {
            color: #666;
            margin: 3px 0;
        }

        .invoice-header .invoice-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-top: 10px;
            color: #000;
        }

        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            padding: 15px;
            background: #f9f9f9;
            border: 1px solid #ddd;
        }

        .invoice-info-left,
        .invoice-info-right {
            flex: 1;
        }

        .invoice-info-right {
            text-align: right;
        }

        .invoice-info p {
            margin: 8px 0;
            font-size: 0.95rem;
        }

        .invoice-info strong {
            font-weight: bold;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .invoice-table th,
        .invoice-table td {
            border: 1px solid #333;
            padding: 10px;
            text-align: left;
        }

        .invoice-table th {
            background: #f0f0f0;
            font-weight: bold;
            text-align: left;
        }

        .invoice-table .text-right {
            text-align: right;
        }

        .invoice-table tfoot td {
            font-weight: bold;
            background: #f9f9f9;
        }

        .total-row td {
            background: #e6e6e6 !important;
            font-size: 1.1rem;
        }

        .invoice-footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #333;
        }

        .invoice-footer p {
            margin: 5px 0;
            color: #666;
        }

        .invoice-footer .thank-you {
            font-size: 1.2rem;
            font-weight: bold;
            color: #000;
            margin-bottom: 10px;
        }

        /* Print Button - Hidden saat print */
        .print-button {
            text-align: center;
            margin-bottom: 20px;
        }

        .print-button button {
            padding: 12px 30px;
            background: #4361ee;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            font-weight: bold;
        }

        .print-button button:hover {
            background: #3a56d4;
        }

        @media print {
            body {
                padding: 0;
            }

            .invoice-container {
                border: none;
                padding: 0;
            }

            .print-button {
                display: none !important;
            }

            @page {
                margin: 1cm;
            }
        }
    </style>
</head>

<body>
    <!-- Print Button (Hidden saat print) -->
    <div class="print-button">
        <button onclick="window.print()">
            🖨️ Print Invoice
        </button>
    </div>

    <div class="invoice-container">
        <!-- Invoice Header -->
        <div class="invoice-header">
            <h1>Toko Kelontong Pak Dedi</h1>
            <p>Jl. Contoh No. 123, Surabaya</p>
            <p>Telp: (031) 123-4567</p>
            <p class="invoice-title">INVOICE</p>
        </div>

        <!-- Invoice Info -->
        <div class="invoice-info">
            <div class="invoice-info-left">
                <p><strong>Order ID:</strong> #{{ $order->id }}</p>
                <p><strong>Date:</strong> {{ $order->created_at->format('l, F d, Y') }}</p>
                <p><strong>Time:</strong> {{ $order->created_at->format('H:i:s') }}</p>
                <p><strong>Cashier:</strong> {{ $order->user ? $order->user->getFullname() : 'N/A' }}</p>
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
                        Not Paid
                    @elseif($received < $total)
                        Partial Payment
                    @elseif($received == $total)
                        Paid in Full
                    @else
                        Change Returned
                    @endif
                </p>
            </div>
        </div>

        <!-- Invoice Items Table -->
        <table class="invoice-table">
            <thead>
                <tr>
                    <th style="width: 40px;">#</th>
                    <th>Item Name</th>
                    <th style="width: 120px;" class="text-right">Unit Price</th>
                    <th style="width: 80px;" class="text-right">Qty</th>
                    <th style="width: 130px;" class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($order->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->product?->name ?? 'N/A' }}</td>

                        <td class="text-right">
                            {{ config('settings.currency_symbol') }}
                            {{ number_format($item->unit_price, 2) }}
                        </td>

                        <td class="text-right">
                            {{ number_format($item->quantity, 0) }}
                        </td>

                        <td class="text-right">
                            {{ config('settings.currency_symbol') }}
                            {{ number_format($item->subtotal, 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center;">No items found</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-right"><strong>Subtotal:</strong></td>
                    <td class="text-right"><strong>{{ config('settings.currency_symbol') }}
                            {{ number_format($order->total(), 2) }}</strong></td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right"><strong>Paid Amount:</strong></td>
                    <td class="text-right"><strong>{{ config('settings.currency_symbol') }}
                            {{ number_format($order->receivedAmount(), 2) }}</strong></td>
                </tr>
                <tr class="total-row">
                    <td colspan="4" class="text-right"><strong>Balance Due:</strong></td>
                    <td class="text-right">
                        <strong>{{ config('settings.currency_symbol') }}
                            {{ number_format($order->total() - $order->receivedAmount(), 2) }}</strong>
                    </td>
                </tr>
            </tfoot>
        </table>

        <!-- Invoice Footer -->
        <div class="invoice-footer">
            <p class="thank-you">Thank You for Your Order!</p>
            <p>For questions about this invoice, please contact us</p>
            <p>www.tokokelontongpakdedi.com</p>
            <p style="margin-top: 15px; font-size: 0.85rem;">
                Printed on: {{ now()->format('F d, Y H:i:s') }}
            </p>
        </div>
    </div>

    <script>
        // Auto print saat halaman selesai load (OPTIONAL)
        // Uncomment baris di bawah jika ingin auto print
        // window.onload = function() {
        //     window.print();
        // }
    </script>
</body>

</html>