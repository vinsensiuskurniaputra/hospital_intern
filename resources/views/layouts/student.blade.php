\student.blade.php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIM Magang RS')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        body {
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        
        /* Sidebar Style */
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            width: 240px;
            background-color: white;
            z-index: 100;
            padding: 20px 0;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        
        .sidebar-logo {
            padding: 0 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .sidebar .nav-link {
            color: #6c757d;
            padding: 0.75rem 1.5rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
        }
        
        .sidebar .nav-link:hover {
            color: #006644;
        }
        
        .sidebar .nav-link.active {
            color: #006644;
            background-color: #f0f8f1;
            border-left: 4px solid #006644;
            padding-left: calc(1.5rem - 4px);
        }
        
        .sidebar .nav-link i {
            margin-right: 0.75rem;
            font-size: 1.1rem;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 240px;
            padding-top: 70px;
            padding-bottom: 24px;
            min-height: 100vh;
        }
        
        /* Navbar Style */
        .top-navbar {
            position: fixed;
            top: 0;
            right: 0;
            left: 240px;
            z-index: 99;
            height: 60px;
            background-color: #fff;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            padding: 0 1rem;
        }
        
        .status-badge {
            background-color: #e9ecef;
            padding: 0.375rem 0.75rem;
            border-radius: 50px;
            font-size: 0.85rem;
        }
        
        .status-badge.active {
            background-color: #d1e7dd;
            color: #0f5132;
        }
        
        .avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        /* Card styling */
        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #f0f0f0;
            padding: 1rem 1.5rem;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        /* Footer */
        footer {
            font-size: 0.8rem;
            color: #6c757d;
            text-align: center;
            padding: 1rem 0;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    @include('components.student.sidebar')
    
    <!-- Navbar -->
    @include('components.student.navbar')
    
    <!-- Main Content -->
    <main class="main-content px-4">
        @yield('content')
        
        <footer>
            @2025 IK Polines
        </footer>
    </main>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>