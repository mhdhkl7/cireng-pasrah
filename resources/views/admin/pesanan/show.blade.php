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
    }

    .status-header {
        background: var(--primary);
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

    .refund-alert-box {
        background: #fee2e2;
        border: 1px solid #fca5a5;
        border-radius: 8px;
        padding: 14px;
        font-size: 0.83rem;
        color: #991b1b;
        font-weight: 600;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .badge-tidak_diambil { background: #fef3c7; color: #92400e; }
</style>
@endsection

@section('content')
<div style="margin-bottom:16px;">
    <a href="{{ route('admin.pesanan.index') }}" style="font-size:0.83rem;color:var(--text-muted);text-decoration:none;">
        ← Kembali ke Daftar Pesanan
    </a>
</div>

{{-- Alert: Perlu Refund --}}
@if($pesanan->perlu_refund)
    <div class="refund-alert-box">
        <span style="font-size:1.5rem;">⚠️</span>
        <div>
            <div>Pesanan ini <strong>sudah Lunas</strong> tapi statusnya <strong>{{ $pesanan->status_label }}</strong>.</div>
            <div style="font-size:0.78rem;margin-top:3px;">Harap proses <strong>refund / pengembalian dana</strong> kepada customer.</div>
        </div>
    </div>
@endif

{{-- Alert: Take Away Terlambat --}}
@if($pesanan->terlambat_diambil)
    <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:8px;padding:14px;margin-bottom:16px;font-size:0.83rem;color:#92400e;font-weight:600;display:flex;align-items:center;gap:10px;">
        <span style="font-size:1.5rem;">⏰</span>
        <div>
            <div>Pesanan Take Away ini sudah <strong>siap {{ $pesanan->siap_at?->diffForHumans() }}</strong> tapi belum diambil!</div>
            <div style="font-size:0.78rem;margin-top:3px;">Pertimbangkan untuk menandai sebagai "Tidak Diambil".</div>
        </div>
    </div>
@endif

<div style="background:#f0f9ff;border:1px solid rgba(14,165,233,0.2);border-radius:16px;padding:18px 20px;margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
    <div>
        <div style="font-size:1.1rem;font-weight:900;">🏷️ {{ $pesanan->kode_pesanan }}</div>
        <div style="font-size:0.78rem;color:var(--text-muted);margin-top:3px;">
            {{ $pesanan->created_at->format('l, d F Y - H:i') }} WIB
        </div>
        @if($pesanan->siap_at)
            <div style="font-size:0.75rem;color:var(--text-muted);margin-top:2px;">
                Siap sejak: {{ $pesanan->siap_at->format('d M Y, H:i') }} ({{ $pesanan->siap_at->diffForHumans() }})
            </div>
        @endif
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <span class="badge badge-{{ $pesanan->status }}">{{ $pesanan->status_label }}</span>
        <span class="badge {{ $pesanan->status_pembayaran === 'lunas' ? 'badge-lunas' : 'badge-belum' }}">
            {{ $pesanan->status_pembayaran === 'lunas' ? '✅ Lunas' : '⏳ Belum Bayar' }}
        </span>
        @if($pesanan->opsi_pengiriman === 'delivery' && $pesanan->metode_pembayaran === 'cod')
            <span class="badge" style="background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0;">🚗 COD</span>
        @endif
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
                    <span class="info-val">{{ $pesanan->metode_pembayaran === 'cash' ? '💵 Cash' : ($pesanan->metode_pembayaran === 'cod' ? '🚗 COD' : '📱 Transfer Bank/E-Wallet') }}</span>
                </div>
                @if($pesanan->catatan)
                    <div class="info-row" style="align-items:flex-start;">
                        <span class="info-key">Catatan</span>
                        <span class="info-val" style="max-width:280px;color:var(--warning);">📝 {{ $pesanan->catatan }}</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Info Driver (jika delivery) --}}
        @if($pesanan->opsi_pengiriman === 'delivery')
        <div class="card" style="margin-top:16px;">
            <div class="card-header">
                <div class="card-title">🚗 Info Driver</div>
            </div>
            <div class="card-body">
                @if($pesanan->driver)
                    <div class="info-row">
                        <span class="info-key">Driver</span>
                        <span class="info-val" style="color:var(--primary);font-weight:700;">{{ $pesanan->driver->name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Telepon Driver</span>
                        <span class="info-val">{{ $pesanan->driver->phone ?? '-' }}</span>
                    </div>
                    @if($pesanan->diambil_driver_at)
                        <div class="info-row">
                            <span class="info-key">Diambil pada</span>
                            <span class="info-val">{{ $pesanan->diambil_driver_at->format('d M Y, H:i') }}</span>
                        </div>
                    @endif
                @else
                    <div style="text-align:center;padding:14px;color:#78716c;">
                        @if($pesanan->status === 'mencari_driver')
                            <div>🔍 Sedang mencari driver dari pool...</div>
                        @else
                            <div>Belum ada driver yang ditugaskan</div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Right Column -->
    <div style="position: sticky; top: 80px; align-self: start;">
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
                            <option value="pending"          {{ $pesanan->status === 'pending'          ? 'selected' : '' }}>🕐 Pending</option>
                            <option value="diproses"         {{ $pesanan->status === 'diproses'         ? 'selected' : '' }}>👩‍🍳 Diproses</option>
                            <option value="siap"             {{ $pesanan->status === 'siap'             ? 'selected' : '' }}>✅ Siap (→ pool driver jika delivery)</option>
                            <option value="selesai"          {{ $pesanan->status === 'selesai'          ? 'selected' : '' }}>🎉 Selesai</option>
                            <option value="dibatalkan"       {{ $pesanan->status === 'dibatalkan'       ? 'selected' : '' }}>❌ Dibatalkan</option>
                            <option value="tidak_diambil"   {{ $pesanan->status === 'tidak_diambil'   ? 'selected' : '' }}>📦 Tidak Diambil</option>
                        </select>
                        @if($pesanan->opsi_pengiriman === 'delivery')
                            <div style="font-size:0.72rem;color:#78716c;margin-top:4px;">ℹ️ Mengubah ke "Siap" akan otomatis masuk pool driver</div>
                        @endif
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Status Pembayaran</label>
                        <select name="status_pembayaran" class="form-control">
                            <option value="belum_dibayar" {{ $pesanan->status_pembayaran === 'belum_dibayar' ? 'selected' : '' }}>⏳ Belum Dibayar</option>
                            <option value="lunas"         {{ $pesanan->status_pembayaran === 'lunas'         ? 'selected' : '' }}>✅ Lunas</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin-top:14px;margin-bottom:0;" id="catatan-batal-wrap">
                        <label class="form-label">Catatan Pembatalan / Refund</label>
                        <textarea name="catatan_pembatalan" class="form-control" rows="2"
                                  placeholder="Alasan pembatalan atau info refund..."
                                  style="font-size:0.83rem;">{{ $pesanan->catatan_pembatalan }}</textarea>
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
