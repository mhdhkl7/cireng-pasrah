<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Pesan Cireng Goreng Online - Cireng Pasrah">
    <title>@yield('title', 'Selamat Datang') | Cireng Pasrah 🍟</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary: #0ea5e9;
            --primary-dark: #0284c7;
            --primary-light: #e0f2fe;
            --primary-lighter: #bae6fd;
            --secondary-color: #10b981;
            --secondary-dark: #059669;
            --secondary-light: #d1fae5;
            --accent: #06b6d4;
            --bg: #ffffff;
            --card: #ffffff;
            --text: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --radius: 16px;
            --radius-sm: 8px;
            --shadow: 0 1px 3px rgba(14,165,233,0.08), 0 1px 2px rgba(16,185,129,0.04);
            --shadow-md: 0 4px 6px -1px rgba(14,165,233,0.10), 0 2px 4px -1px rgba(16,185,129,0.06);
            --shadow-lg: 0 20px 25px -5px rgba(14,165,233,0.12), 0 10px 10px -5px rgba(16,185,129,0.05);
            --gradient: linear-gradient(135deg, #0ea5e9, #10b981);
            --gradient-light: linear-gradient(135deg, #e0f2fe, #d1fae5);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #ffffff;
            color: var(--text);
            min-height: 100vh;
        }

        /* Navbar */
        .navbar {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(14,165,233,0.15);
            position: sticky;
            top: 0;
            z-index: 100;
            padding: 0;
            box-shadow: 0 2px 20px rgba(14,165,233,0.08);
        }

        .navbar-inner {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 24px;
            height: 72px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .brand-logo {
            width: 48px; height: 48px;
            background: var(--gradient);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(14,165,233,0.35);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .brand-logo:hover {
            transform: rotate(-5deg) scale(1.05);
            box-shadow: 0 8px 24px rgba(14,165,233,0.45);
        }

        .brand-logo img {
            width: 100%; height: 100%;
            object-fit: cover;
            border-radius: 14px;
        }

        .brand-text h1 {
            font-size: 1.05rem;
            font-weight: 800;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1.1;
        }

        .brand-text small {
            font-size: 0.65rem;
            color: var(--text-muted);
            font-weight: 400;
        }

        .navbar-nav {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
            text-decoration: none;
            transition: all 0.2s;
        }

        .nav-link:hover {
            background: var(--gradient-light);
            color: var(--primary-dark);
        }
        .nav-link.active {
            background: var(--gradient-light);
            color: var(--primary-dark);
            box-shadow: 0 2px 8px rgba(14,165,233,0.15);
        }

        .cart-badge {
            background: var(--gradient);
            color: #fff;
            border-radius: 50%;
            width: 18px; height: 18px;
            font-size: 0.65rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-nav-logout {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--danger);
            background: none;
            border: none;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-nav-logout:hover { background: #fee2e2; }

        .user-greeting {
            font-size: 0.8rem;
            color: var(--text-muted);
            padding: 0 8px;
        }

        /* Main Container */
        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .page-body {
            padding: 32px 0;
        }

        /* Alert */
        .alert {
            padding: 12px 18px;
            border-radius: var(--radius-sm);
            margin-bottom: 20px;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-success { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
        .alert-error   { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

        /* Card */
        .card {
            background: var(--card);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 20px;
            border-radius: 10px;
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
            box-shadow: 0 4px 12px rgba(14,165,233,0.3);
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(14,165,233,0.4); }

        .btn-outline {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }
        .btn-outline:hover { background: var(--primary-light); }

        .btn-danger { background: var(--danger); color: #fff; }
        .btn-danger:hover { background: #b91c1c; }

        .btn-secondary { background: #f8fafc; color: var(--text); border: 1px solid var(--border); }
        .btn-secondary:hover { background: var(--border); }

        .btn-sm { padding: 6px 12px; font-size: 0.78rem; }
        .btn-block { width: 100%; justify-content: center; }

        /* Form */
        .form-group { margin-bottom: 18px; }

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
            padding: 11px 14px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-size: 0.875rem;
            font-family: 'Inter', sans-serif;
            color: var(--text);
            background: #fff;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(14,165,233,0.12);
        }

        .form-error {
            font-size: 0.78rem;
            color: var(--danger);
            margin-top: 5px;
        }

        /* Status Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 600;
        }

        .badge-pending    { background: #fef3c7; color: #92400e; }
        .badge-diproses   { background: #dbeafe; color: #1e40af; }
        .badge-siap       { background: #d1fae5; color: #065f46; }
        .badge-selesai    { background: #dcfce7; color: #14532d; }
        .badge-dibatalkan { background: #fee2e2; color: #991b1b; }
        .badge-lunas      { background: #d1fae5; color: #065f46; }
        .badge-belum      { background: #fef3c7; color: #92400e; }

        /* Pagination */
        .pagination {
            display: flex;
            gap: 4px;
            justify-content: center;
            padding: 20px 0;
        }

        .pagination a, .pagination span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 8px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
            color: var(--text);
            background: var(--card);
            border: 1px solid var(--border);
            transition: all 0.2s;
        }

        .pagination a:hover { border-color: var(--primary); color: var(--primary); }
        .pagination .active-page { background: var(--gradient); color: #fff; border-color: var(--primary); }

        /* Footer */
        .footer {
            background: linear-gradient(135deg, #0f172a, #0c1a2e);
            color: #94a3b8;
            text-align: center;
            padding: 24px;
            font-size: 0.8rem;
            margin-top: 48px;
        }

        .footer strong { color: var(--primary); }

        /* Gradient text utility */
        .gradient-text {
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-inner">
            <a href="{{ route('katalog.index') }}" class="navbar-brand">
                <div class="brand-logo">
                    <img src="{{ asset('images/cireng_icon.png') }}" alt="Logo Cireng">
                </div>
                <div class="brand-text">
                    <h1>Cireng Pasrah</h1>
                    <small>Enak, Renyah, Halal</small>
                </div>
            </a>

            <div class="navbar-nav">
                <span class="user-greeting">Halo, {{ auth()->user()->name }}!</span>

                <a href="{{ route('katalog.index') }}"
                   class="nav-link {{ request()->routeIs('katalog.*') ? 'active' : '' }}">
                    🏪 Katalog
                </a>

                <a href="{{ route('keranjang.index') }}"
                   class="nav-link {{ request()->routeIs('keranjang.*') ? 'active' : '' }}">
                    🛒 Keranjang
                    @php $jumlahKeranjang = count(session('keranjang', [])); @endphp
                    @if($jumlahKeranjang > 0)
                        <span class="cart-badge">{{ $jumlahKeranjang }}</span>
                    @endif
                </a>

                <a href="{{ route('pesanan.index') }}"
                   class="nav-link {{ request()->routeIs('pesanan.*') ? 'active' : '' }}">
                    📋 Pesanan Saya
                </a>

                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-nav-logout">🚪 Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Page Body -->
    <main>
        <div class="container">
            <div class="page-body">
                @if(session('success'))
                    <div class="alert alert-success">✅ {{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-error">❌ {{ session('error') }}</div>
                @endif

                @yield('content')
            </div>
        </div>
    </main>

    <footer class="footer">
        <p>&copy; {{ date('Y') }} <strong>Cireng Pasrah</strong>. Semua Hak Dilindungi.</p>
        <p style="margin-top:4px;">Made with ❤️ for UMKM Indonesia</p>
    </footer>

    @yield('scripts')
</body>
</html>
