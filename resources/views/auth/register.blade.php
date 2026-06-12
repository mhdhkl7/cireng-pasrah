<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun | Cireng Pasrah 🍟</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root { --primary: #0284c7; --primary-dark: #0d9488; --danger: #dc2626; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #f0f9ff 0%, #ccfbf1 50%, #e0f2fe 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        body::before {
            content: '';
            position: fixed;
            top: -120px; right: -120px;
            width: 450px; height: 450px;
            background: radial-gradient(circle, rgba(2,132,199,0.15) 0%, transparent 70%);
            border-radius: 50%;
        }

        .auth-wrapper { width: 100%; max-width: 480px; position: relative; z-index: 1; }

        .auth-logo {
            text-align: center;
            margin-bottom: 24px;
        }

        .logo-icon {
            width: 64px; height: 64px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 18px;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 1.75rem;
            box-shadow: 0 10px 25px rgba(2,132,199,0.3);
            margin-bottom: 12px;
        }

        .auth-logo h1 { font-size: 1.4rem; font-weight: 900; color: #1c1917; }
        .auth-logo h1 span { color: var(--primary); }
        .auth-logo p { color: #78716c; font-size: 0.83rem; margin-top: 3px; }

        .auth-card {
            background: #fff;
            border-radius: 24px;
            padding: 36px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.08), 0 4px 20px rgba(2,132,199,0.07);
            border: 1px solid rgba(2,132,199,0.1);
        }

        .auth-title { font-size: 1.2rem; font-weight: 800; color: #1c1917; margin-bottom: 4px; }
        .auth-subtitle { font-size: 0.83rem; color: #78716c; margin-bottom: 24px; }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

        .form-group { margin-bottom: 15px; }

        .form-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: #292524;
            margin-bottom: 6px;
        }

        .form-label .required { color: var(--danger); }

        .form-control {
            width: 100%;
            padding: 11px 13px;
            border: 1.5px solid #e7e5e4;
            border-radius: 10px;
            font-size: 0.875rem;
            font-family: 'Inter', sans-serif;
            color: #1c1917;
            background: #fafaf9;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(2,132,199,0.12);
        }
        textarea.form-control { resize: vertical; min-height: 80px; }

        .form-error { font-size: 0.75rem; color: var(--danger); margin-top: 4px; }

        .btn-auth {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(2,132,199,0.3);
            transition: all 0.2s;
            margin-top: 8px;
        }
        .btn-auth:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(2,132,199,0.4); }

        .auth-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.84rem;
            color: #78716c;
        }
        .auth-footer a { color: var(--primary); font-weight: 600; text-decoration: none; }
        .auth-footer a:hover { text-decoration: underline; }

        .hint {
            font-size: 0.72rem;
            color: #a8a29e;
            margin-top: 3px;
        }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-logo">
            <div class="logo-icon">🍟</div>
            <h1><span>Cireng</span> Pasrah</h1>
            <p>Daftar & nikmati cireng renyah kesukaan!</p>
        </div>

        <div class="auth-card">
            <div class="auth-title">Buat Akun Baru</div>
            <div class="auth-subtitle">Isi data diri Anda untuk mulai memesan</div>

            <form method="POST" action="{{ route('register.post') }}" id="form-register">
                @csrf

                <div class="form-row">
                    <div class="form-group">
                        <label for="name" class="form-label">Nama Lengkap <span class="required">*</span></label>
                        <input type="text" name="name" id="name" class="form-control"
                               value="{{ old('name') }}" placeholder="Nama Anda" required>
                        @error('name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone" class="form-label">No. Telepon</label>
                        <input type="text" name="phone" id="phone" class="form-control"
                               value="{{ old('phone') }}" placeholder="08xxxxxxxxxx">
                        @error('phone')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Alamat Email <span class="required">*</span></label>
                    <input type="email" name="email" id="email" class="form-control"
                           value="{{ old('email') }}" placeholder="contoh@email.com" required>
                    @error('email')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="address" class="form-label">Alamat Lengkap</label>
                    <textarea name="address" id="address" class="form-control"
                              placeholder="Alamat lengkap Anda (digunakan untuk pengiriman)">{{ old('address') }}</textarea>
                    @error('address')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password" class="form-label">Password <span class="required">*</span></label>
                        <input type="password" name="password" id="password" class="form-control"
                               placeholder="Min. 8 karakter" required>
                        @error('password')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="required">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="form-control" placeholder="Ulangi password" required>
                    </div>
                </div>

                <div class="hint" style="margin-bottom:16px;">
                    🔒 Password minimal 8 karakter. Pastikan konfirmasi password cocok.
                </div>

                <button type="submit" class="btn-auth">
                    🎉 Daftar Sekarang
                </button>
            </form>

            <div class="auth-footer">
                Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
            </div>
        </div>
    </div>
</body>
</html>
