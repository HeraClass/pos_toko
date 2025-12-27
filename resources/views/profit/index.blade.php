@extends('layouts.admin')

@section('title', 'Profit')
@section('content-header', 'Profit')

@section('css')
    <style>
        .summary-card {
            border-radius: 12px;
            padding: 1.5rem;
            color: #fff;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .summary-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .summary-card .icon {
            font-size: 2.5rem;
            opacity: 0.8;
        }

        .info-box {
            display: flex;
            align-items: center;
            padding: 1rem;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 1rem;
            min-width: 0;
        }

        .info-box .info-box-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            border-radius: 12px;
            font-size: 1.5rem;
            color: #fff;
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .info-box-content {
            flex: 1 1 auto;
            min-width: 0;
            overflow-wrap: break-word;
        }

        .info-box-text {
            display: block;
            white-space: normal;
            word-break: break-word;
            line-height: 1.2;
        }

        .table thead th {
            background-color: #f8fafc;
            font-weight: 600;
        }

        .table tbody tr:hover {
            background-color: #f1f5f9;
        }

        .profit-positive {
            color: #28a745;
            font-weight: 700;
        }

        .profit-negative {
            color: #dc3545;
            font-weight: 700;
        }

        .card-header.bg-primary {
            background-color: #007bff;
            color: #fff;
            border-bottom: none;
            border-radius: 12px 12px 0 0;
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">

        <!-- Filter Date Range -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('profit.index') }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Dari</label>
                            <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Sampai</label>
                            <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i>
                                Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="summary-card bg-info">
                    <div>
                        <h4>{{ config('settings.currency_symbol') }}{{ number_format($revenue, 2) }}</h4>
                        <p class="mb-0">Total Pendapatan</p>
                    </div>
                    <div class="icon"><i class="fas fa-shopping-cart"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="summary-card bg-warning">
                    <div>
                        <h4>{{ config('settings.currency_symbol') }}{{ number_format($totalCogs, 2) }}</h4>
                        <p class="mb-0">Total HPP</p>
                    </div>
                    <div class="icon"><i class="fas fa-box"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="summary-card bg-success">
                    <div>
                        <h4>{{ config('settings.currency_symbol') }}{{ number_format($grossProfit, 2) }}</h4>
                        <p class="mb-0">Laba Kotor ({{ number_format($grossProfitMargin, 2) }}%)</p>
                    </div>
                    <div class="icon"><i class="fas fa-chart-line"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="summary-card {{ $netProfit >= 0 ? 'bg-primary' : 'bg-danger' }}">
                    <div>
                        <h4>{{ config('settings.currency_symbol') }}{{ number_format($netProfit, 2) }}</h4>
                        <p class="mb-0">Laba Bersih ({{ number_format($netProfitMargin, 2) }}%)</p>
                    </div>
                    <div class="icon"><i class="fas fa-coins"></i></div>
                </div>
            </div>
        </div>

        <!-- Info Boxes -->
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-receipt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Pesanan</span>
                        <span class="info-box-number">{{ number_format($totalOrders) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="fas fa-dollar-sign"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Rata-rata Nilai Pesanan</span>
                        <span
                            class="info-box-number">{{ config('settings.currency_symbol') }}{{ number_format($averageOrderValue, 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="info-box">
                    <span class="info-box-icon bg-warning"><i class="fas fa-shopping-bag"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Pengeluaran Pembelian</span>
                        <span
                            class="info-box-number">{{ config('settings.currency_symbol') }}{{ number_format($totalPurchases ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="info-box">
                    <span class="info-box-icon bg-danger"><i class="fas fa-exclamation-triangle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Kerugian Stok</span>
                        <span
                            class="info-box-number">{{ config('settings.currency_symbol') }}{{ number_format($stockLoss, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profit & Loss Statement & Chart -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary">
                        <h3 class="card-title text-white"><i class="fas fa-file-invoice"></i> Laporan Laba Rugi</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <tbody>
                                <tr class="bg-light">
                                    <td><strong>PENDAPATAN</strong></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="ps-4">Penjualan</td>
                                    <td class="text-end">
                                        {{ config('settings.currency_symbol') }}{{ number_format($revenue, 2) }}
                                    </td>
                                </tr>
                                <tr class="bg-light">
                                    <td><strong>HARGA POKOK PENJUALAN</strong></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="ps-4">HPP Barang Terjual</td>
                                    <td class="text-end text-muted">
                                        {{ config('settings.currency_symbol') }}{{ number_format($cogs, 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-4">Kerugian Stok</td>
                                    <td class="text-end text-muted">
                                        {{ config('settings.currency_symbol') }}{{ number_format($stockLoss, 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-4"><strong>Total HPP</strong></td>
                                    <td class="text-end text-danger">
                                        <strong>-{{ config('settings.currency_symbol') }}{{ number_format($totalCogs, 2) }}</strong>
                                    </td>
                                </tr>
                                <tr class="bg-success text-white">
                                    <td><strong>LABA KOTOR</strong></td>
                                    <td class="text-end">
                                        <strong>{{ config('settings.currency_symbol') }}{{ number_format($grossProfit, 2) }}</strong>
                                    </td>
                                </tr>
                                <tr class="bg-light">
                                    <td><strong>BIAYA OPERASIONAL</strong></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="ps-4">Gaji, Sewa, Listrik, dll</td>
                                    <td class="text-end text-danger">
                                        -{{ config('settings.currency_symbol') }}{{ number_format($operatingExpenses, 2) }}
                                    </td>
                                </tr>
                                <tr class="{{ $netProfit >= 0 ? 'bg-primary text-white' : 'bg-danger text-white' }}">
                                    <td><strong>LABA BERSIH</strong></td>
                                    <td class="text-end">
                                        <strong>{{ config('settings.currency_symbol') }}{{ number_format($netProfit, 2) }}</strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-chart-area"></i> Grafik Harian</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="dailyChart" style="height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Profitable Products -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-medal"></i> 10 Produk Paling Menguntungkan</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-sm mb-0">
                    <thead>
                        <tr>
                            <th style="width: 30px">No</th>
                            <th>Nama Produk</th>
                            <th class="text-end">Qty Terjual</th>
                            <th class="text-end">Total Penjualan</th>
                            <th class="text-end">Total HPP</th>
                            <th class="text-end">Laba</th>
                            <th class="text-end">Margin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productProfits as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item['product_name'] }}</td>
                                <td class="text-end">{{ number_format($item['qty_sold']) }}</td>
                                <td class="text-end">
                                    {{ config('settings.currency_symbol') }}{{ number_format($item['total_sales'], 2) }}
                                </td>
                                <td class="text-end">
                                    {{ config('settings.currency_symbol') }}{{ number_format($item['total_cost'], 2) }}
                                </td>
                                <td class="text-end {{ $item['profit'] >= 0 ? 'profit-positive' : 'profit-negative' }}">
                                    <strong>{{ config('settings.currency_symbol') }}{{ number_format($item['profit'], 2) }}</strong>
                                </td>
                                <td class="text-end {{ $item['margin'] >= 0 ? 'profit-positive' : 'profit-negative' }}">
                                    <strong>{{ number_format($item['margin'], 2) }}%</strong>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Tidak ada data penjualan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Notes -->
        <div class="card">
            <div class="card-body">
                <div class="alert alert-info mb-0">
                    <h5><i class="fas fa-info-circle"></i> Penjelasan Perhitungan:</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="mb-0">
                                <li><strong>Pendapatan:</strong> Total nilai penjualan dari semua pesanan</li>
                                <li><strong>HPP Barang Terjual:</strong> Harga beli rata-rata × Qty produk yang terjual</li>
                                <li><strong>Kerugian Stok:</strong> Nilai barang rusak/kadaluarsa/hilang (dari adjustment
                                    decrease)</li>
                                <li><strong>Laba Kotor:</strong> Pendapatan - Total HPP</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="mb-0">
                                <li><strong>Biaya Operasional:</strong> Gaji, sewa, listrik, marketing, dll</li>
                                <li><strong>Laba Bersih:</strong> Laba Kotor - Biaya Operasional</li>
                                <li><strong>Margin:</strong> Persentase keuntungan dari penjualan</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script>
        const dailyData = @json($dailyData);
        const ctx = document.getElementById('dailyChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: dailyData.map(d => d.date),
                datasets: [
                    {
                        label: 'Pendapatan',
                        data: dailyData.map(d => d.revenue),
                        borderColor: 'rgb(54,162,235)',
                        backgroundColor: 'rgba(54,162,235,0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'HPP + Kerugian Stok',
                        data: dailyData.map(d => d.cost),
                        borderColor: 'rgb(255,99,132)',
                        backgroundColor: 'rgba(255,99,132,0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Laba Kotor',
                        data: dailyData.map(d => d.profit),
                        borderColor: 'rgb(75,192,192)',
                        backgroundColor: 'rgba(75,192,192,0.1)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                let label = context.dataset.label || '';
                                if (label) label += ': ';
                                label += '{{ config('settings.currency_symbol') }}' + context.parsed.y.toLocaleString('id-ID', { minimumFractionDigits: 2 });
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { callback: function (value) { return '{{ config('settings.currency_symbol') }}' + value.toLocaleString('id-ID'); } } }
                }
            }
        });
    </script>
@endsection