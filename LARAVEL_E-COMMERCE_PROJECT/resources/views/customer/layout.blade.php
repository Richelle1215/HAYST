<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard - LUMIÈRE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 260px;
            background: linear-gradient(180deg, #1a2332 0%, #2d3748 100%);
            color: white;
            padding: 0;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar-header {
            padding: 1.5rem;
            font-size: 1.5rem;
            font-weight: bold;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            background: rgba(0,0,0,0.2);
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 1rem 0;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .sidebar-menu li a:hover,
        .sidebar-menu li a.active {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left-color: #dc3545;
        }

        .sidebar-menu li a span {
            margin-right: 0.75rem;
            font-size: 1.2rem;
        }

        .logout-btn {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 1rem 1.5rem;
            background: #dc3545;
            color: white;
            border: none;
            text-align: center;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.3s;
        }

        .logout-btn:hover {
            background: #c82333;
        }

        .main-content {
            margin-left: 260px;
            padding: 2rem;
            min-height: 100vh;
        }

        .page-header {
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #dee2e6;
        }

        .page-header h1 {
            font-size: 1.75rem;
            font-weight: 600;
            color: #2d3748;
            margin: 0;
            display: flex;
            align-items: center;
        }

        .page-header h1 span {
            margin-right: 0.75rem;
            font-size: 2rem;
        }

        .user-avatar {
            position: fixed;
            top: 1.5rem;
            right: 2rem;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: #dc3545;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .dashboard-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: transform 0.3s, box-shadow 0.3s;
            border: 1px solid #e9ecef;
            height: 100%;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }

        .card-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.75rem;
        }

        .card-text {
            color: #718096;
            margin-bottom: 1.5rem;
        }

        .btn-custom {
            background: #dc3545;
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-custom:hover {
            background: #c82333;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
        }

        .welcome-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .welcome-section h2 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .welcome-section p {
            opacity: 0.9;
            margin: 0;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            Customer Panel
        </div>
        
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('customer.dashboard') }}">
                    <span></span> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('store.index') }}">
                    <span></span> Shop
                </a>
            </li>
            <li>
                <a href="{{ route('customer.cart.index') }}">
                    <span></span> My Cart
                </a>
            </li>
            <li>
                <a href="{{ route('customer.orders') }}">
                    <span></span> My Orders
                </a>
            </li>
            <li>
                <a href="{{ route('customer.profile') }}">
                    <span></span> Profile
                </a>
            </li>
        </ul>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                 Logout
            </button>
        </form>
    </div>

    <!-- Main content -->
    <main class="main-content">
        @yield('content')
    </main>

</body>
</html>