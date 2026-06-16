<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Driver Panel') | Cireng Pasrah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --primary: #d97706;
            --primary-dark: #b45309;
            --bg: #fffbeb;
            --card: #ffffff;
            --text: #1c1917;
            --text-muted: #78716c;
            --border: #e7e5e4;
            --success: #10b981;
            --danger: #ef4444;
            --radius: 12px;
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; }

        .topbar {
            background: #1e293b;
            padding: 0 24px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 16px rgba(0,0,0,0.3);
        }
        .topbar-brand { display: flex; align-items: center; gap: 10px; }
        .brand-icon {
            width: 38px; height: 38px;
            background: var(--primary);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
        }
        .brand-text { font-size: 1rem; font-weight: 700; color: #fff; }
        .brand-text small { display: block; color: var(--primary); font-size: 0.65rem; font-weight: 500; }
        .topbar-nav { display: flex; align-items: center; gap: 8px; }
        .nav-link {
            display: flex; align-items: center; gap: 5px;
            padding: 7px 12px; border-radius: 8px;
            font-size: 0.83rem; font-weight: 600;
            color: #94a3b8; text-decoration: none;
            transition: all 0.2s;
        }
        .nav-link:hover, .nav-link.active { background: rgba(245,158,11,0.15); color: var(--primary); }
        .btn-logout {
            display: flex; align-items: center; gap: 6px;
            padding: 7px 12px; border-radius: 8px;
            font-size: 0.83rem; font-weight: 600;
            color: #f87171; background: rgba(239,68,68,0.1);
            border: none; cursor: pointer; font-family: 'Inter', sans-serif;
            transition: background 0.2s;
        }
        .btn-logout:hover { background: rgba(239,68,68,0.2); }
        .driver-badge {
            background: var(--primary);
            color: #fff;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 20px;
        }

        .page-body { max-width: 1000px; margin: 0 auto; padding: 28px 24px; }

        .alert {
            padding: 12px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex; align-items: center; gap: 8px;
        }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-error   { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

        .card {
            background: var(--card);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            overflow: hidden;
            margin-bottom: 20px;
        }
        .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }
        .card-title { font-size: 0.95rem; font-weight: 700; }
        .card-body { padding: 20px; }

        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 16px; border-radius: 8px;
            font-size: 0.83rem; font-weight: 600;
            cursor: pointer; text-decoration: none;
            border: none; transition: all 0.2s; font-family: 'Inter', sans-serif;
        }
        .btn-primary { background: var(--primary); color: #fff; box-shadow: 0 2px 8px rgba(245,158,11,0.3); }
        .btn-primary:hover { transform: translateY(-1px); }
        .btn-success { background: var(--success); color: #fff; }
        .btn-success:hover { background: #059669; }
        .btn-secondary { background: #f5f5f4; color: var(--text); border: 1px solid var(--border); }
        .btn-sm { padding: 5px 10px; font-size: 0.75rem; }

        .badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 20px; font-size: 0.7rem; font-weight: 600; }
        .badge-siap     { background: #d1fae5; color: #065f46; }
        .badge-diproses { background: #dbeafe; color: #1e40af; }
        .badge-selesai  { background: #dcfce7; color: #14532d; }
    </style>
    @yield('styles')
</head>
<body>
    <header class="topbar">
        <div class="topbar-brand">
            <div class="brand-icon">🚗</div>
            <div class="brand-text">
                Driver Panel
                <small>Cireng Pasrah</small>
            </div>
        </div>
        <div class="topbar-nav">
            <a href="{{ route('driver.dashboard') }}" class="nav-link {{ request()->routeIs('driver.dashboard') ? 'active' : '' }}">🏠 Dashboard</a>
            <a href="{{ route('driver.pesanan.index') }}" class="nav-link {{ request()->routeIs('driver.pesanan.*') ? 'active' : '' }}">📦 Pesanan</a>
            <a href="{{ route('driver.profil.index') }}" class="nav-link {{ request()->routeIs('driver.profil.*') ? 'active' : '' }}">👤 Profil</a>
            <span class="driver-badge">🚗 {{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">🚪 Logout</button>
            </form>
        </div>
    </header>

    <div class="page-body">
        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">❌ {{ session('error') }}</div>
        @endif

        @yield('content')
    </div>

    @yield('scripts')
</body>
</html>
