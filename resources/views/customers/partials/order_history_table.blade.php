@if($orders->count() > 0)
    <div class="table-container">
        <div class="table-scroll-wrapper">
            <table class="order-table">
                <thead>
                    <tr>
                        <th class="th-sortable">
                            ID
                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'id', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="sort-link">
                                <i class="fas fa-sort"></i>
                            </a>
                        </th>
                        <th class="th-sortable">
                            Tanggal
                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'created_at', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="sort-link">
                                <i class="fas fa-sort"></i>
                            </a>
                        </th>
                        <th>Produk</th>
                        <th class="th-sortable">
                            Qty
                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'quantity', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="sort-link">
                                <i class="fas fa-sort"></i>
                            </a>
                        </th>
                        <th>Harga</th>
                        <th>SubTotal</th>
                        <th class="th-sortable">
                            Amount
                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'received_amount', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="sort-link">
                                <i class="fas fa-sort"></i>
                            </a>
                        </th>
                        <th>Status</th>
                        <th>Kasir</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="order-id">#{{ $order->id }}</div>
                                </td>
                                <td>
                                    <div class="order-date">{{ $order->created_at->format('M d, Y H:i') }}</div>
                                </td>
                                <td>
                                    <div class="product-name">{{ $item->product->name }}</div>
                                </td>
                                <td>
                                    <div class="quantity">{{ $item->quantity }}</div>
                                </td>
                                <td>
                                    <div class="price">{{ config('settings.currency_symbol') }}{{ number_format($item->price, 2) }}</div>
                                </td>
                                <td>
                                    <div class="subtotal">{{ config('settings.currency_symbol') }}{{ number_format($item->price * $item->quantity, 2) }}</div>
                                </td>
                                <td>
                                    <div class="amount">{{ config('settings.currency_symbol') }}{{ number_format($order->receivedAmount(), 2) }}</div>
                                </td>
                                <td>
                                    @php
                                        $receivedAmount = $order->receivedAmount();
                                        $total = $order->total();
                                    @endphp
                                    @if($receivedAmount == 0)
                                        <span class="status-badge badge-not-paid">Not Paid</span>
                                    @elseif($receivedAmount < $total)
                                        <span class="status-badge badge-partial">Partial</span>
                                    @elseif($receivedAmount == $total)
                                        <span class="status-badge badge-paid">Paid</span>
                                    @elseif($receivedAmount > $total)
                                        <span class="status-badge badge-change">Change</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="cashier-name">{{ $order->user->full_name ?? $order->user->name ?? 'N/A' }}</div>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination Section -->
    <div class="pagination-container">
        <div class="pagination-info">
            Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} orders
        </div>
        <nav>
            {{ $orders->appends(request()->query())->links() }}
        </nav>
    </div>
@else
    <div class="empty-state">
        <div class="empty-state-icon">
            <i class="fas fa-shopping-cart"></i>
        </div>
        <h3 class="empty-state-title">No Orders Found</h3>
        <p class="empty-state-description">No orders match your search criteria.</p>
    </div>
@endif