@extends('layouts.admin')

@section('title', 'Detail Pesanan ' . $pesanan->kode_pesanan)
@section('page-title', 'Detail Pesanan')
@section('page-subtitle', 'Kelola dan perbarui status pesanan')

@section('styles')
<style>
    .detail-layout {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 20px;
        align-items: start;
    }
    @media(max-width:900px) { .detail-layout { grid-template-columns: 1fr; } }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid var(--border);
        font-size: 0.875rem;
    }
    .info-row:last-child { border-bottom: none; }
    .info-key { color: var(--text-muted); font-weight: 500; }
    .info-val { font-weight: 600; text-align: right; }

    .item-row {
        display: flex; align-items: center; gap: 12px;
        padding: 10px 0; border-bottom: 1px solid var(--border);
    }
    .item-row:last-child { border-bottom: none; }
    .item-thumb { width: 48px; height: 48px; border-radius: 8px; overflow: hidden; background: #f0f9ff; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; }
    .item-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .item-name { font-weight: 700; font-size: 0.875rem; }
    .item-price { font-size: 0.75rem; color: var(--text-muted); }
    .item-subtotal { font-weight: 700; color: var(--primary); margin-left: auto; }

    .total-row { display: flex; justify-content: space-between; align-items: center; padding: 14px 0 0; border-top: 2px solid var(--border); margin-top: 4px; }
    .total-label { font-size: 1rem; font-weight: 700; }
    .total-value { font-size: 1.1rem; font-weight: 800; color: var(--primary); }

    /* Status Form */
    .status-card {
        background: var(--card);
        border-radius: var(--radius);
        border: 1px solid var(--border);
        overflow: hidden;
        position: sticky;
        top: 80px;
    }

    .status-header {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        padding: 16px 20px;
        color: #fff;
        font-weight: 700;
    }

    .status-body { padding: 20px; }

    .form-group { margin-bottom: 16px; }
    .form-label { display: block; font-size: 0.83rem; font-weight: 600; margin-bottom: 6px; }

    select.form-control {
        width: 100%;
        padding: 10px 14px;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        font-size: 0.875rem;
        font-family: 'Inter', sans-serif;
        color: var(--text);
        background: var(--bg);
        cursor: pointer;
    }
    select.form-control:focus { outline: none; border-color: var(--primary); }

    /* Bukti Transfer */
    .bukti-box {
        border-radius: 10px;
        overflow: hidden;
        border: 2px solid var(--border);
    }
    .bukti-box img { width: 100%; display: block; }
    .bukti-actions { padding: 10px; display: flex; gap: 8px; background: var(--bg); }

    .stok-warning-box {
        background: #ccfbf1;
        border: 1px solid #fde68a;
        border-radius: 8px;
        padding: 12px;
        font-size: 0.8rem;
        color: #92400e;
        font-weight: 500;
        margin-bottom: 14px;
    }

    .verified-box {
        background: #d1fae5;
        border: 1px solid #a7f3d0;
        border-radius: 8px;
        padding: 12px;
        font-size: 0.83rem;
        color: #065f46;
        font-weight: 600;
        text-align: center;
    }
</style>
@endsection

@section('content')
<div style="margin-bottom:16px;">
    <a href="{{ route('admin.pesanan.index') }}" style="font-size:0.83rem;color:var(--text-muted);text-decoration:none;">
        ← Kembali ke Daftar Pesanan
    </a>
</div>

<div style="background:linear-gradient(135deg,#f0f9ff,#ccfbf1);border:1px solid rgba(14,165,233,0.2);border-radius:16px;padding:18px 20px;margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
    <div>
        <div style="font-size:1.1rem;font-weight:900;">🏷️ {{ $pesanan->kode_pesanan }}</div>
        <div style="font-size:0.78rem;color:var(--text-muted);margin-top:3px;">
            {{ $pesanan->created_at->format('l, d F Y - H:i') }} WIB
        </div>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <span class="badge badge-{{ $pesanan->status }}">{{ $pesanan->status_label }}</span>
        <span class="badge {{ $pesanan->status_pembayaran === 'lunas' ? 'badge-lunas' : 'badge-belum' }}">
            {{ $pesanan->status_pembayaran === 'lunas' ? '✅ Lunas' : '⏳ Belum Bayar' }}
        </span>
    </div>
</div>

<div class="detail-layout">
    <!-- Left Column -->
    <div>
        <!-- Info Customer -->
        <div class="card" style="margin-bottom:16px;">
            <div class="card-header">
                <div class="card-title">👤 Info Customer</div>
                <a href="{{ route('admin.customer.show', $pesanan->user) }}" class="btn btn-sm btn-secondary">Lihat Profil</a>
            </div>
            <div class="card-body">
                <div class="info-row">
                    <span class="info-key">Nama</span>
                    <span class="info-val">{{ $pesanan->user->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">Email</span>
                    <span class="info-val">{{ $pesanan->user->email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">Telepon</span>
                    <span class="info-val">{{ $pesanan->user->phone ?? '-' }}</span>
                </div>
            </div>
        </div>

        <!-- Item Pesanan -->
        <div class="card" style="margin-bottom:16px;">
            <div class="card-header">
                <div class="card-title">🍟 Detail Item Pesanan</div>
            </div>
            <div class="card-body">
                @foreach($pesanan->detailPesanans as $detail)
                    <div class="item-row">
                        <div class="item-thumb">
                            @if($detail->produk && $detail->produk->gambar)
                                <img src="{{ asset('storage/' . $detail->produk->gambar) }}" alt="{{ $detail->nama_produk }}">
                            @else
                                🍟
                            @endif
                        </div>
                        <div style="flex:1;">
                            <div class="item-name">{{ $detail->nama_produk }}</div>
                            <div class="item-price">{{ $detail->harga_formatted }} × {{ $detail->qty }} pcs</div>
                        </div>
                        <div class="item-subtotal">{{ $detail->subtotal_formatted }}</div>
                    </div>
                @endforeach
                <div class="total-row">
                    <span class="total-label">💰 Total Pembayaran</span>
                    <span class="total-value">{{ $pesanan->total_harga_formatted }}</span>
                </div>
            </div>
        </div>

        <!-- Info Pengiriman & Catatan -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">📋 Info Pengiriman</div>
            </div>
            <div class="card-body">
                <div class="info-row">
                    <span class="info-key">Opsi Pengiriman</span>
                    <span class="info-val">{{ $pesanan->opsi_pengiriman === 'take_away' ? '🏃 Take Away' : '🛵 Delivery' }}</span>
                </div>
                @if($pesanan->alamat_pengiriman)
                    <div class="info-row" style="align-items:flex-start;">
                        <span class="info-key">Alamat</span>
                        <span class="info-val" style="max-width:280px;">{{ $pesanan->alamat_pengiriman }}</span>
                    </div>
                @endif
                <div class="info-row">
                    <span class="info-key">Metode Pembayaran</span>
                    <span class="info-val">{{ $pesanan->metode_pembayaran === 'cash' ? '💵 Cash' : '📱 Transfer Bank/E-Wallet' }}</span>
                </div>
                @if($pesanan->catatan)
                    <div class="info-row" style="align-items:flex-start;">
                        <span class="info-key">Catatan</span>
                        <span class="info-val" style="max-width:280px;color:var(--warning);">📝 {{ $pesanan->catatan }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div>
        <!-- Update Status Form -->
        <div class="status-card" style="margin-bottom:16px;">
            <div class="status-header">⚙️ Update Status Pesanan</div>
            <div class="status-body">
                @if($pesanan->status === 'pending')
                    <div class="stok-warning-box">
                        ⚠️ Mengubah status ke <strong>Diproses</strong> akan otomatis mengurangi stok cireng mentah sesuai jumlah pesanan ({{ $pesanan->detailPesanans->sum('qty') }} pcs).
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.pesanan.updateStatus', $pesanan->id) }}">
                    @csrf @method('PATCH')
                    <div class="form-group">
                        <label class="form-label">Status Pesanan</label>
                        <select name="status" class="form-control">
                            <option value="pending"    {{ $pesanan->status === 'pending'    ? 'selected' : '' }}>🕐 Pending</option>
                            <option value="diproses"   {{ $pesanan->status === 'diproses'   ? 'selected' : '' }}>👩‍🍳 Diproses</option>
                            <option value="siap"       {{ $pesanan->status === 'siap'       ? 'selected' : '' }}>✅ Siap Diambil/Dikirim</option>
                            <option value="selesai"    {{ $pesanan->status === 'selesai'    ? 'selected' : '' }}>🎉 Selesai</option>
                            <option value="dibatalkan" {{ $pesanan->status === 'dibatalkan' ? 'selected' : '' }}>❌ Dibatalkan</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Status Pembayaran</label>
                        <select name="status_pembayaran" class="form-control">
                            <option value="belum_dibayar" {{ $pesanan->status_pembayaran === 'belum_dibayar' ? 'selected' : '' }}>⏳ Belum Dibayar</option>
                            <option value="lunas"         {{ $pesanan->status_pembayaran === 'lunas'         ? 'selected' : '' }}>✅ Lunas</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%;margin-top:16px;">
                        💾 Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>

        <!-- Bukti Transfer -->
        @if($pesanan->bukti_pembayaran)
            <div class="card">
                <div class="card-header">
                    <div class="card-title">📸 Bukti Transfer</div>
                </div>
                <div class="card-body" style="padding:16px;">
                    <div class="bukti-box">
                        <img src="{{ $pesanan->bukti_pembayaran_url }}" alt="Bukti Transfer">
                        <div class="bukti-actions">
                            <a href="{{ $pesanan->bukti_pembayaran_url }}" target="_blank"
                               class="btn btn-sm btn-secondary">🔍 Lihat Full</a>

                            @if($pesanan->status_pembayaran === 'belum_dibayar')
                                <form method="POST" action="{{ route('admin.pesanan.verifikasi', $pesanan->id) }}"
                                      onsubmit="return confirm('Verifikasi pembayaran ini sebagai LUNAS?')">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-success">✅ Verifikasi Lunas</button>
                                </form>
                            @else
                                <div class="verified-box" style="flex:1;">✅ Pembayaran Terverifikasi</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-body" style="text-align:center;padding:20px;color:var(--text-muted);">
                    @if($pesanan->metode_pembayaran === 'transfer')
                        <div style="font-size:1.5rem;margin-bottom:8px;">📸</div>
                        <div style="font-size:0.83rem;font-weight:500;">Belum ada bukti transfer diunggah</div>
                    @else
                        <div style="font-size:1.5rem;margin-bottom:8px;">💵</div>
                        <div style="font-size:0.83rem;font-weight:500;">Pembayaran Cash (tidak ada bukti)</div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
