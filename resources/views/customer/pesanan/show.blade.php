@extends('layouts.customer')

@section('title', 'Detail Pesanan ' . $pesanan->kode_pesanan)
@section('styles')
<style>
    .back-link {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 0.83rem; color: #78716c; text-decoration: none;
        margin-bottom: 20px; font-weight: 500;
    }
    .back-link:hover { color: #0ea5e9; }

    .detail-layout {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 20px;
        align-items: start;
    }
    @media(max-width:768px) { .detail-layout { grid-template-columns: 1fr; } }

    .detail-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e7e5e4;
        overflow: hidden;
        margin-bottom: 16px;
    }

    .detail-card-header {
        padding: 15px 20px;
        border-bottom: 1px solid #f5f5f4;
        font-size: 0.9rem;
        font-weight: 700;
        color: #1c1917;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .detail-card-body { padding: 20px; }

    /* Progress Timeline */
    .timeline {
        display: flex;
        flex-direction: column;
        gap: 0;
    }

    .timeline-item {
        display: flex;
        gap: 14px;
        position: relative;
    }

    .timeline-item:not(:last-child) .timeline-line {
        position: absolute;
        left: 18px;
        top: 36px;
        bottom: 0;
        width: 2px;
        background: #e7e5e4;
        z-index: 0;
    }

    .timeline-item.done .timeline-line { background: #0ea5e9; }

    .timeline-dot {
        width: 36px; height: 36px;
        border-radius: 50%;
        border: 2px solid #e7e5e4;
        background: #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.9rem;
        flex-shrink: 0;
        position: relative;
        z-index: 1;
    }

    .timeline-item.done .timeline-dot {
        background: #f0f9ff;
        border-color: #0ea5e9;
    }

    .timeline-item.active .timeline-dot {
        background: var(--primary);
        border-color: #0ea5e9;
        box-shadow: 0 0 0 4px rgba(14,165,233,0.2);
    }

    .timeline-content {
        padding-bottom: 20px;
    }

    .timeline-title {
        font-size: 0.875rem;
        font-weight: 700;
        color: #1c1917;
    }

    .timeline-item.pending-step .timeline-title { color: #a8a29e; }

    .timeline-desc {
        font-size: 0.75rem;
        color: #78716c;
        margin-top: 2px;
    }

    /* Item Table */
    .item-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid #f5f5f4;
    }
    .item-row:last-child { border-bottom: none; }
    .item-thumb {
        width: 48px; height: 48px;
        border-radius: 8px;
        overflow: hidden;
        background: #f0f9ff;
        flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.25rem;
    }
    .item-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .item-details { flex: 1; }
    .item-name { font-size: 0.875rem; font-weight: 700; color: #1c1917; }
    .item-price { font-size: 0.75rem; color: #78716c; }
    .item-subtotal { font-size: 0.875rem; font-weight: 700; color: #0ea5e9; }

    /* Info Rows */
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 9px 0;
        border-bottom: 1px solid #f5f5f4;
        font-size: 0.83rem;
    }
    .info-row:last-child { border-bottom: none; }
    .info-key { color: #78716c; font-weight: 500; flex-shrink: 0; min-width: 120px; }
    .info-val { font-weight: 600; color: #1c1917; text-align: right; }

    .badge {
        display: inline-flex; align-items: center;
        padding: 4px 10px; border-radius: 20px;
        font-size: 0.72rem; font-weight: 600;
    }
    .badge-pending    { background: #ccfbf1; color: #92400e; }
    .badge-diproses   { background: #dbeafe; color: #1e40af; }
    .badge-siap       { background: #d1fae5; color: #065f46; }
    .badge-selesai    { background: #dcfce7; color: #14532d; }
    .badge-dibatalkan { background: #fee2e2; color: #991b1b; }
    .badge-lunas      { background: #d1fae5; color: #065f46; }
    .badge-belum      { background: #ccfbf1; color: #92400e; }

    /* Total */
    .total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 0 0;
        border-top: 2px solid #f5f5f4;
        margin-top: 4px;
    }
    .total-label { font-size: 1rem; font-weight: 700; }
    .total-value { font-size: 1.15rem; font-weight: 800; color: #0ea5e9; }

    /* Bukti Transfer */
    .bukti-img {
        width: 100%;
        border-radius: 10px;
        border: 2px solid #e7e5e4;
        object-fit: cover;
        max-height: 300px;
        cursor: pointer;
        transition: transform 0.2s;
    }
    .bukti-img:hover { transform: scale(1.02); }

    /* Header Pesanan */
    .pesanan-header-card {
        background: #f0f9ff;
        border: 1px solid rgba(14,165,233,0.2);
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .kode-pesanan { font-size: 1.2rem; font-weight: 900; color: #1c1917; }
    .tanggal-pesanan { font-size: 0.8rem; color: #78716c; margin-top: 3px; }
</style>
@endsection

@section('content')
<a href="{{ route('pesanan.index') }}" class="back-link">← Kembali ke Riwayat Pesanan</a>

<div class="pesanan-header-card">
    <div>
        <div class="kode-pesanan">🏷️ {{ $pesanan->kode_pesanan }}</div>
        <div class="tanggal-pesanan">Dipesan pada {{ $pesanan->created_at->format('l, d F Y, H:i') }}</div>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <span class="badge badge-{{ $pesanan->status }}">{{ $pesanan->status_label }}</span>
        <span class="badge {{ $pesanan->status_pembayaran === 'lunas' ? 'badge-lunas' : 'badge-belum' }}">
            {{ $pesanan->status_pembayaran === 'lunas' ? '✅ Lunas' : '⏳ Menunggu Pembayaran' }}
        </span>
    </div>
</div>

<div class="detail-layout">
    <!-- Left Column -->
    <div>
        <!-- Item Pesanan -->
        <div class="detail-card">
            <div class="detail-card-header">🍟 Item Pesanan</div>
            <div class="detail-card-body">
                @foreach($pesanan->detailPesanans as $detail)
                    <div class="item-row">
                        <div class="item-thumb">
                            @if($detail->produk && $detail->produk->gambar)
                                <img src="{{ asset('storage/' . $detail->produk->gambar) }}" alt="{{ $detail->nama_produk }}">
                            @else
                                🍟
                            @endif
                        </div>
                        <div class="item-details">
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

        <!-- Status Tracking -->
        <div class="detail-card">
            <div class="detail-card-header">📍 Tracking Status Pesanan</div>
            <div class="detail-card-body">
                @php
                    $statusFlow = [
                        'pending'    => ['label' => 'Menunggu Konfirmasi', 'icon' => '🕐', 'desc' => 'Pesanan diterima, menunggu konfirmasi admin'],
                        'diproses'   => ['label' => 'Sedang Diproses', 'icon' => '👩‍🍳', 'desc' => 'Cireng sedang dalam proses pembuatan'],
                        'siap'       => ['label' => 'Siap Diambil/Dikirim', 'icon' => '✅', 'desc' => 'Pesanan sudah siap'],
                        'selesai'    => ['label' => 'Selesai', 'icon' => '🎉', 'desc' => 'Pesanan berhasil diterima'],
                        'dibatalkan' => ['label' => 'Dibatalkan', 'icon' => '❌', 'desc' => 'Pesanan dibatalkan'],
                    ];

                    $currentStatus = $pesanan->status;
                    $statusOrder   = ['pending', 'diproses', 'siap', 'selesai'];
                    $currentIndex  = array_search($currentStatus, $statusOrder);
                @endphp

                <div class="timeline">
                    @if($currentStatus === 'dibatalkan')
                        <div class="timeline-item active">
                            <div class="timeline-dot">❌</div>
                            <div class="timeline-content">
                                <div class="timeline-title">Pesanan Dibatalkan</div>
                                <div class="timeline-desc">Pesanan Anda telah dibatalkan</div>
                            </div>
                        </div>
                    @else
                        @foreach($statusOrder as $idx => $s)
                            @php
                                $isDone   = $currentIndex !== false && $idx < $currentIndex;
                                $isActive = $s === $currentStatus;
                                $isPending = $currentIndex !== false && $idx > $currentIndex;
                            @endphp
                            <div class="timeline-item {{ $isDone ? 'done' : '' }} {{ $isActive ? 'active' : '' }} {{ $isPending ? 'pending-step' : '' }}">
                                <div class="timeline-line"></div>
                                <div class="timeline-dot">
                                    @if($isDone) ✓
                                    @elseif($isActive) {{ $statusFlow[$s]['icon'] }}
                                    @else ○
                                    @endif
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-title">{{ $statusFlow[$s]['label'] }}</div>
                                    <div class="timeline-desc">{{ $statusFlow[$s]['desc'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div>
        <!-- Info Pesanan -->
        <div class="detail-card">
            <div class="detail-card-header">📋 Informasi Pesanan</div>
            <div class="detail-card-body">
                <div class="info-row">
                    <span class="info-key">Kode Pesanan</span>
                    <span class="info-val" style="font-family:monospace;">{{ $pesanan->kode_pesanan }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">Opsi Pengiriman</span>
                    <span class="info-val">{{ $pesanan->opsi_pengiriman === 'take_away' ? '🏃 Take Away' : '🛵 Delivery' }}</span>
                </div>
                @if($pesanan->alamat_pengiriman)
                    <div class="info-row">
                        <span class="info-key">Alamat</span>
                        <span class="info-val">{{ $pesanan->alamat_pengiriman }}</span>
                    </div>
                @endif
                <div class="info-row">
                    <span class="info-key">Pembayaran</span>
                    <span class="info-val">{{ $pesanan->metode_pembayaran === 'cash' ? '💵 Cash' : '📱 Transfer' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">Status Bayar</span>
                    <span class="info-val">
                        <span class="badge {{ $pesanan->status_pembayaran === 'lunas' ? 'badge-lunas' : 'badge-belum' }}">
                            {{ $pesanan->status_pembayaran === 'lunas' ? 'Lunas' : 'Belum Dibayar' }}
                        </span>
                    </span>
                </div>
                @if($pesanan->catatan)
                    <div class="info-row">
                        <span class="info-key">Catatan</span>
                        <span class="info-val">{{ $pesanan->catatan }}</span>
                    </div>
                @endif
            </div>
        </div>

        @if($pesanan->bukti_pembayaran)
            <div class="detail-card">
                <div class="detail-card-header">📸 Bukti Transfer</div>
                <div class="detail-card-body">
                    <a href="{{ $pesanan->bukti_pembayaran_url }}" target="_blank">
                        <img src="{{ $pesanan->bukti_pembayaran_url }}" alt="Bukti Transfer" class="bukti-img">
                    </a>
                    <p style="font-size:0.75rem;color:#78716c;margin-top:8px;text-align:center;">
                        Klik gambar untuk melihat ukuran penuh
                    </p>
                </div>
            </div>
        @endif

        @if($pesanan->status_pembayaran === 'belum_dibayar' && $pesanan->metode_pembayaran === 'transfer' && !$pesanan->bukti_pembayaran)
            <div class="detail-card" style="border-color:#fde68a;">
                <div class="detail-card-body" style="background:#fffbeb;">
                    <p style="font-size:0.83rem;color:#92400e;font-weight:600;">
                        ⚠️ Anda belum mengunggah bukti transfer. Silakan hubungi admin atau lakukan checkout ulang.
                    </p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
