@extends('layouts.admin')

@section('title', 'Manajemen Inventori')
@section('page-title', 'Manajemen Inventori')
@section('page-subtitle', 'Kelola stok bahan baku cireng')

@section('styles')
<style>
    .inventori-layout {
        display: grid;
        grid-template-columns: 1fr 360px;
        gap: 20px;
        align-items: start;
    }
    @media(max-width:900px) { .inventori-layout { grid-template-columns: 1fr; } }

    .stok-indicator {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .stok-bar-wrap {
        flex: 1;
        height: 8px;
        background: #e7e5e4;
        border-radius: 4px;
        overflow: hidden;
    }
    .stok-bar {
        height: 100%;
        border-radius: 4px;
        transition: width 1s ease;
    }
    .stok-bar.ok       { background: linear-gradient(to right, #10b981, #059669); }
    .stok-bar.low      { background: linear-gradient(to right, #f59e0b, #d97706); }
    .stok-bar.critical { background: linear-gradient(to right, #ef4444, #dc2626); }

    .stok-number { font-size: 1.1rem; font-weight: 800; }
    .stok-number.ok       { color: #059669; }
    .stok-number.low      { color: #d97706; }
    .stok-number.critical { color: #dc2626; }

    .tambah-form {
        background: var(--card);
        border-radius: var(--radius);
        border: 1px solid var(--border);
        overflow: hidden;
        position: sticky;
        top: 80px;
    }

    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        z-index: 200;
        align-items: center;
        justify-content: center;
    }
    .modal-overlay.show { display: flex; }
    .modal {
        background: #fff;
        border-radius: 16px;
        padding: 28px;
        width: 100%;
        max-width: 400px;
        margin: 24px;
    }
    .modal-title { font-size: 1rem; font-weight: 700; margin-bottom: 16px; }
    .modal-footer { display: flex; gap: 10px; margin-top: 20px; }
</style>
@endsection

@section('content')
<div class="inventori-layout">
    <!-- List Inventori -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">📦 Daftar Stok Bahan Baku</div>
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Item</th>
                        <th>Stok Tersedia</th>
                        <th>Satuan</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inventoris as $item)
                        @php
                            $level = $item->stok > 50 ? 'ok' : ($item->stok > 10 ? 'low' : 'critical');
                            $maxStok = 200;
                            $pct = min(($item->stok / $maxStok) * 100, 100);
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td style="font-weight:600;">{{ $item->nama_item }}</td>
                            <td>
                                <div class="stok-indicator">
                                    <div class="stok-number {{ $level }}">{{ $item->stok }}</div>
                                    <div class="stok-bar-wrap">
                                        <div class="stok-bar {{ $level }}" style="width:{{ $pct }}%;"></div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $item->satuan }}</td>
                            <td style="color:var(--text-muted);font-size:0.83rem;">{{ $item->keterangan ?? '-' }}</td>
                            <td>
                                <div style="display:flex;gap:6px;">
                                    <button onclick="openTambah({{ $item->id }}, '{{ $item->nama_item }}', '{{ $item->satuan }}')"
                                            class="btn btn-sm btn-success">+ Tambah Stok</button>
                                    <button onclick="openEdit({{ $item->id }}, '{{ $item->nama_item }}', {{ $item->stok }}, '{{ $item->satuan }}', '{{ $item->keterangan }}')"
                                            class="btn btn-sm btn-secondary">✏️</button>
                                    <form method="POST" action="{{ route('admin.inventori.destroy', $item) }}"
                                          onsubmit="return confirm('Hapus item ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center;color:var(--text-muted);padding:40px;">
                                Belum ada item inventori. Tambahkan di kanan!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Form Tambah Baru -->
    <div class="tambah-form">
        <div class="card-header">
            <div class="card-title">➕ Tambah Item Baru</div>
        </div>
        <div style="padding:20px;">
            <form method="POST" action="{{ route('admin.inventori.store') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nama Item <span class="required">*</span></label>
                    <input type="text" name="nama_item" class="form-control"
                           value="{{ old('nama_item') }}" placeholder="Cth: Cireng Mentah" required>
                    @error('nama_item') <div class="form-error">⚠️ {{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Jumlah Stok Awal <span class="required">*</span></label>
                    <input type="number" name="stok" class="form-control"
                           value="{{ old('stok', 0) }}" min="0" required>
                    @error('stok') <div class="form-error">⚠️ {{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Satuan <span class="required">*</span></label>
                    <input type="text" name="satuan" class="form-control"
                           value="{{ old('satuan', 'pcs') }}" placeholder="pcs / kg / pack" required>
                    @error('satuan') <div class="form-error">⚠️ {{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="2"
                              placeholder="Keterangan opsional...">{{ old('keterangan') }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;">💾 Simpan Item</button>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Stok -->
<div class="modal-overlay" id="modal-tambah">
    <div class="modal">
        <div class="modal-title">📦 Tambah Stok: <span id="modal-item-name"></span></div>
        <form method="POST" id="form-tambah-stok">
            @csrf
            <div class="form-group">
                <label class="form-label">Jumlah yang Ditambahkan <span class="required">*</span></label>
                <input type="number" name="jumlah" id="jumlah-tambah" class="form-control"
                       min="1" placeholder="Masukkan jumlah..." required>
                <div style="font-size:0.75rem;color:var(--text-muted);margin-top:4px;">
                    Satuan: <span id="modal-satuan"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">✅ Tambah Stok</button>
                <button type="button" onclick="closeModal('modal-tambah')" class="btn btn-secondary">Batal</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Stok -->
<div class="modal-overlay" id="modal-edit">
    <div class="modal">
        <div class="modal-title">✏️ Edit Item Inventori</div>
        <form method="POST" id="form-edit-stok">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Nama Item</label>
                <input type="text" name="nama_item" id="edit-nama" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Jumlah Stok</label>
                <input type="number" name="stok" id="edit-stok" class="form-control" min="0" required>
            </div>
            <div class="form-group">
                <label class="form-label">Satuan</label>
                <input type="text" name="satuan" id="edit-satuan" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" id="edit-keterangan" class="form-control" rows="2"></textarea>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">💾 Simpan</button>
                <button type="button" onclick="closeModal('modal-edit')" class="btn btn-secondary">Batal</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openTambah(id, nama, satuan) {
        document.getElementById('modal-item-name').textContent = nama;
        document.getElementById('modal-satuan').textContent = satuan;
        document.getElementById('jumlah-tambah').value = '';
        document.getElementById('form-tambah-stok').action = '/admin/inventori/' + id + '/tambah-stok';
        document.getElementById('modal-tambah').classList.add('show');
    }

    function openEdit(id, nama, stok, satuan, keterangan) {
        document.getElementById('edit-nama').value = nama;
        document.getElementById('edit-stok').value = stok;
        document.getElementById('edit-satuan').value = satuan;
        document.getElementById('edit-keterangan').value = keterangan || '';
        document.getElementById('form-edit-stok').action = '/admin/inventori/' + id;
        document.getElementById('modal-edit').classList.add('show');
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('show');
    }

    // Close on backdrop click
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) this.classList.remove('show');
        });
    });
</script>
@endsection
