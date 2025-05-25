<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Laravel App')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="/">
            <i class="fas fa-tools me-2"></i>Mogok
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                @auth
                    {{-- searchbar --}}
                @endauth
            </ul>

            <ul class="navbar-nav ms-auto">
                @auth
                    <li class="nav-item ">
                        <a class="nav-link" title="Parts" href="{{ route('parts.index') }}">
                            <i class="fas fa-cogs me-1"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" title="Categories" href="{{ route('categories.index') }}">
                            <i class="fas fa-list me-1"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" title="Compatibles" href="{{ route('compatibles.index') }}">
                            <i class="fas fa-car me-1"></i>
                        </a>
                    </li>
                    <li class="nav-item dropdown ">
                        <a class="nav-link dropdown-toggle nav-link rounded-pill bg-white text-primary px-3 mx-1" title="Logged as {{ Auth::user()->name }}" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i>
                        </a>
                        <ul class="dropdown-menu ">
                            <li class="dropdown-item">Logged as {{ Auth::user()->name }}</li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-1"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>


    <!-- Alert Messages -->
    <div class="container mt-3">
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

    <!-- Main Content -->
    <main class="container mt-4">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>