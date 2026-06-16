<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Pesan Cireng Goreng Online - Cireng Pasrah">
    <title>Cireng Pasrah | Enak, Renyah, Halal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: #ffffff;
            color: #0f172a;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .navbar {
            width: 100%;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(14,165,233,0.15);
            position: sticky;
            top: 0;
            z-index: 100;
            padding: 16px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1100px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .brand img {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            box-shadow: 0 4px 16px rgba(14,165,233,0.35);
        }

        .brand-text h1 {
            font-size: 1.25rem;
            font-weight: 800;
            background: var(--primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1.1;
        }

        .brand-text small {
            font-size: 0.75rem;
            color: #64748b;
        }

        .nav-links a {
            text-decoration: none;
            color: #0284c7;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 8px;
            border: 1px solid #e0f2fe;
            transition: all 0.2s;
            font-size: 0.9rem;
        }

        .nav-links a.primary {
            background: var(--primary);
            color: #fff;
            border: none;
            box-shadow: 0 4px 12px rgba(2,132,199,0.3);
        }

        .nav-links a:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(2,132,199,0.4);
        }

        .hero {
            text-align: center;
            padding: 80px 24px;
            max-width: 800px;
            margin: auto;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .hero h2 {
            font-size: 3.5rem;
            font-weight: 900;
            line-height: 1.2;
            margin-bottom: 24px;
            color: #0f172a;
        }

        .hero h2 span {
            background: var(--primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            font-size: 1.15rem;
            color: #64748b;
            margin-bottom: 40px;
            max-width: 600px;
            line-height: 1.6;
        }

        .cta-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 16px 32px;
            background: var(--primary);
            color: #fff;
            font-size: 1.1rem;
            font-weight: 700;
            text-decoration: none;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(2,132,199,0.35);
            transition: all 0.3s;
        }

        .cta-btn:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(2,132,199,0.45);
        }

        .hero-image {
            margin-top: 48px;
            width: 100%;
            max-width: 600px;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(14,165,233,0.15);
            border: 4px solid #fff;
        }

        @media (max-width: 768px) {
            .hero h2 { font-size: 2.5rem; }
            .hero p { font-size: 1rem; }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="/" class="brand">
            <img src="{{ asset('images/cireng_icon.png') }}" alt="Cireng Pasrah">
            <div class="brand-text">
                <h1>Cireng Pasrah</h1>
                <small>Enak, Renyah, Halal</small>
            </div>
        </a>
        <div class="nav-links">
            @auth
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('dashboard.index') }}" class="primary">Masuk ke Dashboard</a>
                @else
                    <a href="{{ route('katalog.index') }}" class="primary">Masuk ke Katalog</a>
                @endif
            @else
                <a href="{{ route('login') }}" class="primary">Login / Daftar</a>
            @endauth
        </div>
    </nav>

    <main class="hero">
        <h2>Sensasi Renyah<br><span>Cireng Pasrah</span></h2>
        <p>Nikmati kerenyahan cireng goreng yang pas untuk menemani hari-harimu. Dibuat dengan bahan berkualitas dan penuh cinta. Pesan sekarang, pasrah dengan kenikmatannya!</p>
        <a href="{{ route('katalog.index') }}" class="cta-btn">
            Lihat Katalog Kami 🛒
        </a>

        <!-- Display the generated hero image -->
        <img src="{{ asset('images/cireng_hero.png') }}" alt="Cireng Goreng Lezat" class="hero-image">
    </main>
</body>
</html>
