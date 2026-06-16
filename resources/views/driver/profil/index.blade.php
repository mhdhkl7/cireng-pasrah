@extends('layouts.driver')

@section('title', 'Edit Profil Driver')
@section('styles')
<style>
    .profil-layout {
        display: grid;
        grid-template-columns: 260px 1fr;
        gap: 24px;
        align-items: start;
    }
    @media(max-width: 768px) { .profil-layout { grid-template-columns: 1fr; } }

    .profil-sidebar {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e7e5e4;
        overflow: hidden;
        text-align: center;
        padding: 32px 24px;
    }
    .avatar-big {
        width: 80px; height: 80px;
        background: var(--primary);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 2rem; font-weight: 800; color: #fff;
        margin: 0 auto 14px;
        box-shadow: 0 6px 20px rgba(245,158,11,0.3);
    }
    .profil-name { font-size: 1.1rem; font-weight: 800; color: #1c1917; }
    .profil-email { font-size: 0.8rem; color: #78716c; margin-top: 3px; }
    .profil-role {
        display: inline-block;
        margin-top: 10px;
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
        font-size: 0.72rem;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 20px;
    }

    .section-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e7e5e4;
        overflow: hidden;
        margin-bottom: 20px;
    }
    .section-header {
        padding: 16px 20px;
        border-bottom: 1px solid #f5f5f4;
        font-size: 0.95rem;
        font-weight: 700;
        color: #1c1917;
        display: flex; align-items: center; gap: 8px;
    }
    .section-body { padding: 24px; }

    .form-group { margin-bottom: 18px; }
    .form-label { display: block; font-size: 0.83rem; font-weight: 600; color: #292524; margin-bottom: 6px; }
    .form-label .required { color: #dc2626; }
    .form-control {
        width: 100%;
        padding: 11px 14px;
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
        border-color: #f59e0b;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(245,158,11,0.12);
    }
    .form-error { font-size: 0.75rem; color: #dc2626; margin-top: 4px; }

    .password-wrapper { position: relative; }
    .password-wrapper .form-control { padding-right: 44px; }
    .toggle-pw {
        position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
        background: none; border: none; cursor: pointer; color: #a8a29e;
        font-size: 1rem; padding: 4px; transition: color 0.2s;
    }
    .toggle-pw:hover { color: #f59e0b; }

    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    @media(max-width: 600px) { .form-row { grid-template-columns: 1fr; } }

    .btn-save {
        padding: 12px 28px;
        background: var(--primary);
        color: #fff;
        border: none;
        border-radius: 12px;
        font-size: 0.9rem;
        font-weight: 700;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        box-shadow: 0 4px 15px rgba(245,158,11,0.3);
        transition: all 0.2s;
    }
    .btn-save:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(245,158,11,0.4); }

    .page-title { font-size: 1.5rem; font-weight: 900; color: #1c1917; margin-bottom: 6px; }
    .page-subtitle { color: #78716c; font-size: 0.875rem; margin-bottom: 28px; }
</style>
@endsection

@section('content')
<div class="page-title">👤 Edit Profil Driver</div>
<div class="page-subtitle">Kelola informasi akun dan kata sandi Anda</div>

<div class="profil-layout">
    {{-- Sidebar --}}
    <div class="profil-sidebar">
        <div class="avatar-big">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
        <div class="profil-name">{{ $user->name }}</div>
        <div class="profil-email">{{ $user->email }}</div>
        <div class="profil-role">🚗 Driver</div>
        <div style="margin-top:20px;font-size:0.75rem;color:#a8a29e;">Bergabung {{ $user->created_at->format('d M Y') }}</div>
    </div>

    {{-- Main --}}
    <div>
        {{-- Edit Data Profil --}}
        <div class="section-card">
            <div class="section-header">📋 Informasi Pribadi</div>
            <div class="section-body">
                <form method="POST" action="{{ route('driver.profil.update') }}">
                    @csrf @method('PUT')

                    <div class="form-row">
                        <div class="form-group">
                            <label for="name" class="form-label">Nama Lengkap <span class="required">*</span></label>
                            <input type="text" name="name" id="name" class="form-control"
                                   value="{{ old('name', $user->name) }}" placeholder="Nama lengkap" required>
                            @error('name') <div class="form-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label for="phone" class="form-label">No. Telepon / WhatsApp <span class="required">*</span></label>
                            <input type="text" name="phone" id="phone" class="form-control"
                                   value="{{ old('phone', $user->phone) }}" placeholder="08xxxxxxxxxx" required>
                            @error('phone') <div class="form-error">{{ $message }}</div> @enderror
                            <div style="font-size:0.7rem;color:#78716c;margin-top:4px;">Penting untuk menghubungi customer</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Alamat Email <span class="required">*</span></label>
                        <input type="email" name="email" id="email" class="form-control"
                               value="{{ old('email', $user->email) }}" placeholder="email@contoh.com" required>
                        @error('email') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label for="address" class="form-label">Alamat Rumah</label>
                        <textarea name="address" id="address" class="form-control" rows="3"
                                  placeholder="Alamat tinggal Anda (opsional)">{{ old('address', $user->address) }}</textarea>
                        @error('address') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="btn-save">💾 Simpan Perubahan</button>
                </form>
            </div>
        </div>

        {{-- Ganti Password --}}
        <div class="section-card">
            <div class="section-header">🔑 Ganti Password</div>
            <div class="section-body">
                <form method="POST" action="{{ route('driver.profil.password') }}">
                    @csrf @method('PUT')

                    <div class="form-group">
                        <label for="current_password" class="form-label">Password Saat Ini <span class="required">*</span></label>
                        <div class="password-wrapper">
                            <input type="password" name="current_password" id="current_password" class="form-control"
                                   placeholder="Masukkan password lama" required>
                            <button type="button" class="toggle-pw" onclick="togglePw('current_password',this)">👁️</button>
                        </div>
                        @error('current_password') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="password" class="form-label">Password Baru <span class="required">*</span></label>
                            <div class="password-wrapper">
                                <input type="password" name="password" id="password" class="form-control"
                                       placeholder="Min. 8 karakter" required>
                                <button type="button" class="toggle-pw" onclick="togglePw('password',this)">👁️</button>
                            </div>
                            @error('password') <div class="form-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password Baru <span class="required">*</span></label>
                            <div class="password-wrapper">
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                                       placeholder="Ulangi password baru" required>
                                <button type="button" class="toggle-pw" onclick="togglePw('password_confirmation',this)">👁️</button>
                            </div>
                        </div>
                    </div>

                    <div style="font-size:0.75rem;color:#a8a29e;margin-bottom:16px;">
                        🔒 Setelah ganti password, Anda akan diarahkan ke halaman login.
                    </div>

                    <button type="submit" class="btn-save" style="background:var(--danger);box-shadow:0 4px 15px rgba(239,68,68,0.3);">
                        🔑 Ganti Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function togglePw(id, btn) {
    const input = document.getElementById(id);
    if (input.type === 'password') {
        input.type = 'text';
        btn.textContent = '🔒';
    } else {
        input.type = 'password';
        btn.textContent = '👁️';
    }
}
</script>
@endsection
