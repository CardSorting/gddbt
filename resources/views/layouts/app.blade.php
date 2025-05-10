<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'DBT Learning Platform')</title>
    <meta name="description" content="@yield('meta_description', 'A gamified platform for learning Dialectical Behavior Therapy (DBT) skills.')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #4F46E5; /* Indigo */
            --secondary: #10B981; /* Emerald */
            --accent: #06B6D4; /* Cyan */
            --danger: #EF4444; /* Red */
            --success: #10B981; /* Green */
            --warning: #F59E0B; /* Amber */
            --dark: #111827; /* Gray 900 */
            --text: #374151; /* Gray 700 */
            --light-text: #6B7280; /* Gray 500 */
            --light-bg: #F9FAFB; /* Gray 50 */
            --border: #E5E7EB; /* Gray 200 */
        }
        
        body {
            font-family: 'Inter', sans-serif;
            color: var(--text);
            line-height: 1.6;
        }
        
        .bg-primary { background-color: var(--primary) !important; }
        .bg-secondary { background-color: var(--secondary) !important; }
        .bg-accent { background-color: var(--accent) !important; }
        .bg-light-custom { background-color: var(--light-bg) !important; }
        
        .text-primary { color: var(--primary) !important; }
        .text-secondary { color: var(--secondary) !important; }
        .text-accent { color: var(--accent) !important; }
        
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-primary:hover {
            background-color: #4338CA; /* Indigo 700 */
            border-color: #4338CA;
        }
        
        .btn-secondary {
            background-color: var(--secondary);
            border-color: var(--secondary);
        }
        
        .btn-secondary:hover {
            background-color: #059669; /* Emerald 700 */
            border-color: #059669;
        }
        
        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .navbar {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        .hero-section {
            padding: 7rem 0;
            background: linear-gradient(135deg, #4F46E5 0%, #06B6D4 100%);
            color: white;
        }
        
        .section-title {
            font-weight: 700;
            margin-bottom: 2rem;
        }
        
        .feature-card {
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .card-icon {
            width: 60px;
            height: 60px;
            margin-bottom: 1rem;
        }
        
        footer {
            background-color: var(--dark);
            color: white;
            padding: 3rem 0;
        }
        
        .section-padding {
            padding: 5rem 0;
        }
        
        /* Custom styles for different sections can be added here */
        @yield('custom_css')
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    DBT<span class="text-primary">Path</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">Home</a>
                        </li>
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn btn-primary text-white ms-2 px-4" href="{{ route('register') }}">Register</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('modules.index') }}">Modules</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('daily-goals.index') }}">Daily Goals</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="#">My Profile</a></li>
                                    <li><a class="dropdown-item" href="#">Settings</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main>
            @yield('content')
        </main>

        <footer class="pt-5 pb-4">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 mb-4">
                        <h5 class="text-white mb-3">DBT<span class="text-primary">Path</span></h5>
                        <p class="text-light">A gamified platform for learning and practicing Dialectical Behavior Therapy skills with daily goals and social support.</p>
                    </div>
                    <div class="col-lg-2 col-md-3 col-6 mb-4">
                        <h6 class="text-white mb-3">Modules</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2"><a href="#" class="text-light text-decoration-none">Mindfulness</a></li>
                            <li class="mb-2"><a href="#" class="text-light text-decoration-none">Distress Tolerance</a></li>
                            <li class="mb-2"><a href="#" class="text-light text-decoration-none">Emotion Regulation</a></li>
                            <li><a href="#" class="text-light text-decoration-none">Interpersonal Effectiveness</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-2 col-md-3 col-6 mb-4">
                        <h6 class="text-white mb-3">Resources</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2"><a href="#" class="text-light text-decoration-none">Blog</a></li>
                            <li class="mb-2"><a href="#" class="text-light text-decoration-none">FAQ</a></li>
                            <li class="mb-2"><a href="#" class="text-light text-decoration-none">Support</a></li>
                            <li><a href="#" class="text-light text-decoration-none">Contact</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-2 col-md-3 col-6 mb-4">
                        <h6 class="text-white mb-3">Legal</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2"><a href="#" class="text-light text-decoration-none">Terms</a></li>
                            <li class="mb-2"><a href="#" class="text-light text-decoration-none">Privacy</a></li>
                            <li><a href="#" class="text-light text-decoration-none">Cookies</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-2 col-md-3 col-6 mb-4">
                        <h6 class="text-white mb-3">Connect</h6>
                        <div class="d-flex">
                            <a href="#" class="text-light me-3"><i class="bi bi-facebook"></i></a>
                            <a href="#" class="text-light me-3"><i class="bi bi-twitter"></i></a>
                            <a href="#" class="text-light me-3"><i class="bi bi-instagram"></i></a>
                            <a href="#" class="text-light"><i class="bi bi-linkedin"></i></a>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <hr class="border-secondary">
                        <p class="text-center text-light mb-0">© {{ date('Y') }} DBTPath. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
