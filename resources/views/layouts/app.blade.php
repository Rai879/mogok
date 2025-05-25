<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Laravel App')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
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
        }
        
        .sidebar-brand:hover {
            color: white;
        }
        
        .main-content {
            margin-left: 250px;
            transition: all 0.3s;
            min-height: 100vh;
        }
        
        .main-content.expanded {
            margin-left: 70px;
        }
        
        .sidebar-brand:hover {
            background: rgba(255, 255, 255, 0.1);
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
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .mobile-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
            }
            
            .mobile-overlay.show {
                display: block;
            }
        }
    </style>
</head>
<body class="bg-light">
    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <!-- Brand as Toggle -->
        <div class="sidebar-brand d-flex align-items-center text-decoration-none" id="toggleSidebar" style="cursor: pointer;">
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
                    <span class="sidebar-text">Parts</span>
                </a>
                
                <a class="nav-link d-flex align-items-center" href="{{ route('categories.index') }}">
                    <i class="fas fa-list me-2"></i>
                    <span class="sidebar-text">Categories</span>
                </a>
                
                <a class="nav-link d-flex align-items-center" href="{{ route('compatibles.index') }}">
                    <i class="fas fa-car me-2"></i>
                    <span class="sidebar-text">Compatibles</span>
                </a>
                <!-- User Section -->
                <div class="user-section mt-auto">
                    <div class="nav-link d-flex align-items-center">
                        <i class="fas fa-user me-2"></i>
                        <span class="sidebar-text">{{ Auth::user()->name }}</span>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="nav-link d-flex align-items-center w-100 border-0 bg-transparent text-start" style="color: rgba(255, 255, 255, 0.8);">
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
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const toggleBtn = document.getElementById('toggleSidebar');
            const mobileOverlay = document.getElementById('mobileOverlay');
            
            // Toggle sidebar when clicking brand
            toggleBtn.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    // Mobile behavior
                    sidebar.classList.toggle('show');
                    mobileOverlay.classList.toggle('show');
                } else {
                    // Desktop behavior
                    sidebar.classList.toggle('collapsed');
                    mainContent.classList.toggle('expanded');
                }
            });
            
            // Close sidebar when clicking overlay (mobile)
            mobileOverlay.addEventListener('click', function() {
                sidebar.classList.remove('show');
                mobileOverlay.classList.remove('show');
            });
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('show');
                    mobileOverlay.classList.remove('show');
                } else {
                    sidebar.classList.remove('collapsed');
                    mainContent.classList.remove('expanded');
                }
            });
        });
    </script>
</body>
</html>