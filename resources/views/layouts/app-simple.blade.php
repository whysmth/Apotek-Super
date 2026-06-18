<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apotek Super - Modern POS</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3 CDN (Lebih modern dan stabil) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary-color: #0d9488;      /* Teal 600 */
            --primary-light: #f0fdfa;      /* Teal 50 */
            --primary-hover: #0f766e;      /* Teal 700 */
            --secondary-color: #4f46e5;    /* Indigo 600 */
            --bg-color: #f8fafc;           /* Slate 50 */
            --card-bg: #ffffff;
            --text-dark: #0f172a;          /* Slate 900 */
            --text-muted: #64748b;         /* Slate 500 */
            --border-color: #e2e8f0;       /* Slate 200 */
            --success-color: #10b981;      /* Emerald 500 */
            --warning-color: #f59e0b;      /* Amber 500 */
            --danger-color: #ef4444;       /* Red 500 */
            
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.05), 0 1px 2px rgba(0,0,0,0.02);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -2px rgba(0,0,0,0.05);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.05), 0 4px 6px -4px rgba(0,0,0,0.05);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Modern Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Modern Top Navbar */
        .modern-nav {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 12px 0;
            transition: all 0.3s ease;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.25rem;
            color: var(--primary-color) !important;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-link {
            font-weight: 600;
            color: var(--text-muted) !important;
            padding: 8px 16px !important;
            border-radius: var(--radius-sm);
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
            background-color: var(--primary-light);
        }

        .nav-link.active {
            color: var(--primary-color) !important;
            background-color: var(--primary-light);
        }

        /* Container Padding */
        .main-container {
            flex: 1;
            padding: 32px 0;
        }

        /* Card Custom Styling */
        .card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .card:hover {
            box-shadow: var(--shadow-md);
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border-color);
            padding: 20px 24px;
            font-weight: 700;
            font-size: 1.15rem;
        }

        .card-body {
            padding: 24px;
        }

        /* Button Custom Styling */
        .btn {
            font-weight: 600;
            padding: 10px 20px;
            border-radius: var(--radius-md);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-1px);
        }

        .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }

        .btn-success:hover, .btn-success:focus {
            background-color: #059669;
            border-color: #059669;
            transform: translateY(-1px);
        }

        /* Custom Badges */
        .badge {
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 50px;
        }
        
        .bg-success-light {
            background-color: #d1fae5;
            color: #065f46;
        }
        .bg-primary-light {
            background-color: var(--primary-light);
            color: var(--primary-hover);
        }
        .bg-danger-light {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .bg-warning-light {
            background-color: #fef3c7;
            color: #92400e;
        }

        /* Table Styling */
        .table-responsive {
            border-radius: var(--radius-md);
            overflow: hidden;
            border: 1px solid var(--border-color);
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            background-color: #f8fafc;
            color: var(--text-muted);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            padding: 14px 16px;
            border-bottom: 1px solid var(--border-color);
        }

        .table td {
            padding: 16px;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color);
        }

        /* Alert Styling */
        .alert {
            border: none;
            border-radius: var(--radius-md);
            padding: 16px 20px;
            box-shadow: var(--shadow-sm);
        }

        /* Custom Utility Styles */
        .text-muted-custom {
            color: var(--text-muted);
        }
    </style>
    @yield('styles')
</head>
<body>
    
    <!-- Modern Navigation -->
    <nav class="navbar navbar-expand-lg modern-nav">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="bi bi-capsule text-primary"></i> {{ \App\Models\Setting::get('shop_name', 'Apotek Super') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto gap-1 mt-2 mt-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('medicines*') ? 'active' : '' }}" href="{{ route('medicines.index') }}">
                            <i class="bi bi-medicine-bottle"></i> Data Obat
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('sales*') ? 'active' : '' }}" href="{{ route('sales.index') }}">
                            <i class="bi bi-cart3"></i> Transaksi Penjualan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('settings*') ? 'active' : '' }}" href="{{ route('settings.index') }}">
                            <i class="bi bi-gear"></i> Pengaturan
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-container">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill fs-5"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>