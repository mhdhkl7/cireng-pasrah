@extends('layouts.customer')

@section('title', 'Riwayat Pesanan')
@section('styles')
<style>
    .page-title { font-size: 1.5rem; font-weight: 800; color: #1c1917; margin-bottom: 6px; }
    .page-title span { color: #0ea5e9; }
    .page-subtitle { color: #78716c; font-size: 0.875rem; margin-bottom: 28px; }

    .pesanan-list { display: flex; flex-direction: column; gap: 16px; }

    .pesanan-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e7e5e4;
        overflow: hidden;
        transition: box-shadow 0.2s;
    }
    .pesanan-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.07); }

    .pesanan-header {
        padding: 16px 20px;
        border-bottom: 1px solid #f5f5f4;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }

    .pesanan-kode {
        font-size: 0.9rem;
        font-weight: 800;
        color: #1c1917;
    }

    .pesanan-tanggal {
        font-size: 0.75rem;
        color: #78716c;
        margin-top: 2px;
    }

    .pesanan-badges { display: flex; gap: 6px; flex-wrap: wrap; }

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

    .pesanan-body {
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .pesanan-items {
        font-size: 0.83rem;
        color: #78716c;
        flex: 1;
    }
    .pesanan-items strong { color: #1c1917; }

    .pesanan-info { text-align: right; }

    .pesanan-total {
        font-size: 1rem;
        font-weight: 800;
        color: #0ea5e9;
    }

    .pesanan-meta {
        font-size: 0.75rem;
        color: #78716c;
        margin-top: 3px;
    }

    .btn-detail {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        background: #f0f9ff;
        color: #0ea5e9;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 700;
        text-decoration: none;
        border: 1px solid #fed7aa;
        transition: all 0.2s;
    }
    .btn-detail:hover { background: #0ea5e9; color: #fff; }

    .empty-state {
        text-align: center;
        padding: 80px 24px;
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e7e5e4;
    }
    .empty-emoji { font-size: 4rem; margin-bottom: 16px; }
    .empty-title { font-size: 1.2rem; font-weight: 700; margin-bottom: 8px; }
    .empty-desc { color: #78716c; margin-bottom: 20px; }

    .btn-shop {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: linear-gradient(135deg, #0ea5e9, #10b981);
        color: #fff;
        border-radius: 12px;
        font-weight: 700;
        text-decoration: none;
        font-size: 0.9rem;
    }
</style>
@endsection

@section('content')
<div class="page-title">📋 Riwayat <span>Pesanan</span></div>
<div class="page-subtitle">Pantau status pesanan Anda di sini</div>

@if($pesanans->isEmpty())
    <div class="empty-state">
        <div class="empty-emoji">📦</div>
        <div class="empty-title">Belum Ada Pesanan</div>
        <p class="empty-desc">Anda belum pernah melakukan pemesanan. Yuk coba cireng kami!</p>
        <a href="{{ route('katalog.index') }}" class="btn-shop">🍟 Pesan Sekarang</a>
    </div>
@else
    <div class="pesanan-list">
        @foreach($pesanans as $pesanan)
            <div class="pesanan-card">
                <div class="pesanan-header">
                    <div>
                        <div class="pesanan-kode">🏷️ {{ $pesanan->kode_pesanan }}</div>
                        <div class="pesanan-tanggal">{{ $pesanan->created_at->format('d M Y, H:i') }}</div>
                    </div>
                    <div class="pesanan-badges">
                        <span class="badge badge-{{ $pesanan->status }}">
                            {{ $pesanan->status_label }}
                        </span>
                        <span class="badge {{ $pesanan->status_pembayaran === 'lunas' ? 'badge-lunas' : 'badge-belum' }}">
                            {{ $pesanan->status_pembayaran === 'lunas' ? '✅ Lunas' : '⏳ Belum Dibayar' }}
                        </span>
                    </div>
                </div>
                <div class="pesanan-body">
                    {{-- Thumbnail produk --}}
                    <div style="display:flex;gap:6px;align-items:center;flex-shrink:0;">
                        @foreach($pesanan->detailPesanans->take(3) as $detail)
                            <div style="width:44px;height:44px;border-radius:8px;overflow:hidden;background:#f0f9ff;border:1px solid #e7e5e4;flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                                @if($detail->produk && $detail->produk->gambar)
                                    <img src="{{ asset('storage/' . $detail->produk->gambar) }}" alt="{{ $detail->nama_produk }}"
                                         style="width:100%;height:100%;object-fit:cover;">
                                @else
                                    <span style="font-size:1.2rem;">🍟</span>
                                @endif
                            </div>
                        @endforeach
                        @if($pesanan->detailPesanans->count() > 3)
                            <div style="width:44px;height:44px;border-radius:8px;background:#f0f9ff;border:1px solid #e7e5e4;display:flex;align-items:center;justify-content:center;font-size:0.7rem;font-weight:700;color:#0ea5e9;">
                                +{{ $pesanan->detailPesanans->count() - 3 }}
                            </div>
                        @endif
                    </div>

                    <div class="pesanan-items">
                        @foreach($pesanan->detailPesanans->take(2) as $detail)
                            <div><strong>{{ $detail->nama_produk }}</strong> x{{ $detail->qty }}</div>
                        @endforeach
                        @if($pesanan->detailPesanans->count() > 2)
                            <div style="color:#0ea5e9;">+{{ $pesanan->detailPesanans->count() - 2 }} item lainnya</div>
                        @endif
                    </div>
                    <div class="pesanan-info">
                        <div class="pesanan-total">{{ $pesanan->total_akhir_formatted }}</div>
                        @if($pesanan->ongkir > 0)
                            <div style="font-size:0.72rem;color:#78716c;">Termasuk ongkir {{ $pesanan->ongkir_formatted }}</div>
                        @endif
                        <div class="pesanan-meta">
                            {{ $pesanan->opsi_pengiriman === 'take_away' ? '🏃 Take Away' : '🛵 Delivery' }} •
                            {{ $pesanan->metode_pembayaran === 'cash' ? '💵 Cash' : ($pesanan->metode_pembayaran === 'cod' ? '🚗 COD' : '📱 Transfer') }}
                        </div>
                    </div>
                    <a href="{{ route('pesanan.show', $pesanan->kode_pesanan) }}" class="btn-detail">
                        👁️ Detail
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="pagination">
        @if($pesanans->onFirstPage())
            <span>«</span>
        @else
            <a href="{{ $pesanans->previousPageUrl() }}">«</a>
        @endif

        @for($i = 1; $i <= $pesanans->lastPage(); $i++)
            @if($i == $pesanans->currentPage())
                <span class="active-page">{{ $i }}</span>
            @else
                <a href="{{ $pesanans->url($i) }}">{{ $i }}</a>
            @endif
        @endfor

        @if($pesanans->hasMorePages())
            <a href="{{ $pesanans->nextPageUrl() }}">»</a>
        @else
            <span>»</span>
        @endif
    </div>
@endif
@endsection
