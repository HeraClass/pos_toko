@extends('layouts.admin')
@section('content-header', __('dashboard.title'))

@section('css')
   <style>
      .dashboard-container {
         padding: 0.5rem;
      }

      .stats-grid {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
         gap: 1.5rem;
         margin-bottom: 2rem;
      }

      .stat-card {
         background: white;
         border-radius: 12px;
         padding: 1.5rem;
         box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
         transition: transform 0.3s ease, box-shadow 0.3s ease;
         border-left: 4px solid;
      }

      .stat-card:hover {
         transform: translateY(-2px);
         box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      }

      .stat-card.info {
         border-left-color: #17a2b8;
      }

      .stat-card.success {
         border-left-color: #28a745;
      }

      .stat-card.danger {
         border-left-color: #dc3545;
      }

      .stat-card.warning {
         border-left-color: #ffc107;
      }

      .stat-icon {
         font-size: 2.5rem;
         margin-bottom: 1rem;
         opacity: 0.8;
      }

      .stat-card.info .stat-icon {
         color: #17a2b8;
      }

      .stat-card.success .stat-icon {
         color: #28a745;
      }

      .stat-card.danger .stat-icon {
         color: #dc3545;
      }

      .stat-card.warning .stat-icon {
         color: #ffc107;
      }

      .stat-value {
         font-size: 1.8rem;
         font-weight: 700;
         margin-bottom: 0.5rem;
         color: #2d3748;
      }

      .stat-label {
         color: #718096;
         font-weight: 500;
         margin-bottom: 1rem;
         font-size: 0.95rem;
      }

      .stat-link {
         display: flex;
         align-items: center;
         color: #4a5568;
         text-decoration: none;
         font-weight: 500;
         font-size: 0.9rem;
         transition: color 0.3s ease;
      }

      .stat-link:hover {
         color: #2b6cb0;
      }

      .stat-link i {
         margin-left: 0.5rem;
         transition: transform 0.3s ease;
      }

      .stat-link:hover i {
         transform: translateX(3px);
      }

      .products-grid {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
         gap: 1.5rem;
         margin-bottom: 2rem;
      }

      .product-section {
         background: white;
         border-radius: 12px;
         padding: 1.5rem;
         box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      }

      .section-header {
         display: flex;
         justify-content: space-between;
         align-items: center;
         margin-bottom: 1.5rem;
         padding-bottom: 0.75rem;
         border-bottom: 2px solid #f7fafc;
      }

      .section-title {
         font-size: 1.25rem;
         font-weight: 600;
         color: #2d3748;
         margin: 0;
      }

      .product-table {
         width: 100%;
         border-collapse: collapse;
      }

      .product-table th {
         background-color: #f7fafc;
         padding: 0.75rem;
         text-align: left;
         font-weight: 600;
         color: #4a5568;
         border-bottom: 2px solid #e2e8f0;
      }

      .product-table td {
         padding: 0.75rem;
         border-bottom: 1px solid #e2e8f0;
         vertical-align: middle;
      }

      .product-table tr:last-child td {
         border-bottom: none;
      }

      .product-table tr:hover {
         background-color: #f7fafc;
      }

      .product-img {
         width: 50px;
         height: 50px;
         object-fit: cover;
         border-radius: 6px;
         border: 1px solid #e2e8f0;
      }

      .status-badge {
         padding: 0.25rem 0.75rem;
         border-radius: 20px;
         font-size: 0.75rem;
         font-weight: 600;
      }

      .status-active {
         background-color: #c6f6d5;
         color: #22543d;
      }

      .status-inactive {
         background-color: #fed7d7;
         color: #742a2a;
      }

      @media (max-width: 1024px) {
         .products-grid {
            grid-template-columns: 1fr;
         }
      }

      @media (max-width: 768px) {
         .dashboard-container {
            padding: 1rem;
         }

         .stats-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
         }

         .product-table {
            display: block;
            overflow-x: auto;
         }

         .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
         }
      }

      .empty-state {
         text-align: center;
         padding: 2rem;
         color: #a0aec0;
      }

      .empty-state i {
         font-size: 3rem;
         margin-bottom: 1rem;
         opacity: 0.5;
      }
   </style>
@endsection

@section('content')
   <div class="dashboard-container">
      <!-- Statistics Cards -->
      <div class="stats-grid">
         <div class="stat-card info">
            <div class="stat-icon">
               <i class="fas fa-shopping-bag"></i>
            </div>
            <div class="stat-value">{{$orders_count}}</div>
            <div class="stat-label">{{ __('dashboard.Orders_Count') }}</div>
            <a href="{{route('orders.index')}}" class="stat-link">
               {{ __('common.More_info') }} <i class="fas fa-arrow-right"></i>
            </a>
         </div>

         <div class="stat-card success">
            <div class="stat-icon">
               <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-value">{{config('settings.currency_symbol')}} {{number_format($income, 2)}}</div>
            <div class="stat-label">{{ __('dashboard.Income') }}</div>
            <a href="{{route('orders.index')}}" class="stat-link">
               {{ __('common.More_info') }} <i class="fas fa-arrow-right"></i>
            </a>
         </div>

         <div class="stat-card danger">
            <div class="stat-icon">
               <i class="fas fa-calendar-day"></i>
            </div>
            <div class="stat-value">{{config('settings.currency_symbol')}} {{number_format($income_today, 2)}}</div>
            <div class="stat-label">{{ __('dashboard.Income_Today') }}</div>
            <a href="{{route('orders.index')}}" class="stat-link">
               {{ __('common.More_info') }} <i class="fas fa-arrow-right"></i>
            </a>
         </div>

         <div class="stat-card warning">
            <div class="stat-icon">
               <i class="fas fa-users"></i>
            </div>
            <div class="stat-value">{{$products_count}}</div>
            <div class="stat-label">{{ __('dashboard.Products_Count') }}</div>
            <a href="{{ route('products.index') }}" class="stat-link">
               {{ __('common.More_info') }} <i class="fas fa-arrow-right"></i>
            </a>
         </div>
      </div>

      <!-- Product Sections -->
      <div class="products-grid">
         <!-- Top 10 Most Profitable Products -->
         <div class="product-section">
            <div class="section-header">
               <h3 class="section-title">{{ __('dashboard.Top_Profitable_Products') }}</h3>
            </div>
            @if($top_profitable_products->count() > 0)
               <div class="table-responsive">
                  <table class="product-table">
                     <thead>
                        <tr>
                           <th>{{ __('dashboard.ID') }}</th>
                           <th>{{ __('dashboard.Name') }}</th>
                           <th>{{ __('dashboard.Image') }}</th>
                           <th>{{ __('dashboard.Qty_Sold') }}</th>
                           <th>{{ __('dashboard.Total_Sales') }}</th>
                           <th>{{ __('dashboard.Total_Cost') }}</th>
                           <th>{{ __('dashboard.Profit') }}</th>
                           <th>{{ __('dashboard.Margin') }}</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach ($top_profitable_products as $product)
                           <tr>
                              <td>{{ $product['id'] }}</td>
                              <td>{{ $product['product_name'] }}</td>
                              <td>
                                 <img class="product-img" src="{{ Storage::url($product['image']) }}"
                                    alt="{{ $product['product_name'] }}">
                              </td>
                              <td>{{ $product['qty_sold'] }}</td>
                              <td>{{ config('settings.currency_symbol') }} {{ number_format($product['total_sales'], 2) }}</td>
                              <td>{{ config('settings.currency_symbol') }} {{ number_format($product['total_cost'], 2) }}</td>
                              <td>{{ config('settings.currency_symbol') }} {{ number_format($product['profit'], 2) }}</td>
                              <td>{{ number_format($product['margin'], 2) }}%</td>
                           </tr>
                        @endforeach
                     </tbody>
                  </table>
               </div>
            @else
               <div class="empty-state">
                  <i class="fas fa-chart-line"></i>
                  <p>{{ __('dashboard.No_Top_Profitable_Products') }}</p>
               </div>
            @endif
         </div>

         <!-- Low Stock Products -->
         <div class="product-section">
            <div class="section-header">
               <h3 class="section-title">{{ __('dashboard.Low_Stock_Product') }}</h3>
            </div>
            @if($low_stock_products->count() > 0)
               <div class="table-responsive">
                  <table class="product-table">
                     <thead>
                        <tr>
                           <th>{{ __('dashboard.ID') }}</th>
                           <th>{{ __('dashboard.Name') }}</th>
                           <th>{{ __('dashboard.Image') }}</th>
                           <th>{{ __('dashboard.Barcode') }}</th>
                           <th>{{ __('dashboard.Price') }}</th>
                           <th>{{ __('dashboard.Quantity') }}</th>
                           <th>{{ __('dashboard.Status') }}</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach ($low_stock_products as $product)
                           <tr>
                              <td>{{$product->id}}</td>
                              <td>{{$product->name}}</td>
                              <td>
                                 <img class="product-img" src="{{ Storage::url($product->image) }}" alt="{{$product->name}}">
                              </td>
                              <td>{{$product->barcode}}</td>
                              <td>{{config('settings.currency_symbol')}} {{number_format($product->price, 2)}}</td>
                              <td>
                                 <span
                                    class="{{ $product->quantity <= config('settings.warning_quantity') ? 'text-danger font-weight-bold' : '' }}">
                                    {{$product->quantity}}
                                 </span>
                              </td>
                              <td>
                                 <span class="status-badge {{ $product->status ? 'status-active' : 'status-inactive' }}">
                                    {{$product->status ? __('common.Active') : __('common.Inactive') }}
                                 </span>
                              </td>
                           </tr>
                        @endforeach
                     </tbody>
                  </table>
               </div>
            @else
               <div class="empty-state">
                  <i class="fas fa-box-open"></i>
                  <p>{{ __('dashboard.No_Low_Stock_Products') }}</p>
               </div>
            @endif
         </div>

         <!-- Hot Products -->
         <div class="product-section">
            <div class="section-header">
               <h3 class="section-title">{{ __('dashboard.Hot_Products') }}</h3>
            </div>
            @if($current_month_products->count() > 0)
               <div class="table-responsive">
                  <table class="product-table">
                     <thead>
                        <tr>
                           <th>{{ __('dashboard.ID') }}</th>
                           <th>{{ __('dashboard.Name') }}</th>
                           <th>{{ __('dashboard.Image') }}</th>
                           <th>{{ __('dashboard.Barcode') }}</th>
                           <th>{{ __('dashboard.Price') }}</th>
                           <th>{{ __('dashboard.Quantity') }}</th>
                           <th>{{ __('dashboard.Status') }}</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach ($current_month_products as $product)
                           <tr>
                              <td>{{$product->id}}</td>
                              <td>{{$product->name}}</td>
                              <td>
                                 <img class="product-img" src="{{ Storage::url($product->image) }}" alt="{{$product->name}}">
                              </td>
                              <td>{{$product->barcode}}</td>
                              <td>{{config('settings.currency_symbol')}} {{number_format($product->price, 2)}}</td>
                              <td>{{$product->quantity}}</td>
                              <td>
                                 <span class="status-badge {{ $product->status ? 'status-active' : 'status-inactive' }}">
                                    {{$product->status ? __('common.Active') : __('common.Inactive') }}
                                 </span>
                              </td>
                           </tr>
                        @endforeach
                     </tbody>
                  </table>
               </div>
            @else
               <div class="empty-state">
                  <i class="fas fa-fire"></i>
                  <p>{{ __('dashboard.No_Hot_Products') }}</p>
               </div>
            @endif
         </div>

         <!-- Hot Products of Year -->
         <div class="product-section">
            <div class="section-header">
               <h3 class="section-title">{{ __('dashboard.Hot_Products_Of_Year') }}</h3>
            </div>
            @if($past_months_products->count() > 0)
               <div class="table-responsive">
                  <table class="product-table">
                     <thead>
                        <tr>
                           <th>{{ __('dashboard.ID') }}</th>
                           <th>{{ __('dashboard.Name') }}</th>
                           <th>{{ __('dashboard.Image') }}</th>
                           <th>{{ __('dashboard.Barcode') }}</th>
                           <th>{{ __('dashboard.Price') }}</th>
                           <th>{{ __('dashboard.Quantity') }}</th>
                           <th>{{ __('dashboard.Status') }}</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach ($past_months_products as $product)
                           <tr>
                              <td>{{$product->id}}</td>
                              <td>{{$product->name}}</td>
                              <td>
                                 <img class="product-img" src="{{ Storage::url($product->image) }}" alt="{{$product->name}}">
                              </td>
                              <td>{{$product->barcode}}</td>
                              <td>{{config('settings.currency_symbol')}} {{number_format($product->price, 2)}}</td>
                              <td>{{$product->quantity}}</td>
                              <td>
                                 <span class="status-badge {{ $product->status ? 'status-active' : 'status-inactive' }}">
                                    {{$product->status ? __('common.Active') : __('common.Inactive') }}
                                 </span>
                              </td>
                           </tr>
                        @endforeach
                     </tbody>
                  </table>
               </div>
            @else
               <div class="empty-state">
                  <i class="fas fa-star"></i>
                  <p>{{ __('dashboard.No_Yearly_Hot_Products') }}</p>
               </div>
            @endif
         </div>

         <!-- Best Selling Products -->
         <div class="product-section">
            <div class="section-header">
               <h3 class="section-title">{{ __('dashboard.Best_Selling_Products') }}</h3>
            </div>
            @if($best_selling_products->count() > 0)
               <div class="table-responsive">
                  <table class="product-table">
                     <thead>
                        <tr>
                           <th>{{ __('dashboard.ID') }}</th>
                           <th>{{ __('dashboard.Name') }}</th>
                           <th>{{ __('dashboard.Image') }}</th>
                           <th>{{ __('dashboard.Barcode') }}</th>
                           <th>{{ __('dashboard.Price') }}</th>
                           <th>{{ __('dashboard.Quantity') }}</th>
                           <th>{{ __('dashboard.Status') }}</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach ($best_selling_products as $product)
                           <tr>
                              <td>{{$product->id}}</td>
                              <td>{{$product->name}}</td>
                              <td>
                                 <img class="product-img" src="{{ Storage::url($product->image) }}" alt="{{$product->name}}">
                              </td>
                              <td>{{$product->barcode}}</td>
                              <td>{{config('settings.currency_symbol')}} {{number_format($product->price, 2)}}</td>
                              <td>{{$product->quantity}}</td>
                              <td>
                                 <span class="status-badge {{ $product->status ? 'status-active' : 'status-inactive' }}">
                                    {{$product->status ? __('common.Active') : __('common.Inactive') }}
                                 </span>
                              </td>
                           </tr>
                        @endforeach
                     </tbody>
                  </table>
               </div>
            @else
               <div class="empty-state">
                  <i class="fas fa-trophy"></i>
                  <p>{{ __('dashboard.No_Best_Selling_Products') }}</p>
               </div>
            @endif
         </div>
      </div>
   </div>
@endsection