<!-- Main Sidebar Container -->
<aside class="app-sidebar">
    <div class="brand-container">
        <div class="brand-logo">
            <i class="fas fa-store"></i>
        </div>
        <div class="brand-text">{{ config('app.name') }}</div>
    </div>

    <nav class="sidebar-nav">
        <ul class="nav-list">
            <li class="nav-item">
                <a href="{{route('home')}}" class="nav-link {{ activeSegment('') }}">
                    <span class="nav-icon"><i class="fas fa-home"></i></span>
                    <span class="nav-text">{{ __('dashboard.title') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('categories.index') }}" class="nav-link {{ activeSegment('categories') }}">
                    <span class="nav-icon"><i class="fas fa-box"></i></span>
                    <span class="nav-text">{{ __('category.title') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('products.index') }}" class="nav-link {{ activeSegment('products') }}">
                    <span class="nav-icon"><i class="fas fa-box"></i></span>
                    <span class="nav-text">{{ __('product.title') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('cart.index') }}" class="nav-link {{ activeSegment('cart') }}">
                    <span class="nav-icon"><i class="fas fa-shopping-cart"></i></span>
                    <span class="nav-text">{{ __('cart.title') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('orders.index') }}" class="nav-link {{ activeSegment('orders') }}">
                    <span class="nav-icon"><i class="fas fa-clipboard-list"></i></span>
                    <span class="nav-text">{{ __('order.title') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('customers.index') }}" class="nav-link {{ activeSegment('customers') }}">
                    <span class="nav-icon"><i class="fas fa-users"></i></span>
                    <span class="nav-text">{{ __('customer.title') }}</span>
                </a>
            </li>
              <li class="nav-item">
                <a href="{{ route('suppliers.index') }}" class="nav-link {{ activeSegment('suppliers') }}">
                    <span class="nav-icon"><i class="fas fa-users"></i></span>
                    <span class="nav-text">{{ __('suppliers.title') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('settings.index') }}" class="nav-link {{ activeSegment('settings') }}">
                    <span class="nav-icon"><i class="fas fa-cog"></i></span>
                    <span class="nav-text">{{ __('settings.title') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link" onclick="document.getElementById('logout-form').submit()">
                    <span class="nav-icon"><i class="fas fa-sign-out-alt"></i></span>
                    <span class="nav-text">{{ __('common.Logout') }}</span>
                </a>
                <form action="{{route('logout')}}" method="POST" id="logout-form">
                    @csrf
                </form>
            </li>
        </ul>
    </nav>

    <style>
        .app-sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            color: var(--sidebar-color);
            position: fixed;
            height: 100vh;
            z-index: 1000;
            transition: var(--transition);
            box-shadow: var(--shadow);
        }

        .brand-container {
            display: flex;
            align-items: center;
            padding: 1.5rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            height: var(--header-height);
        }

        .brand-logo {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            overflow: hidden;
        }

        .brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .brand-text {
            font-weight: 700;
            font-size: 18px;
            letter-spacing: -0.5px;
        }

        .sidebar-nav {
            padding: 1.5rem 0;
            overflow-y: auto;
            height: calc(100vh - var(--header-height));
        }

        .nav-list {
            list-style: none;
            padding: 0 0.5rem;
        }

        .nav-item {
            margin-bottom: 0.25rem;
            position: relative;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.85rem 1rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: var(--transition);
            border-radius: var(--border-radius);
            position: relative;
        }

        .nav-link:hover,
        .nav-link.active {
            background: var(--sidebar-hover);
            color: #fff;
            transform: translateX(5px);
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            height: 60%;
            width: 3px;
            background: var(--primary);
            border-radius: 0 2px 2px 0;
        }

        .nav-icon {
            margin-right: 12px;
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        .nav-text {
            flex: 1;
            font-size: 0.95rem;
            font-weight: 500;
        }

        @media (max-width: 992px) {
            .app-sidebar {
                transform: translateX(-100%);
            }

            .app-sidebar.mobile-open {
                transform: translateX(0);
            }
        }
    </style>
</aside>