@extends('layouts.admin')

@section('title', 'Edit Produk')
@section('page-title', 'Edit Produk')
@section('page-subtitle', 'Perbarui informasi produk')

@section('styles')
<style>
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    @media(max-width:768px) { .form-grid { grid-template-columns: 1fr; } }
    .upload-preview-wrap {
        border: 2px dashed var(--border); border-radius: 10px; padding: 16px;
        text-align: center; transition: all 0.2s; cursor: pointer; position: relative;
    }
    .upload-preview-wrap:hover { border-color: var(--primary); background: #fff7ed; }
    .upload-preview-wrap input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; }
    .preview-img { max-width: 100%; max-height: 160px; border-radius: 8px; object-fit: cover; }
    input[type="checkbox"].toggle { appearance: none; width: 44px; height: 24px; background: #e7e5e4; border-radius: 12px; cursor: pointer; position: relative; transition: background 0.3s; }
    input[type="checkbox"].toggle:checked { background: var(--primary); }
    input[type="checkbox"].toggle::after { content: ''; position: absolute; top: 2px; left: 2px; width: 20px; height: 20px; background: #fff; border-radius: 50%; transition: left 0.3s; box-shadow: 0 1px 3px rgba(0,0,0,0.2); }
    input[type="checkbox"].toggle:checked::after { left: 22px; }
    .toggle-wrap { display: flex; align-items: center; gap: 10px; }
</style>
@endsection

@section('content')
<div class="card" style="max-width:800px;">
    <div class="card-header">
        <div class="card-title">✏️ Edit: {{ $produk->nama }}</div>
        <a href="{{ route('admin.produk.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.produk.update', $produk) }}" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Nama Produk <span class="required">*</span></label>
                    <input type="text" name="nama" class="form-control"
                           value="{{ old('nama', $produk->nama) }}" required>
                    @error('nama') <div class="form-error">⚠️ {{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Harga (Rp) <span class="required">*</span></label>
                    <input type="number" name="harga" class="form-control"
                           value="{{ old('harga', $produk->harga) }}" min="0" step="500" required>
                    @error('harga') <div class="form-error">⚠️ {{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi Produk</label>
                <textarea name="deskripsi" class="form-control" rows="4">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Foto Produk</label>
                <div class="upload-preview-wrap">
                    <input type="file" name="gambar" accept="image/*" onchange="previewImage(this)">
                    <img id="preview-img"
                         src="{{ $produk->gambar ? $produk->gambar_url : '' }}"
                         alt="{{ $produk->nama }}"
                         style="{{ $produk->gambar ? '' : 'display:none;' }}"
                         class="preview-img">
                    @if(!$produk->gambar)
                        <div id="placeholder" style="padding:10px;">
                            <div style="font-size:2rem;">📸</div>
                            <div style="font-size:0.78rem;color:var(--text-muted);">Klik untuk ganti foto (opsional)</div>
                        </div>
                    @else
                        <div id="placeholder" style="display:none;"></div>
                        <div style="font-size:0.72rem;color:var(--text-muted);margin-top:6px;">Klik untuk ganti foto</div>
                    @endif
                </div>
                @error('gambar') <div class="form-error">⚠️ {{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Status Produk</label>
                <div class="toggle-wrap">
                    <input type="checkbox" name="is_active" id="is_active" class="toggle"
                           {{ old('is_active', $produk->is_active) ? 'checked' : '' }} value="1">
                    <label for="is_active" style="font-size:0.875rem;font-weight:600;cursor:pointer;">
                        Produk Aktif (Tampil di katalog)
                    </label>
                </div>
            </div>

            <div style="display:flex;gap:12px;padding-top:8px;">
                <button type="submit" class="btn btn-primary">💾 Perbarui Produk</button>
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
