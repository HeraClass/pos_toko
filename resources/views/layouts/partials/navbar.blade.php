<!-- Navbar -->
<nav class="main-navbar">
    <div class="navbar-container">
        <!-- Left section -->
        <div class="navbar-left">
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <a href="{{ route('home') }}" class="nav-brand">{{ __('dashboard.title') }}</a>
        </div>

        <!-- Right section -->
        <div class="navbar-right">
            <!-- Language Switcher -->
            <div class="nav-dropdown">
                <button class="nav-dropdown-toggle">
                    <i class="fas fa-globe"></i>
                    <span class="lang-code">{{ strtoupper(app()->getLocale()) }}</span>
                </button>
                <div class="nav-dropdown-menu">
                    <a href="{{ route('lang.switch', ['lang' => 'en']) }}" class="dropdown-item">
                        <i class="fas fa-language"></i>
                        <span>English</span>
                    </a>
                    <a href="{{ route('lang.switch', ['lang' => 'id']) }}" class="dropdown-item">
                        <i class="fas fa-language"></i>
                        <span>Indonesia</span>
                    </a>
                </div>
            </div>

            <!-- User Account Dropdown -->
            <div class="nav-dropdown user-menu">
                <button class="nav-dropdown-toggle">
                    <div class="user-avatar">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <span class="user-name">{{ auth()->user()->getFullname() }}</span>
                    <i class="fas fa-chevron-down dropdown-arrow"></i>
                </button>
                <div class="nav-dropdown-menu">
                    <div class="dropdown-header">
                        <div class="user-info">
                            <div class="user-name">{{ auth()->user()->getFullname() }}</div>
                            <div class="user-email">{{ auth()->user()->email }}</div>
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('settings.index') }}" class="dropdown-item">
                        <i class="fas fa-cog"></i>
                        <span>{{ __('settings.title') }}</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('logout') }}" class="dropdown-item"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>{{ __('common.Logout') }}</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Navbar Variables */
        :root {
            --navbar-height: 70px;
            --navbar-bg: #ffffff;
            --navbar-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            --navbar-text: #333333;
            --navbar-hover: #f5f7ff;
            --primary-color: #4361ee;
            --border-radius: 8px;
            --transition: all 0.3s ease;
        }

        /* Navbar Styles */
        .main-navbar {
            height: var(--navbar-height);
            background: var(--navbar-bg);
            box-shadow: var(--navbar-shadow);
            position: sticky;
            top: 0;
            z-index: 1001;
            width: 100%;
        }

        .navbar-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 100%;
            padding: 0 1.5rem;
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .sidebar-toggle {
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--navbar-text);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: var(--border-radius);
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
        }

        .sidebar-toggle:hover {
            background: var(--navbar-hover);
            color: var(--primary-color);
        }

        .nav-brand {
            font-weight: 600;
            color: var(--navbar-text);
            text-decoration: none;
            font-size: 1.1rem;
            display: none;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .nav-dropdown {
            position: relative;
        }

        .nav-dropdown-toggle {
            display: flex;
            align-items: center;
            background: none;
            border: none;
            padding: 0.5rem 0.75rem;
            border-radius: var(--border-radius);
            color: var(--navbar-text);
            cursor: pointer;
            transition: var(--transition);
            gap: 0.5rem;
        }

        .nav-dropdown-toggle:hover {
            background: var(--navbar-hover);
        }

        .user-avatar {
            font-size: 1.25rem;
            color: var(--primary-color);
        }

        .user-name {
            font-weight: 600;
            font-size: 0.85rem;
        }

        .lang-code {
            font-weight: 600;
            font-size: 0.85rem;
        }

        .dropdown-arrow {
            font-size: 0.75rem;
            transition: var(--transition);
        }

        .nav-dropdown:hover .dropdown-arrow {
            transform: rotate(180deg);
        }

        .nav-dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            width: 250px;
            background: var(--navbar-bg);
            border-radius: var(--border-radius);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 0.5rem 0;
            margin-top: 0.5rem;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: var(--transition);
            z-index: 1000;
        }

        .nav-dropdown:hover .nav-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-header {
            padding: 0.75rem 1rem;
            margin-bottom: 0.25rem;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-email {
            font-size: 0.8rem;
            color: #666;
            margin-top: 0.25rem;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dropdown-divider {
            height: 1px;
            background: #f0f0f0;
            margin: 0.5rem 0;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: var(--navbar-text);
            text-decoration: none;
            transition: var(--transition);
            gap: 0.75rem;
        }

        .dropdown-item:hover {
            background: var(--navbar-hover);
            color: var(--primary-color);
        }

        .dropdown-item i {
            width: 18px;
            text-align: center;
        }

        /* Responsive Design */
        @media (min-width: 768px) {
            .nav-brand {
                display: block;
            }
        }

        @media (max-width: 767px) {
            .navbar-container {
                padding: 0 1rem;
            }

            .user-name {
                display: none;
            }

            .lang-code {
                display: none;
            }

            .nav-dropdown-toggle {
                padding: 0.5rem;
            }

            .nav-dropdown-menu {
                right: 0;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebarToggle = document.getElementById('sidebarToggle');

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function () {
                    const event = new CustomEvent('toggleSidebar');
                    document.dispatchEvent(event);
                });
            }
        });
    </script>
</nav>