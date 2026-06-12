<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Admin Panel - Cireng Goreng Pasrah">
    <title>@yield('title', 'Admin Panel') | Cireng Pasrah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary: #0284c7;
            --primary-dark: #0369a1;
            --primary-light: #e0f2fe;
            --secondary: #1e293b;
            --sidebar-bg: #0f172a;
            --sidebar-text: #94a3b8;
            --sidebar-active: #0ea5e9;
            --bg: #f1f5f9;
            --card: #ffffff;
            --text: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
            --radius: 12px;
            --shadow: 0 1px 3px rgba(2,132,199,0.1), 0 1px 2px rgba(2,132,199,0.06);
            --shadow-lg: 0 10px 15px -3px rgba(2,132,199,0.1), 0 4px 6px -2px rgba(2,132,199,0.05);
            --gradient: linear-gradient(135deg, #0284c7, #0d9488);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: var(--sidebar-bg);
            min-height: 100vh;
            position: fixed;
            left: 0; top: 0;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
            z-index: 100;
        }

        .sidebar-brand {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .sidebar-brand h1 {
            font-size: 1.25rem;
            font-weight: 800;
            color: #fff;
            line-height: 1.2;
        }

        .sidebar-brand span {
            color: var(--primary);
        }

        .sidebar-brand small {
            display: block;
            color: var(--sidebar-text);
            font-size: 0.7rem;
            font-weight: 400;
            margin-top: 2px;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            overflow-y: auto;
        }

        .nav-section-label {
            font-size: 0.65rem;
            font-weight: 600;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 12px 8px 6px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 8px;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            margin-bottom: 2px;
        }

        .sidebar-link:hover {
            background: rgba(255,255,255,0.05);
            color: #fff;
        }

        .sidebar-link.active {
            background: var(--gradient);
            color: #fff;
            box-shadow: 0 4px 12px rgba(2,132,199,0.3);
        }

        .sidebar-link .icon {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid rgba(255,255,255,0.05);
        }

        .admin-info {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 8px;
            background: rgba(255,255,255,0.05);
            margin-bottom: 8px;
        }

        .admin-avatar {
            width: 36px; height: 36px;
            background: var(--gradient);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; color: #fff; font-size: 0.875rem;
            flex-shrink: 0;
        }

        .admin-name {
            font-size: 0.8rem;
            font-weight: 600;
            color: #fff;
            line-height: 1.2;
        }

        .admin-role {
            font-size: 0.65rem;
            color: var(--sidebar-text);
        }

        .btn-logout {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 9px;
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.2);
            color: #f87171;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-logout:hover {
            background: rgba(239,68,68,0.2);
            color: #ef4444;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Topbar */
        .topbar {
            background: var(--card);
            border-bottom: 1px solid var(--border);
            padding: 0 28px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: var(--shadow);
        }

        .topbar-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text);
        }

        .topbar-subtitle {
            font-size: 0.75rem;
            color: var(--text-muted);
            font-weight: 400;
        }

        .page-body {
            padding: 28px;
            flex: 1;
        }

        /* Cards */
        .card {
            background: var(--card);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            overflow: hidden;
        }

        .card-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .card-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text);
        }

        .card-body {
            padding: 24px;
        }

        /* Alert Messages */
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-error   { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .alert-warning { background: #ccfbf1; color: #92400e; border: 1px solid #fde68a; }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: none;
            transition: all 0.2s;
            line-height: 1.4;
        }

        .btn-primary {
            background: var(--gradient);
            color: #fff;
            box-shadow: 0 2px 8px rgba(2,132,199,0.3);
        }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(2,132,199,0.4); }

        .btn-secondary { background: var(--bg); color: var(--text); border: 1px solid var(--border); }
        .btn-secondary:hover { background: var(--border); }

        .btn-danger { background: #ef4444; color: #fff; }
        .btn-danger:hover { background: #dc2626; }

        .btn-success { background: var(--success); color: #fff; }
        .btn-success:hover { background: #059669; }

        .btn-sm { padding: 5px 10px; font-size: 0.78rem; }

        /* Table */
        .table-wrapper { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f8fafc;
            padding: 12px 16px;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid var(--border);
        }

        td {
            padding: 13px 16px;
            font-size: 0.875rem;
            color: var(--text);
            border-bottom: 1px solid var(--border);
        }

        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #f8fafc; }

        /* Status Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 600;
        }

        .badge-pending    { background: #ccfbf1; color: #92400e; }
        .badge-diproses   { background: #dbeafe; color: #1e40af; }
        .badge-siap       { background: #d1fae5; color: #065f46; }
        .badge-selesai    { background: #dcfce7; color: #14532d; }
        .badge-dibatalkan { background: #fee2e2; color: #991b1b; }
        .badge-lunas      { background: #d1fae5; color: #065f46; }
        .badge-belum      { background: #ccfbf1; color: #92400e; }

        /* Form elements */
        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 6px;
        }

        .form-label .required { color: var(--danger); }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            font-size: 0.875rem;
            font-family: 'Inter', sans-serif;
            color: var(--text);
            background: #fff;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(14,165,233,0.1);
        }

        .form-error {
            font-size: 0.78rem;
            color: var(--danger);
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Pagination */
        .pagination {
            display: flex;
            gap: 4px;
            justify-content: center;
            padding: 16px;
        }

        .pagination a, .pagination span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
            color: var(--text);
            background: var(--bg);
            border: 1px solid var(--border);
            transition: all 0.2s;
        }

        .pagination a:hover { background: var(--primary-light); border-color: var(--primary); color: var(--primary-dark); }
        .pagination .active-page { background: var(--primary); color: #fff; border-color: var(--primary); }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <h1><img src="{{ asset('images/cireng_icon.png') }}" style="width:24px;height:24px;display:inline-block;vertical-align:middle;border-radius:6px;margin-right:6px;" alt=""> Cireng Pasrah</h1>
            <small>Admin Panel</small>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-label">Menu Utama</div>

            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="icon">📊</span> Dashboard
            </a>

            <div class="nav-section-label">Manajemen</div>

            <a href="{{ route('admin.produk.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.produk.*') ? 'active' : '' }}">
                <span class="icon">🍟</span> Produk Cireng
            </a>

            <a href="{{ route('admin.inventori.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.inventori.*') ? 'active' : '' }}">
                <span class="icon">📦</span> Inventori Stok
            </a>

            <a href="{{ route('admin.pesanan.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.pesanan.*') ? 'active' : '' }}">
                <span class="icon">🛒</span> Pesanan Masuk
            </a>

            <div class="nav-section-label">Pengguna</div>

            <a href="{{ route('admin.customer.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.customer.*') ? 'active' : '' }}">
                <span class="icon">👥</span> Data Pelanggan
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="admin-info">
                <div class="admin-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div>
                    <div class="admin-name">{{ auth()->user()->name }}</div>
                    <div class="admin-role">Administrator</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    🚪 Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <header class="topbar">
            <div>
                <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
                <div class="topbar-subtitle">@yield('page-subtitle', 'Panel Admin Cireng Pasrah')</div>
            </div>
            <div style="display:flex;align-items:center;gap:12px;">
                <span style="font-size:0.8rem;color:var(--text-muted);">{{ now()->format('l, d F Y') }}</span>
            </div>
        </header>

        <main class="page-body">
            @if(session('success'))
                <div class="alert alert-success">✅ {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">❌ {{ session('error') }}</div>
            @endif

            @yield('content')
        </main>
    </div>

    @yield('scripts')
</body>
</html>
