@extends('layouts.driver')

@section('title', 'Riwayat Pesanan Saya')

@section('content')
<div style="margin-bottom:20px;">
    <div style="font-size:1.4rem;font-weight:900;">📋 Riwayat Pesanan Saya</div>
    <div style="color:#78716c;font-size:0.875rem;margin-top:3px;">Semua pesanan yang pernah atau sedang Anda tangani</div>
</div>

@if($pesanans->isEmpty())
    <div class="card">
        <div class="card-body" style="text-align:center;padding:60px;color:#78716c;">
            <div style="font-size:3rem;margin-bottom:12px;">📭</div>
            <div style="font-weight:700;font-size:1rem;">Belum ada riwayat pesanan</div>
            <div style="font-size:0.83rem;margin-top:4px;">Ambil pesanan dari pool di Dashboard</div>
            <a href="{{ route('driver.dashboard') }}" class="btn btn-primary" style="margin-top:14px;display:inline-flex;">🏠 Ke Dashboard</a>
        </div>
    </div>
@else
    @foreach($pesanans as $pesanan)
        @php $isSelesai = $pesanan->status === 'selesai'; @endphp
        <div class="card" style="margin-bottom:12px;">
            <div style="padding:16px 20px;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:12px;">
                    <div style="flex:1;">
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;flex-wrap:wrap;">
                            <span style="font-family:monospace;font-weight:700;color:var(--primary);font-size:0.95rem;">{{ $pesanan->kode_pesanan }}</span>
                            <span style="padding:3px 10px;border-radius:20px;font-size:0.72rem;font-weight:700;
                                {{ $isSelesai ? 'background:#d1fae5;color:#065f46;' : 'background:#dbeafe;color:#1e40af;' }}">
                                {{ $pesanan->status_label }}
                            </span>
                            @if($pesanan->metode_pembayaran === 'cod')
                                <span style="padding:3px 10px;border-radius:20px;font-size:0.72rem;font-weight:700;background:#fee2e2;color:#991b1b;">💵 COD</span>
                            @endif
                        </div>
                        <div style="font-size:0.9rem;font-weight:700;color:#1c1917;">{{ $pesanan->user->name }}</div>
                        <div style="font-size:0.8rem;color:#78716c;margin-top:2px;">
                            {{-- Privasi: jika selesai, samarkan alamat --}}
                            @if($isSelesai)
                                📍 <span style="font-style:italic;color:#a8a29e;">{{ $pesanan->masked_alamat }}</span>
                            @else
                                📍 {{ $pesanan->alamat_pengiriman }}
                            @endif
                        </div>
                        @if($pesanan->jarak_meter)
                            <div style="font-size:0.75rem;color:#78716c;margin-top:2px;">
                                📏 {{ number_format($pesanan->jarak_meter/1000,1) }} km
                            </div>
                        @endif
                    </div>
                    <div style="text-align:right;">
                        <div style="font-size:1rem;font-weight:800;color:var(--primary);">{{ $pesanan->total_akhir_formatted }}</div>
                        <div style="font-size:0.72rem;color:#78716c;margin-bottom:10px;">{{ $pesanan->updated_at->format('d M Y, H:i') }}</div>
                        <a href="{{ route('driver.pesanan.show', $pesanan->id) }}" class="btn btn-secondary btn-sm">👁️ Detail</a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Pagination --}}
    @if($pesanans->hasPages())
        <div style="display:flex;gap:4px;justify-content:center;padding:10px 0;">
            @if(!$pesanans->onFirstPage()) <a href="{{ $pesanans->previousPageUrl() }}" style="padding:6px 12px;background:#fff;border:1px solid #e7e5e4;border-radius:8px;text-decoration:none;color:#1c1917;">«</a> @endif
            @for($i=1;$i<=$pesanans->lastPage();$i++)
                @if($i==$pesanans->currentPage())
                    <span style="padding:6px 12px;background:var(--primary);color:#fff;border-radius:8px;">{{ $i }}</span>
                @else
                    <a href="{{ $pesanans->url($i) }}" style="padding:6px 12px;background:#fff;border:1px solid #e7e5e4;border-radius:8px;text-decoration:none;color:#1c1917;">{{ $i }}</a>
                @endif
            @endfor
            @if($pesanans->hasMorePages()) <a href="{{ $pesanans->nextPageUrl() }}" style="padding:6px 12px;background:#fff;border:1px solid #e7e5e4;border-radius:8px;text-decoration:none;color:#1c1917;">»</a> @endif
        </div>
    @endif
@endif
@endsection
