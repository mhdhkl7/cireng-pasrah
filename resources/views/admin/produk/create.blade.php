@extends('layouts.admin')

@section('title', 'Tambah Produk')
@section('page-title', 'Tambah Produk Baru')
@section('page-subtitle', 'Isi detail produk cireng baru')

@section('styles')
<style>
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    @media(max-width:768px) { .form-grid { grid-template-columns: 1fr; } }

    .upload-preview-wrap {
        border: 2px dashed var(--border);
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        transition: all 0.2s;
        cursor: pointer;
        position: relative;
    }
    .upload-preview-wrap:hover { border-color: var(--primary); background: #fff7ed; }
    .upload-preview-wrap input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; }
    .preview-img { max-width: 100%; max-height: 160px; border-radius: 8px; margin-top: 10px; object-fit: cover; display: none; }
    .preview-placeholder { font-size: 2rem; margin-bottom: 6px; }
    .preview-hint { font-size: 0.78rem; color: var(--text-muted); }

    .toggle-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    input[type="checkbox"].toggle {
        appearance: none;
        width: 44px; height: 24px;
        background: #e7e5e4;
        border-radius: 12px;
        cursor: pointer;
        position: relative;
        transition: background 0.3s;
    }
    input[type="checkbox"].toggle:checked { background: var(--primary); }
    input[type="checkbox"].toggle::after {
        content: '';
        position: absolute;
        top: 2px; left: 2px;
        width: 20px; height: 20px;
        background: #fff;
        border-radius: 50%;
        transition: left 0.3s;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }
    input[type="checkbox"].toggle:checked::after { left: 22px; }
</style>
@endsection

@section('content')
<div class="card" style="max-width:800px;">
    <div class="card-header">
        <div class="card-title">➕ Tambah Produk Cireng Baru</div>
        <a href="{{ route('admin.produk.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.produk.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Nama Produk <span class="required">*</span></label>
                    <input type="text" name="nama" class="form-control"
                           value="{{ old('nama') }}" placeholder="Cth: Cireng Isi Ayam Pedas" required>
                    @error('nama')
                        <div class="form-error">⚠️ {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Harga (Rp) <span class="required">*</span></label>
                    <input type="number" name="harga" class="form-control"
                           value="{{ old('harga') }}" placeholder="5000" min="0" step="500" required>
                    @error('harga')
                        <div class="form-error">⚠️ {{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi Produk</label>
                <textarea name="deskripsi" class="form-control" rows="4"
                          placeholder="Deskripsikan produk cireng ini...">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <div class="form-error">⚠️ {{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Foto Produk</label>
                <div class="upload-preview-wrap" id="upload-wrap">
                    <input type="file" name="gambar" id="gambar" accept="image/*"
                           onchange="previewImage(this)">
                    <div id="placeholder">
                        <div class="preview-placeholder">📸</div>
                        <div class="preview-hint">Klik atau drag foto produk (JPG, PNG, WEBP - Maks. 2MB)</div>
                    </div>
                    <img id="preview-img" class="preview-img" src="" alt="Preview">
                </div>
                @error('gambar')
                    <div class="form-error">⚠️ {{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Status Produk</label>
                <div class="toggle-wrap">
                    <input type="checkbox" name="is_active" id="is_active" class="toggle"
                           {{ old('is_active', true) ? 'checked' : '' }} value="1">
                    <label for="is_active" style="font-size:0.875rem;font-weight:600;cursor:pointer;">
                        Produk Aktif (Tampil di katalog)
                    </label>
                </div>
            </div>

            <div style="display:flex;gap:12px;padding-top:8px;">
                <button type="submit" class="btn btn-primary">💾 Simpan Produk</button>
                <a href="{{ route('admin.produk.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('preview-img').style.display = 'block';
                document.getElementById('placeholder').style.display = 'none';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
