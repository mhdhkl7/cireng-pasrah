@extends('layouts.admin')

@section('title', 'Manajemen Pesanan')
@section('page-title', 'Pesanan Masuk')
@section('page-subtitle', 'Kelola semua pesanan customer')

@section('styles')
<style>
    /* ── Stats Bar ── */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        margin-bottom: 20px;
    }
    @media(max-width:900px) { .stats-row { grid-template-columns: repeat(2,1fr); } }
    .stat-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 14px 18px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .stat-icon { font-size: 1.6rem; }
    .stat-label { font-size: 0.72rem; color: var(--text-muted); font-weight: 500; }
    .stat-value { font-size: 1.4rem; font-weight: 800; color: var(--text); line-height: 1; }
    .stat-card.danger { border-color: #fca5a5; background: #fef2f2; }
    .stat-card.danger .stat-value { color: var(--danger); }
    .stat-card.warning { border-color: #fde68a; background: #fffbeb; }
    .stat-card.warning .stat-value { color: #b45309; }

    /* ── Filter Bar ── */
    .filter-bar {
        background: var(--card);
        border-radius: var(--radius);
        border: 1px solid var(--border);
        padding: 14px 20px;
        margin-bottom: 16px;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: center;
    }
    .filter-bar select, .filter-bar input[type=text] {
        padding: 8px 12px;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        font-size: 0.83rem;
        font-family: 'Inter', sans-serif;
        color: var(--text);
        background: var(--bg);
        transition: border-color 0.2s;
    }
    .filter-bar select:focus, .filter-bar input:focus {
        outline: none;
        border-color: var(--primary);
    }
    .filter-bar input[type=text] { min-width: 200px; }

    /* ── Order Cards ── */
    .order-list { display: flex; flex-direction: column; gap: 10px; }

    .order-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        overflow: hidden;
        transition: box-shadow 0.2s, border-color 0.2s;
    }
    .order-card:hover { box-shadow: var(--shadow-lg); border-color: var(--primary); }
    .order-card.refund-alert { border-color: #fca5a5; border-left: 4px solid var(--danger); }
    .order-card.terlambat-alert { border-color: #fde68a; border-left: 4px solid var(--warning); }

    .order-card-inner {
        display: grid;
        grid-template-columns: 190px 1fr auto;
        gap: 0;
        align-items: stretch;
    }
    @media(max-width:900px) { .order-card-inner { grid-template-columns: 1fr; } }

    /* Left: Kode + Status */
    .order-left {
        padding: 16px 18px;
        border-right: 1px solid var(--border);
        display: flex;
        flex-direction: column;
        gap: 8px;
        justify-content: center;
    }
    .order-code {
        font-family: monospace;
        font-size: 0.92rem;
        font-weight: 700;
        color: var(--primary);
        text-decoration: none;
        word-break: break-all;
    }
    .order-code:hover { text-decoration: underline; }
    .order-time { font-size: 0.72rem; color: var(--text-muted); }

    /* Middle: Info */
    .order-middle {
        padding: 14px 20px;
        display: flex;
        flex-wrap: wrap;
        gap: 12px 24px;
        align-items: center;
    }
    .order-info-block { display: flex; flex-direction: column; gap: 2px; }
    .order-info-label { font-size: 0.68rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
    .order-info-value { font-size: 0.85rem; font-weight: 600; color: var(--text); }
    .order-info-value.total { color: var(--primary); font-size: 0.95rem; font-weight: 800; }

    /* Right: Actions */
    .order-right {
        padding: 14px 18px;
        border-left: 1px solid var(--border);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-width: 110px;
    }

    /* Badges */
    .badge { display: inline-flex; align-items: center; gap: 3px; padding: 3px 10px; border-radius: 20px; font-size: 0.7rem; font-weight: 600; }
    .badge-pending    { background: #fef9c3; color: #854d0e; }
    .badge-diproses   { background: #dbeafe; color: #1e40af; }
    .badge-siap       { background: #d1fae5; color: #065f46; }
    .badge-selesai    { background: #dcfce7; color: #14532d; }
    .badge-dibatalkan { background: #fee2e2; color: #991b1b; }
    .badge-tidak_diambil { background: #fef3c7; color: #92400e; }
    .badge-lunas      { background: #d1fae5; color: #065f46; }
    .badge-belum      { background: #f5f5f4; color: #78716c; }
    .badge-refund     { background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; }
    .badge-cod        { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }

    .empty-row {
        text-align: center;
        padding: 60px 24px;
        color: var(--text-muted);
        font-size: 0.9rem;
    }
    .empty-row .empty-icon { font-size: 3rem; margin-bottom: 12px; }
</style>
@endsection

@section('content')

{{-- Stats Row --}}
<div class="stats-row">
    <div class="stat-card">
        <span class="stat-icon">🕐</span>
        <div>
            <div class="stat-label">Pending</div>
            <div class="stat-value">{{ $stats['pending'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <span class="stat-icon">👩‍🍳</span>
        <div>
            <div class="stat-label">Diproses</div>
            <div class="stat-value">{{ $stats['diproses'] }}</div>
        </div>
    </div>
    <div class="stat-card {{ $stats['perlu_refund'] > 0 ? 'danger' : '' }}">
        <span class="stat-icon">⚠️</span>
        <div>
            <div class="stat-label">Perlu Refund</div>
            <div class="stat-value">{{ $stats['perlu_refund'] }}</div>
        </div>
    </div>
    <div class="stat-card {{ $stats['terlambat'] > 0 ? 'warning' : '' }}">
        <span class="stat-icon">⏰</span>
        <div>
            <div class="stat-label">Take Away Terlambat</div>
            <div class="stat-value">{{ $stats['terlambat'] }}</div>
        </div>
    </div>
</div>

{{-- Filter Bar --}}
<form method="GET" action="{{ route('admin.pesanan.index') }}" class="filter-bar">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="🔍 Cari kode / nama customer...">
    <select name="status">
        <option value="">Semua Status</option>
        <option value="pending"       {{ request('status') === 'pending'       ? 'selected' : '' }}>🕐 Pending</option>
        <option value="diproses"      {{ request('status') === 'diproses'      ? 'selected' : '' }}>👩‍🍳 Diproses</option>
        <option value="siap"          {{ request('status') === 'siap'          ? 'selected' : '' }}>✅ Siap</option>
        <option value="selesai"       {{ request('status') === 'selesai'       ? 'selected' : '' }}>🎉 Selesai</option>
        <option value="dibatalkan"    {{ request('status') === 'dibatalkan'    ? 'selected' : '' }}>❌ Dibatalkan</option>
        <option value="tidak_diambil" {{ request('status') === 'tidak_diambil' ? 'selected' : '' }}>📦 Tidak Diambil</option>
    </select>
    <select name="status_pembayaran">
        <option value="">Semua Pembayaran</option>
        <option value="belum_dibayar" {{ request('status_pembayaran') === 'belum_dibayar' ? 'selected' : '' }}>⏳ Belum Dibayar</option>
        <option value="lunas"         {{ request('status_pembayaran') === 'lunas'         ? 'selected' : '' }}>✅ Lunas</option>
    </select>
    <select name="opsi_pengiriman">
        <option value="">Semua Pengiriman</option>
        <option value="take_away" {{ request('opsi_pengiriman') === 'take_away' ? 'selected' : '' }}>🏃 Take Away</option>
        <option value="delivery"  {{ request('opsi_pengiriman') === 'delivery'  ? 'selected' : '' }}>🛵 Delivery</option>
    </select>
    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
    <a href="{{ route('admin.pesanan.index') }}" class="btn btn-secondary btn-sm">Reset</a>
    <span style="margin-left:auto;font-size:0.8rem;color:var(--text-muted);">
        Total: <strong>{{ $pesanans->total() }}</strong> pesanan
    </span>
</form>

{{-- Order List --}}
<div class="order-list">
    @forelse($pesanans as $pesanan)
        @php
            $isRefund    = $pesanan->perlu_refund;
            $isTerlambat = $pesanan->terlambat_diambil;
        @endphp
        <div class="order-card {{ $isRefund ? 'refund-alert' : ($isTerlambat ? 'terlambat-alert' : '') }}">
            <div class="order-card-inner">
                {{-- Left --}}
                <div class="order-left">
                    <a href="{{ route('admin.pesanan.show', $pesanan->id) }}" class="order-code">
                        {{ $pesanan->kode_pesanan }}
                    </a>
                    <div>
                        <span class="badge badge-{{ $pesanan->status }}">{{ $pesanan->status_label }}</span>
                        <span class="badge {{ $pesanan->status_pembayaran === 'lunas' ? 'badge-lunas' : 'badge-belum' }}" style="margin-left:4px;">
                            {{ $pesanan->status_pembayaran === 'lunas' ? 'Lunas' : 'Belum Bayar' }}
                        </span>
                    </div>
                    @if($isRefund)
                        <span class="badge badge-refund">⚠️ Perlu Refund</span>
                    @endif
                    @if($isTerlambat)
                        <span class="badge" style="background:#fef9c3;color:#854d0e;">⏰ Terlambat Diambil</span>
                    @endif
                    <div class="order-time">{{ $pesanan->created_at->format('d M Y, H:i') }}</div>
                </div>

                {{-- Middle --}}
                <div class="order-middle">
                    <div class="order-info-block">
                        <div class="order-info-label">Customer</div>
                        <div class="order-info-value">{{ $pesanan->user->name }}</div>
                    </div>
                    <div class="order-info-block">
                        <div class="order-info-label">Pengiriman</div>
                        <div class="order-info-value">
                            {{ $pesanan->opsi_pengiriman === 'take_away' ? '🏃 Take Away' : '🛵 Delivery' }}
                        </div>
                    </div>
                    <div class="order-info-block">
                        <div class="order-info-label">Pembayaran</div>
                        <div class="order-info-value">
                            @if($pesanan->metode_pembayaran === 'cash')
                                💵 Cash
                            @elseif($pesanan->metode_pembayaran === 'transfer')
                                📱 Transfer
                                @if($pesanan->bukti_pembayaran)
                                    <span style="font-size:0.65rem;background:#dbeafe;color:#1e40af;padding:1px 6px;border-radius:4px;margin-left:4px;">Bukti ✓</span>
                                @endif
                            @else
                                🚗 COD
                                <span class="badge badge-cod" style="margin-left:4px;">COD</span>
                            @endif
                        </div>
                    </div>
                    <div class="order-info-block">
                        <div class="order-info-label">Subtotal</div>
                        <div class="order-info-value">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</div>
                    </div>
                    @if($pesanan->ongkir > 0)
                    <div class="order-info-block">
                        <div class="order-info-label">Ongkir</div>
                        <div class="order-info-value">{{ $pesanan->ongkir_formatted }}</div>
                    </div>
                    @endif
                    <div class="order-info-block">
                        <div class="order-info-label">Total Akhir</div>
                        <div class="order-info-value total">{{ $pesanan->total_akhir_formatted }}</div>
                    </div>
                    <div class="order-info-block">
                        <div class="order-info-label">Item</div>
                        <div class="order-info-value">{{ $pesanan->detailPesanans->sum('qty') }} pcs</div>
                    </div>
                </div>

                {{-- Right --}}
                <div class="order-right">
                    <a href="{{ route('admin.pesanan.show', $pesanan->id) }}" class="btn btn-primary btn-sm" style="width:100%;justify-content:center;">
                        👁️ Detail
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="order-card">
            <div class="empty-row">
                <div class="empty-icon">📭</div>
                Tidak ada pesanan ditemukan
            </div>
        </div>
    @endforelse
</div>

{{-- Pagination --}}
<div class="pagination" style="margin-top:16px;">
    @if($pesanans->onFirstPage()) <span>«</span>
    @else <a href="{{ $pesanans->previousPageUrl() }}">«</a> @endif
    @for($i = 1; $i <= $pesanans->lastPage(); $i++)
        @if($i == $pesanans->currentPage()) <span class="active-page">{{ $i }}</span>
        @else <a href="{{ $pesanans->url($i) }}">{{ $i }}</a> @endif
    @endfor
    @if($pesanans->hasMorePages()) <a href="{{ $pesanans->nextPageUrl() }}">»</a>
    @else <span>»</span> @endif
</div>

@endsection
