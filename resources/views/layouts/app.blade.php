<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Laravel App')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Desktop Sidebar Styles */
        .sidebar {
            min-height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background: #0d6efd;
            transition: all 0.3s;
            z-index: 1000;
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 15px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar .nav-link.active {
            color: white;
            background: rgba(255, 255, 255, 0.2);
        }

        .sidebar-brand {
            padding: 20px;
            color: white;
            text-decoration: none;
            font-size: 1.5rem;
            font-weight: bold;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            cursor: pointer;
        }

        .sidebar-brand:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }

        .main-content {
            margin-left: 250px;
            transition: all 0.3s;
            min-height: 100vh;
        }

        .main-content.expanded {
            margin-left: 70px;
        }

        .sidebar-text {
            opacity: 1;
            transition: opacity 0.3s;
        }

        .sidebar.collapsed .sidebar-text {
            opacity: 0;
        }

        .user-section {
            position: absolute;
            bottom: 0;
            width: 100%;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Mobile Navbar Styles */
        .mobile-navbar {
            display: none;
        }

        .navbar-brand-mobile {
            color: black !important;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .navbar-toggler {
            border: none;
            padding: 4px 8px;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.8%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        .mobile-nav-item:last-child {
            border-bottom: none;
        }

        .mobile-nav-link {
            color: black !important;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.3s;
        }

        .mobile-nav-link:hover {
            color: black !important;
            background: rgba(255, 255, 255, 0.1);
        }

        .mobile-nav-link i {
            margin-right: 8px;
            width: 20px;
        }

        .mobile-user-section {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 8px;
            padding-top: 8px;
        }

        @media (max-width: 768px) {

            /* Hide desktop sidebar */
            .sidebar {
                display: none;
            }

            /* Show mobile navbar */
            .mobile-navbar {
                display: block;
            }

            /* Adjust main content */
            .main-content {
                margin-left: 0;
                padding-top: 76px;
                /* Height of navbar */
            }
        }

        @media (min-width: 769px) {

            /* Ensure mobile navbar is hidden on desktop */
            .mobile-navbar {
                display: none !important;
            }
        }
    </style>
</head>

<body class="bg-light">
    <!-- Mobile Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top mobile-navbar" style="border-radius: 8px; backdrop-filter: blur(20px); background-color: rgba(255, 255, 255, 0.5); box-shadow: 0 1px 12px rgba(0, 0, 0, 0.25); border: 1px solid rgba(255, 255, 255, 0.3);">
        <div class="container-fluid">
            <a class="navbar-brand navbar-brand-mobile d-flex align-items-center" href="#">
                <i class="fas fa-tools me-2"></i>
                <span>Mogok</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mobileNavContent"
                aria-controls="mobileNavContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon" style="filter: invert(1) brightness(0.2);"></span>
            </button>
            <div class="collapse navbar-collapse" id="mobileNavContent">
                <div class="navbar-nav w-100 mt-3">
                    @auth
                        <!-- Main Navigation -->
                        <div class="mobile-nav-item">
                            <a class="mobile-nav-link" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i>
                                <span>Dashboard</span>
                            </a>
                        </div>

                        <div class="mobile-nav-item">
                            <a class="mobile-nav-link" href="{{ route('parts.index') }}">
                                <i class="fas fa-cogs"></i>
                                <span>Part</span>
                            </a>
                        </div>

                        <div class="mobile-nav-item">
                            <a class="mobile-nav-link" href="{{ route('categories.index') }}">
                                <i class="fas fa-list"></i>
                                <span>Kategori</span>
                            </a>
                        </div>

                        <div class="mobile-nav-item">
                            <a class="mobile-nav-link" href="{{ route('compatibles.index') }}">
                                <i class="fas fa-bicycle"></i>
                                <span>Kecocokan</span>
                            </a>
                        </div>
                        <div class="mobile-nav-item">
                            <a class="mobile-nav-link" href="{{ route('part-barcodes.index') }}">
                                <i class="fas fa-barcode"></i>
                                <span>Part Barcodes</span>
                            </a>
                        </div>
                        <div class="mobile-nav-item">
                            <a class="mobile-nav-link" href="{{ route('transactions.checkout') }}">
                                <i class="fas fa-shopping-cart"></i>
                                <span>Checkout</span>
                            </a>
                        </div>
                        <div class="mobile-nav-item">
                            <a class="mobile-nav-link" href="{{ route('transactions.history') }}">
                                <i class="fas fa-history"></i>
                                <span>Transaction History</span>
                            </a>
                        </div>

                        <!-- User Section -->
                        <div class="mobile-user-section">
                            <div class="mobile-nav-item">
                                <div class="mobile-nav-link">
                                    <i class="fas fa-user"></i>
                                    <span>{{ Auth::user()->name }}</span>
                                </div>
                            </div>
                            <div class="mobile-nav-item">
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="mobile-nav-link w-100 border-0 bg-transparent text-start">
                                        <i class="fas fa-sign-out-alt"></i>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="mobile-nav-item">
                            <a class="mobile-nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt"></i>
                                <span>Login</span>
                            </a>
                        </div>

                        <div class="mobile-nav-item">
                            <a class="mobile-nav-link" href="{{ route('register') }}">
                                <i class="fas fa-user-plus"></i>
                                <span>Register</span>
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Desktop Sidebar -->
    <div class="sidebar" id="sidebar">
        <!-- Brand as Toggle -->
        <div class="sidebar-brand d-flex align-items-center text-decoration-none" id="toggleSidebar">
            <i class="fas fa-tools me-2"></i>
            <span class="sidebar-text">Mogok</span>
        </div>

        <!-- Navigation -->
        <nav class="nav flex-column h-100">
            @auth
                <!-- Main Navigation -->
                <a class="nav-link d-flex align-items-center" href="{{ route('dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    <span class="sidebar-text">Dashboard</span>
                </a>

                <a class="nav-link d-flex align-items-center" href="{{ route('parts.index') }}">
                    <i class="fas fa-cogs me-2"></i>
                    <span class="sidebar-text">Part</span>
                </a>

                <a class="nav-link d-flex align-items-center" href="{{ route('categories.index') }}">
                    <i class="fas fa-list me-2"></i>
                    <span class="sidebar-text">Kategori</span>
                </a>

                <a class="nav-link d-flex align-items-center" href="{{ route('compatibles.index') }}">
                    <i class="fas fa-bicycle me-2"></i>
                    <span class="sidebar-text">Kecocokan</span>
                </a>
                <a class="nav-link d-flex align-items-center" href="{{ route('part-barcodes.index') }}">
                    <i class="fas fa-barcode me-2"></i>
                    <span class="sidebar-text">Part Barcodes</span>
                </a>
                <a class="nav-link d-flex align-items-center" href="{{ route('transactions.checkout') }}">
                    <i class="fas fa-shopping-cart me-2"></i>
                    <span class="sidebar-text">Checkout</span>
                </a>
                <a class="nav-link d-flex align-items-center" href="{{ route('transactions.history') }}">
                    <i class="fas fa-history me-2"></i>
                    <span class="sidebar-text">Transaction History</span>
                </a>

                <!-- User Section -->
                <div class="user-section mt-auto">
                    <div class="nav-link d-flex align-items-center">
                        <i class="fas fa-user me-2"></i>
                        <span class="sidebar-text">{{ Auth::user()->name }}</span>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="nav-link d-flex align-items-center w-100 border-0 bg-transparent text-start"
                            style="color: rgba(255, 255, 255, 0.8);">
                            <i class="fas fa-sign-out-alt me-2"></i>
                            <span class="sidebar-text">Logout</span>
                        </button>
                    </form>
                </div>
            @else
                <a class="nav-link d-flex align-items-center" href="{{ route('login') }}">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    <span class="sidebar-text">Login</span>
                </a>

                <a class="nav-link d-flex align-items-center" href="{{ route('register') }}">
                    <i class="fas fa-user-plus me-2"></i>
                    <span class="sidebar-text">Register</span>
                </a>
            @endauth
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Alert Messages -->
        <div class="container-fluid mt-3">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>

        <!-- Main Content Area -->
        <main class="container-fluid mt-4">
            <!-- Breadcrumb -->
            @if(View::hasSection('breadcrumb'))
                <nav aria-label="breadcrumb" class="mt-2">
                    <ol class="breadcrumb">
                        @yield('breadcrumb')
                    </ol>
                </nav>
            @endif
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const toggleBtn = document.getElementById('toggleSidebar');

            // Only handle desktop sidebar toggle
            if (toggleBtn && window.innerWidth > 768) {
                toggleBtn.addEventListener('click', function () {
                    sidebar.classList.toggle('collapsed');
                    mainContent.classList.toggle('expanded');
                });
            }

            // Handle window resize for desktop sidebar
            window.addEventListener('resize', function () {
                if (window.innerWidth > 768) {
                    // Reset any mobile classes that might be applied
                    if (sidebar.classList.contains('collapsed')) {
                        mainContent.classList.add('expanded');
                    } else {
                        mainContent.classList.remove('expanded');
                    }
                } else {
                    // On mobile, ensure desktop classes are removed
                    mainContent.classList.remove('expanded');
                }
            });

            // Auto-close mobile navbar when clicking on a link
            const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');
            const navbarCollapse = document.getElementById('mobileNavContent');

            mobileNavLinks.forEach(link => {
                link.addEventListener('click', function () {
                    if (window.innerWidth <= 768 && navbarCollapse.classList.contains('show')) {
                        const bsCollapse = new bootstrap.Collapse(navbarCollapse);
                        bsCollapse.hide();
                    }
                });
            });
        });
    </script>
    @stack('scripts')
</body>

</html>