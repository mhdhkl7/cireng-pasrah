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
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --primary-light: #eff6ff;
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
            --radius: 12px;
            --radius-sm: 8px;
            --shadow: 0 1px 3px rgba(14,165,233,0.08), 0 1px 2px rgba(16,185,129,0.04);
            --shadow-md: 0 4px 6px -1px rgba(14,165,233,0.10), 0 2px 4px -1px rgba(16,185,129,0.06);
            --shadow-lg: 0 20px 25px -5px rgba(14,165,233,0.12), 0 10px 10px -5px rgba(16,185,129,0.05);
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
            background: var(--primary);
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
            color: var(--primary);
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
            background: var(--primary-light);
            color: var(--primary-dark);
        }
        .nav-link.active {
            background: var(--primary-light);
            color: var(--primary-dark);
            box-shadow: 0 2px 8px rgba(14,165,233,0.15);
        }

        .cart-badge {
            background: var(--primary);
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

        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-dark); transform: translateY(-1px); }

        .btn-outline {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }
        .btn-outline:hover { background: var(--primary-light); }

        .btn-danger { background: var(--danger); color: #fff; }
        .btn-danger:hover { background: #b91c1c; }

        .btn-secondary { background: #f8fafc; color: var(--text); border: 1px solid var(--border); }
        .btn-secondary:hover { background: #f1f5f9; }

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

        .badge-pending    { background: #ccfbf1; color: #92400e; }
        .badge-diproses   { background: #dbeafe; color: #1e40af; }
        .badge-siap       { background: #d1fae5; color: #065f46; }
        .badge-selesai    { background: #dcfce7; color: #14532d; }
        .badge-dibatalkan { background: #fee2e2; color: #991b1b; }
        .badge-lunas      { background: #d1fae5; color: #065f46; }
        .badge-belum      { background: #ccfbf1; color: #92400e; }

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
        .pagination .active-page { background: var(--primary); color: #fff; border-color: var(--primary); }

        /* Footer */
        .footer {
            background: #0f172a;
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

                <a href="{{ route('profil.index') }}"
                   class="nav-link {{ request()->routeIs('profil.*') ? 'active' : '' }}">
                    👤 Profil
                </a>

                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-nav-logout">🚪 Logout</button>
                </form>
            </div>
        </div>
    </nav>

    {{-- Session Timeout Warning --}}
    <div id="session-warning" style="display:none;position:fixed;bottom:20px;right:20px;z-index:999;background:#1c1917;color:#fff;padding:14px 20px;border-radius:12px;box-shadow:0 8px 24px rgba(0,0,0,0.25);font-size:0.83rem;max-width:280px;">
        <div style="font-weight:700;margin-bottom:4px;">⏰ Sesi akan berakhir!</div>
        <div>Tidak ada aktivitas selama <strong id="idle-minutes">14</strong> menit. Sesi otomatis berakhir dalam <strong id="countdown">60</strong> detik.</div>
        <button onclick="document.getElementById('session-warning').style.display='none'; resetIdle();"
                style="margin-top:10px;width:100%;padding:7px;background:var(--primary);color:#fff;border:none;border-radius:8px;font-weight:600;cursor:pointer;font-family:'Inter',sans-serif;">
            Tetap Login
        </button>
    </div>

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
<script>
// Session Idle Warning (14 menit warning, 15 menit server logout)
(function() {
    const IDLE_WARN_MS  = 14 * 60 * 1000; // warn at 14 min
    const WARN_DURATION = 60; // 60 seconds countdown
    let idleTimer, countdownTimer, countdown;
    const warning  = document.getElementById('session-warning');
    const countEl  = document.getElementById('countdown');

    function resetIdle() {
        clearTimeout(idleTimer);
        clearInterval(countdownTimer);
        if (warning) warning.style.display = 'none';
        idleTimer = setTimeout(showWarning, IDLE_WARN_MS);
    }

    function showWarning() {
        if (!warning) return;
        countdown = WARN_DURATION;
        warning.style.display = 'block';
        countdownTimer = setInterval(function() {
            countdown--;
            if (countEl) countEl.textContent = countdown;
            if (countdown <= 0) {
                clearInterval(countdownTimer);
                window.location.reload(); // Server will logout on next request
            }
        }, 1000);
    }

    // Events that reset idle timer
    ['mousemove','keydown','click','scroll','touchstart'].forEach(function(e) {
        document.addEventListener(e, resetIdle, true);
    });

    resetIdle(); // Start timer
    window.resetIdle = resetIdle;
})();
</script>
</body>
</html>
