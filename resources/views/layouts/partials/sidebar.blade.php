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

            @can('categories.view')
                <li class="nav-item">
                    <a href="{{ route('categories.index') }}" class="nav-link {{ activeSegment('categories') }}">
                        <span class="nav-icon"><i class="fas fa-box"></i></span>
                        <span class="nav-text">{{ __('category.title') }}</span>
                    </a>
                </li>
            @endcan

            @can('products.view')
                <li class="nav-item">
                    <a href="{{ route('products.index') }}" class="nav-link {{ activeSegment('products') }}">
                        <span class="nav-icon"><i class="fas fa-box"></i></span>
                        <span class="nav-text">{{ __('product.title') }}</span>
                    </a>
                </li>
            @endcan

            @can('purchases.view')
                <li class="nav-item">
                    <a href="{{ route('purchases.index') }}" class="nav-link {{ activeSegment('purchases') }}">
                        <span class="nav-icon"><i class="fas fa-box"></i></span>
                        <span class="nav-text">{{ __('purchase.title') }}</span>
                    </a>
                </li>
            @endcan

            @can('adjustments.view')
                <li class="nav-item">
                    <a href="{{ route('adjustments.index') }}" class="nav-link {{ activeSegment('adjustments') }}">
                        <span class="nav-icon"><i class="fas fa-box"></i></span>
                        <span class="nav-text">{{ __('adjustment.title') }}</span>
                    </a>
                </li>
            @endcan

            @can('cart.view')
                <li class="nav-item">
                    <a href="{{ route('cart.index') }}" class="nav-link {{ activeSegment('cart') }}">
                        <span class="nav-icon"><i class="fas fa-shopping-cart"></i></span>
                        <span class="nav-text">{{ __('cart.title') }}</span>
                    </a>
                </li>
            @endcan

            @can('orders.view')
                <li class="nav-item">
                    <a href="{{ route('orders.index') }}" class="nav-link {{ activeSegment('orders') }}">
                        <span class="nav-icon"><i class="fas fa-clipboard-list"></i></span>
                        <span class="nav-text">{{ __('order.title') }}</span>
                    </a>
                </li>
            @endcan

            @can('customers.view')
                <li class="nav-item">
                    <a href="{{ route('customers.index') }}" class="nav-link {{ activeSegment('customers') }}">
                        <span class="nav-icon"><i class="fas fa-users"></i></span>
                        <span class="nav-text">{{ __('customer.title') }}</span>
                    </a>
                </li>
            @endcan

            @can('suppliers.view')
                <li class="nav-item">
                    <a href="{{ route('suppliers.index') }}" class="nav-link {{ activeSegment('suppliers') }}">
                        <span class="nav-icon"><i class="fas fa-users"></i></span>
                        <span class="nav-text">{{ __('suppliers.title') }}</span>
                    </a>
                </li>
            @endcan

            <li class="nav-item">
                <a href="{{ route('expenses.index') }}" class="nav-link {{ activeSegment('expenses') }}">
                    <span class="nav-icon"><i class="fas fa-money-bill-wave"></i></span>
                    <span class="nav-text">{{ __('expense.title') }}</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('profit.index') }}" class="nav-link">
                    <span class="nav-icon"><i class="fas fa-chart-line"></i></span>
                    <span class="nav-text">Profit</span>
                </a>
            </li>

            @can('permissions.view')
                <li class="nav-item">
                    <a href="{{ route('permissions.index') }}" class="nav-link {{ activeSegment('permissions') }}">
                        <span class="nav-icon"><i class="fas fa-key"></i></span>
                        <span class="nav-text">Permission Management</span>
                    </a>
                </li>
            @endcan

            @can('roles.view')
                <li class="nav-item">
                    <a href="{{ route('roles.index') }}" class="nav-link {{ activeSegment('roles') }}">
                        <span class="nav-icon"><i class="fas fa-shield-alt"></i></span>
                        <span class="nav-text">Role Management</span>
                    </a>
                </li>
            @endcan

            @can('users.view')
                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link {{ activeSegment('users') }}">
                        <span class="nav-icon"><i class="fas fa-user-cog"></i></span>
                        <span class="nav-text">User Management</span>
                    </a>
                </li>
            @endcan

            @can('settings.edit')
                <li class="nav-item">
                    <a href="{{ route('settings.index') }}" class="nav-link {{ activeSegment('settings') }}">
                        <span class="nav-icon"><i class="fas fa-cog"></i></span>
                        <span class="nav-text">{{ __('settings.title') }}</span>
                    </a>
                </li>
            @endcan

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
            left: 0;
            top: 0;
        }

        .app-sidebar.collapsed {
            width: 70px;
        }

        .app-sidebar.collapsed .brand-text,
        .app-sidebar.collapsed .nav-text {
            opacity: 0;
            visibility: hidden;
            width: 0;
        }

        .app-sidebar.collapsed .brand-container {
            justify-content: center;
            padding: 1.5rem 0.5rem;
        }

        .app-sidebar.collapsed .brand-logo {
            margin-right: 0;
        }

        .app-sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 0.85rem 0.5rem;
        }

        .app-sidebar.collapsed .nav-icon {
            margin-right: 0;
        }

        .brand-container {
            display: flex;
            align-items: center;
            padding: 1.5rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            height: var(--header-height);
            transition: var(--transition);
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
            flex-shrink: 0;
            transition: var(--transition);
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
            white-space: nowrap;
            transition: opacity 0.3s ease, visibility 0.3s ease, width 0.3s ease;
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
        }

        .app-sidebar:not(.collapsed) .nav-link:hover,
        .app-sidebar:not(.collapsed) .nav-link.active {
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
            flex-shrink: 0;
            transition: var(--transition);
        }

        .nav-text {
            flex: 1;
            font-size: 0.95rem;
            font-weight: 500;
            white-space: nowrap;
            transition: opacity 0.3s ease, visibility 0.3s ease, width 0.3s ease;
        }

        @media (max-width: 992px) {
            .app-sidebar {
                transform: translateX(-100%);
            }

            .app-sidebar.mobile-open {
                transform: translateX(0);
            }

            .app-sidebar.collapsed {
                width: var(--sidebar-width);
            }

            .app-sidebar.collapsed .brand-text,
            .app-sidebar.collapsed .nav-text {
                opacity: 1;
                visibility: visible;
                width: auto;
            }

            .app-sidebar.collapsed .brand-container {
                justify-content: flex-start;
                padding: 1.5rem 1rem;
            }

            .app-sidebar.collapsed .brand-logo {
                margin-right: 12px;
            }

            .app-sidebar.collapsed .nav-link {
                justify-content: flex-start;
                padding: 0.85rem 1rem;
            }

            .app-sidebar.collapsed .nav-icon {
                margin-right: 12px;
            }
        }
    </style>
</aside>