<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Cireng Pasrah 🍟</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root { --primary: #2563eb; --primary-dark: #1d4ed8; --danger: #dc2626; }
        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            position: relative;
            overflow: hidden;
        }

        /* Background decorative circles */
        body::before {
            content: '';
            position: fixed;
            top: -100px; right: -100px;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(2,132,199,0.15) 0%, transparent 70%);
            border-radius: 50%;
        }
        body::after {
            content: '';
            position: fixed;
            bottom: -100px; left: -100px;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(13,148,136,0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .auth-wrapper {
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 1;
        }

        .auth-logo {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo-icon {
            width: 72px; height: 72px;
            background: var(--primary);
            border-radius: 20px;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 2rem;
            box-shadow: 0 12px 30px rgba(2,132,199,0.35);
            margin-bottom: 16px;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        .auth-logo h1 {
            font-size: 1.5rem;
            font-weight: 900;
            color: #1c1917;
        }

        .auth-logo h1 span { color: var(--primary); }

        .auth-logo p {
            color: #78716c;
            font-size: 0.875rem;
            margin-top: 4px;
        }

        .auth-card {
            background: #fff;
            border-radius: 24px;
            padding: 36px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1), 0 4px 20px rgba(2,132,199,0.08);
            border: 1px solid rgba(2,132,199,0.1);
        }

        .auth-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: #1c1917;
            margin-bottom: 6px;
        }

        .auth-subtitle {
            font-size: 0.85rem;
            color: #78716c;
            margin-bottom: 28px;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 11px 14px;
            border-radius: 10px;
            font-size: 0.83rem;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-size: 0.83rem;
            font-weight: 600;
            color: #292524;
            margin-bottom: 7px;
        }

        .form-control {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid #e7e5e4;
            border-radius: 10px;
            font-size: 0.875rem;
            font-family: 'Inter', sans-serif;
            color: #1c1917;
            background: #fafaf9;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(2,132,199,0.12);
        }

        .password-wrapper {
            position: relative;
        }
        .password-wrapper .form-control {
            padding-right: 44px;
        }
        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #a8a29e;
            font-size: 1rem;
            padding: 4px;
            transition: color 0.2s;
        }
        .toggle-password:hover { color: var(--primary); }

        .alert-warning {
            background: #fffbeb;
            border: 1px solid #fde68a;
            color: #92400e;
            padding: 11px 14px;
            border-radius: 10px;
            font-size: 0.83rem;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .form-error {
            font-size: 0.75rem;
            color: var(--danger);
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
        }

        .remember-row input[type="checkbox"] {
            accent-color: var(--primary);
            width: 16px; height: 16px;
            cursor: pointer;
        }

        .remember-row label {
            font-size: 0.83rem;
            color: #78716c;
            cursor: pointer;
        }

        .btn-auth {
            width: 100%;
            padding: 13px;
            background: var(--primary);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(2,132,199,0.35);
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-auth:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(2,132,199,0.45);
        }

        .auth-footer {
            text-align: center;
            margin-top: 24px;
            font-size: 0.85rem;
            color: #78716c;
        }

        .auth-footer a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
        }

        .auth-footer a:hover { text-decoration: underline; }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 20px 0;
            color: #a8a29e;
            font-size: 0.78rem;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e7e5e4;
        }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-logo">
            <div class="logo-icon">🍟</div>
            <h1><span>Cireng</span> Pasrah</h1>
            <p>Enak, Renyah, & Halal</p>
        </div>

        <div class="auth-card">
            <div class="auth-title">Selamat Datang Kembali!</div>
            <div class="auth-subtitle">Masuk ke akun Anda untuk mulai memesan</div>

            @if($errors->any())
                <div class="alert-error">
                    ❌ {{ $errors->first() }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert-warning">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert-error" style="background:#d1fae5;border-color:#a7f3d0;color:#065f46;">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" id="form-login">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        class="form-control"
                        value="{{ old('email') }}"
                        placeholder="contoh@email.com"
                        autocomplete="email"
                        required
                    >
                    @error('email')
                        <div class="form-error">⚠️ {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="password-wrapper">
                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="form-control"
                            placeholder="Masukkan password"
                            autocomplete="current-password"
                            required
                        >
                        <button type="button" class="toggle-password" onclick="togglePwd('password', this)" title="Lihat/Sembunyikan Password">👁️</button>
                    </div>
                    @error('password')
                        <div class="form-error">⚠️ {{ $message }}</div>
                    @enderror
                </div>

                <div class="remember-row">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Ingat saya</label>
                </div>

                <button type="submit" class="btn-auth">
                    🔑 Masuk Sekarang
                </button>
            </form>

            <div class="auth-footer">
                Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
            </div>
        </div>
    </div>
<script>
function togglePwd(inputId, btn) {
    const input = document.getElementById(inputId);
    if (input.type === 'password') {
        input.type = 'text';
        btn.textContent = '🔒';
        btn.title = 'Sembunyikan Password';
    } else {
        input.type = 'password';
        btn.textContent = '👁️';
        btn.title = 'Lihat Password';
    }
}
</script>
</body>
</html>
