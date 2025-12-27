@extends('layouts.admin')

@section('title', 'Detail Harga Produk - ' . $product->name)
@section('content-header', 'Detail Harga Produk')

@section('content-actions')
    <a href="{{ route('products.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
<style>
    .product-detail-container { padding: 0.5rem; }

    .stats-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-left: 4px solid #28a745;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }
    .stats-card.info { border-left-color: #17a2b8; }
    .stats-card.success { border-left-color: #28a745; }
    .stats-card.danger { border-left-color: #dc3545; }
    .stats-card.warning { border-left-color: #ffc107; }

    .stats-icon { font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.8; }
    .stats-number { font-size: 1.8rem; font-weight: 700; margin-bottom: 0.5rem; color: #2d3748; }
    .stats-label { color: #718096; font-weight: 500; font-size: 0.95rem; }

    .table-container { overflow: hidden; position: relative; }
    .table-scroll-wrapper { overflow: auto; max-height: 70vh; }
    .order-table { width: 100%; border-collapse: collapse; margin-bottom: 0; min-width: 1200px; }
    .order-table thead { background-color: #f8fafc; border-bottom: 2px solid #e2e8f0; position: sticky; top: 0; z-index: 10; }
    .order-table th { padding: 1rem 1.5rem; text-align: left; font-weight: 600; color: #4a5568; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid #e2e8f0; white-space: nowrap; }
    .order-table td { padding: 1rem 1.5rem; border-bottom: 1px solid #e2e8f0; color: #4a5568; font-size: 0.9rem; white-space: nowrap; }
    .order-table tbody tr { transition: all 0.3s ease; background-color: white; animation: fadeInUp 0.5s ease; }
    .order-table tbody tr:hover { background-color: #f7fafc; transform: translateY(-1px); box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05); }

    .status-badge { padding: 0.5rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; display: inline-block; }
    .badge-not-paid { background-color: #fed7d7; color: #c53030; }
    .badge-partial { background-color: #feebcb; color: #b45309; }
    .badge-paid { background-color: #c6f6d5; color: #22543d; }
    .badge-change { background-color: #bee3f8; color: #2c5282; }

    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    .pagination-container { margin-top: 1rem; display: flex; justify-content: space-between; align-items: center; }
    .pagination-info { font-size: 0.875rem; color: #4a5568; }
</style>
@endsection

@section('content')
<div class="product-detail-container">
    <!-- Statistik Produk -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stats-card success">
                <div class="stats-icon"><i class="fas fa-dollar-sign"></i></div>
                <div class="stats-number">{{ number_format($product->price, 0, ',', '.') }}</div>
                <div class="stats-label">Harga Jual</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card info">
                <div class="stats-icon"><i class="fas fa-boxes"></i></div>
                <div class="stats-number">{{ $product->quantity }}</div>
                <div class="stats-label">Stok</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card warning">
                <div class="stats-icon"><i class="fas fa-tags"></i></div>
                <div class="stats-number">{{ $product->category ? $product->category->name : '-' }}</div>
                <div class="stats-label">Kategori</div>
            </div>
        </div>
    </div>

    <!-- Statistik Harga -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card info">
                <div class="stats-icon"><i class="fas fa-chart-line"></i></div>
                <div class="stats-number">{{ number_format($priceStats['average_cost'], 0, ',', '.') }}</div>
                <div class="stats-label">Harga Rata-rata Beli</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card success">
                <div class="stats-icon"><i class="fas fa-arrow-down"></i></div>
                <div class="stats-number">{{ number_format($priceStats['lowest_cost'], 0, ',', '.') }}</div>
                <div class="stats-label">Harga Beli Terendah</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card danger">
                <div class="stats-icon"><i class="fas fa-arrow-up"></i></div>
                <div class="stats-number">{{ number_format($priceStats['highest_cost'], 0, ',', '.') }}</div>
                <div class="stats-label">Harga Beli Tertinggi</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card {{ $priceStats['is_cost_higher_than_price'] ? 'danger' : 'warning' }}">
                <div class="stats-icon"><i class="fas fa-percentage"></i></div>
                <div class="stats-number">{{ number_format($priceStats['profit_margin'], 2) }}%</div>
                <div class="stats-label">Margin Keuntungan</div>
            </div>
        </div>
    </div>

    <!-- Tabel Riwayat Pembelian -->
    <div class="card">
        <div class="card-body">
            @if($purchases->count() > 0)
            <div class="table-container">
                <div class="table-scroll-wrapper">
                    <table class="order-table">
                        <thead>
                            <tr>
                                <th>Tanggal Pembelian</th>
                                <th>Supplier</th>
                                <th>Harga Beli</th>
                                <th>Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchases as $purchase)
                            <tr>
                                <td>{{ $purchase->created_at->format('d-m-Y H:i') }}</td>
                                <td>{{ optional($purchase->purchase->supplier)->getFullNameAttribute() ?? 'N/A' }}</td>
                                <td>{{ number_format($purchase->price, 0, ',', '.') }}</td>
                                <td>{{ $purchase->quantity }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="pagination-container">
                <div class="pagination-info">
                    Showing {{ $purchases->firstItem() }} to {{ $purchases->lastItem() }} of {{ $purchases->total() }} purchases
                </div>
                <nav>
                    {{ $purchases->appends(request()->query())->links() }}
                </nav>
            </div>
            @else
            <div class="empty-state text-center py-5">
                <div class="empty-state-icon mb-3">
                    <i class="fas fa-box-open fa-3x text-gray-400"></i>
                </div>
                <h3 class="empty-state-title mb-2">No Purchases Found</h3>
                <p class="empty-state-description text-gray-500">This product has no purchase history yet.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
