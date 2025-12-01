<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', config('app.name'))</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Font: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a56d4;
            --secondary: #6c757d;
            --success: #198754;
            --info: #0dcaf0;
            --warning: #ffc107;
            --danger: #dc3545;
            --light: #f8f9fa;
            --dark: #212529;
            --sidebar-width: 260px;
            --sidebar-bg: #1e2a4a;
            --sidebar-color: #fff;
            --sidebar-hover: rgba(255, 255, 255, 0.1);
            --header-height: 70px;
            --content-padding: 1.5rem;
            --border-radius: 10px;
            --transition: all 0.3s ease;
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            --card-bg: #fff;
            --body-bg: #f5f7ff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--body-bg);
            color: var(--dark);
            overflow-x: hidden;
        }

        /* Layout */
        .app-wrapper {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        /* Main Content */
        .app-main {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: var(--transition);
            display: flex;
            flex-direction: column;
            min-width: 0;
            width: calc(100% - var(--sidebar-width));
        }

        .app-main.sidebar-collapsed {
            margin-left: 70px;
            width: calc(100% - 70px);
        }

        /* Content */
        .app-content {
            padding: var(--content-padding);
            flex: 1;
            overflow-x: auto;
        }

        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding: 1.5rem 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--dark);
        }

        .content-actions {
            display: flex;
            gap: 0.75rem;
        }

        /* Alert */
        .alert {
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            border-radius: var(--border-radius);
            border-left: 4px solid;
        }

        .alert-success {
            background-color: rgba(25, 135, 84, 0.1);
            border-color: var(--success);
            color: #0f5132;
        }

        .alert-error {
            background-color: rgba(220, 53, 69, 0.1);
            border-color: var(--danger);
            color: #842029;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            border-radius: var(--border-radius);
            border: none;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            font-size: 0.9rem;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            box-shadow: 0 2px 4px rgba(67, 97, 238, 0.3);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(67, 97, 238, 0.4);
        }

        .btn-icon {
            margin-right: 0.5rem;
        }

        /* Mobile overlay */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .overlay.active {
            display: block;
            opacity: 1;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .app-main {
                margin-left: 0;
                width: 100%;
            }
        }

        @media (max-width: 768px) {
            .content-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .content-actions {
                width: 100%;
                justify-content: flex-end;
            }
        }

        /* Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.3s ease;
        }
    </style>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @yield('css')
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            feather.replace();
        });

        window.APP = <?php echo json_encode([
    'currency_symbol' => config('settings.currency_symbol'),
    'warning_quantity' => config('settings.warning_quantity')
]) ?>;
    </script>
</head>

<body>
    <div class="app-wrapper">
        @include('layouts.partials.sidebar')

        <!-- Main Content -->
        <div class="app-main">
            @include('layouts.partials.navbar')

            <!-- Content Wrapper -->
            <div class="app-content">
                <!-- Content Header -->
                <div class="content-header">
                    <h1 class="page-title">@yield('content-header')</h1>
                    <div class="content-actions">
                        @yield('content-actions')
                    </div>
                </div>

                <!-- Alerts -->
                @include('layouts.partials.alert.success')
                @include('layouts.partials.alert.error')

                <!-- Main Content -->
                <section class="content fade-in">
                    @yield('content')
                </section>
            </div>

            @include('layouts.partials.footer')
        </div>
    </div>

    <!-- Mobile overlay -->
    <div class="overlay"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.querySelector('.app-sidebar');
            const appMain = document.querySelector('.app-main');
            const overlay = document.querySelector('.overlay');

            // Listen for toggle event from navbar
            document.addEventListener('toggleSidebar', function () {
                const isMobile = window.innerWidth <= 992;

                if (isMobile) {
                    // Mobile behavior - slide sidebar
                    sidebar.classList.toggle('mobile-open');
                    overlay.classList.toggle('active');
                } else {
                    // Desktop behavior - collapse sidebar
                    sidebar.classList.toggle('collapsed');
                    appMain.classList.toggle('sidebar-collapsed');
                }
            });

            // Close sidebar when clicking overlay
            if (overlay) {
                overlay.addEventListener('click', function () {
                    sidebar.classList.remove('mobile-open');
                    overlay.classList.remove('active');
                });
            }

            // Handle window resize
            let resizeTimer;
            window.addEventListener('resize', function () {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function () {
                    const isMobile = window.innerWidth <= 992;

                    if (!isMobile) {
                        // Remove mobile classes when switching to desktop
                        sidebar.classList.remove('mobile-open');
                        overlay.classList.remove('active');
                    } else {
                        // Remove desktop collapse when switching to mobile
                        sidebar.classList.remove('collapsed');
                        appMain.classList.remove('sidebar-collapsed');
                    }
                }, 250);
            });
        });
    </script>

    @yield('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('model')
</body>

</html>