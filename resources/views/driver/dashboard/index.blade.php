@extends('layouts.driver')

@section('title', 'Dashboard Driver')

@section('styles')
<style>
    /* ── Stats Row ─────────────────────────── */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        margin-bottom: 24px;
    }
    @media(max-width:700px){ .stats-row { grid-template-columns: 1fr 1fr; } }
    .stat-card {
        background: #fff;
        border: 1px solid #e7e5e4;
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .stat-icon { font-size: 2rem; }
    .stat-label { font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #78716c; }
    .stat-value { font-size: 1.8rem; font-weight: 900; line-height: 1; }
    .stat-value.amber  { color: var(--primary); }
    .stat-value.green  { color: #10b981; }
    .stat-value.blue   { color: #2563eb; }

    /* ── Section ───────────────────────────── */
    .section-title {
        font-size: 1rem;
        font-weight: 800;
        color: #1c1917;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .section-title .count-badge {
        background: var(--primary);
        color: #fff;
        font-size: 0.7rem;
        padding: 2px 8px;
        border-radius: 20px;
    }

    /* ── Pool Card ─────────────────────────── */
    .pool-card {
        background: #fff;
        border: 2px solid var(--primary);
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }
    .pool-card:hover { border-color: var(--primary-dark); background: #fffbeb; }

    /* ── Aktif Card ────────────────────────── */
    .aktif-card {
        background: #fff;
        border: 1px solid #e7e5e4;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    /* ── Empty State ───────────────────────── */
    .empty-state {
        text-align: center;
        padding: 32px;
        color: #78716c;
        font-size: 0.875rem;
    }
    .empty-state .icon { font-size: 2.5rem; margin-bottom: 10px; }

    /* ── Status Pill ───────────────────────── */
    .pill {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
    }
    .pill-mencari     { background: #fef3c7; color: #92400e; }
    .pill-menuju      { background: #dbeafe; color: #1e40af; }
    .pill-tiba        { background: #e0e7ff; color: #4338ca; }
    .pill-mengantar   { background: #d1fae5; color: #065f46; }
    .pill-selesai     { background: #f0fdf4; color: #15803d; }

    /* ── Riwayat ───────────────────────────── */
    .riwayat-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 20px;
        border-bottom: 1px solid #f5f5f4;
        font-size: 0.83rem;
    }
    .riwayat-row:last-child { border-bottom: none; }
    .masked-addr { color: #a8a29e; font-style: italic; font-size: 0.75rem; }
</style>
@endsection

@section('content')

<div style="margin-bottom: 24px;">
    <div style="font-size:1.4rem;font-weight:900;color:#1c1917;">🚗 Dashboard Driver</div>
    <div style="font-size:0.875rem;color:#78716c;margin-top:3px;">Selamat datang, {{ auth()->user()->name }}</div>
</div>

{{-- Stats --}}
<div class="stats-row">
    <div class="stat-card">
        <span class="stat-icon">📦</span>
        <div>
            <div class="stat-label">Pool Tersedia</div>
            <div class="stat-value amber">{{ $stats['pool'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <span class="stat-icon">🏃</span>
        <div>
            <div class="stat-label">Pesanan Aktif Saya</div>
            <div class="stat-value blue">{{ $stats['aktif'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <span class="stat-icon">✅</span>
        <div>
            <div class="stat-label">Selesai Hari Ini</div>
            <div class="stat-value green">{{ $stats['selesai_hari_ini'] }}</div>
        </div>
    </div>
</div>

{{-- ===== PESANAN SAYA (AKTIF) ===== --}}
@if($pesananAktif->isNotEmpty())
<div style="margin-bottom: 24px;">
    <div class="section-title">
        🏃 Pesanan Saya (Aktif)
        <span class="count-badge">{{ $pesananAktif->count() }}</span>
    </div>
    @foreach($pesananAktif as $p)
        @php
            $pillClass = match($p->status) {
                'driver_menuju_resto' => 'pill-menuju',
                'tiba_di_resto'       => 'pill-tiba',
                'sedang_mengantar'    => 'pill-mengantar',
                default               => 'pill-mencari',
            };
        @endphp
        <div class="aktif-card">
            <div style="flex:1;">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                    <span style="font-family:monospace;font-weight:700;font-size:0.9rem;">{{ $p->kode_pesanan }}</span>
                    <span class="pill {{ $pillClass }}">{{ $p->status_label }}</span>
                    @if($p->isCodBelumBayar())
                        <span class="pill" style="background:#fee2e2;color:#991b1b;">💵 COD</span>
                    @endif
                </div>
                <div style="font-size:0.83rem;font-weight:600;">{{ $p->user->name }}</div>
                <div style="font-size:0.75rem;color:#78716c;margin-top:2px;">📍 {{ $p->alamat_pengiriman }}</div>
                @if($p->jarak_meter)
                    <div style="font-size:0.72rem;color:#78716c;">📏 {{ number_format($p->jarak_meter/1000,1) }} km</div>
                @endif
            </div>
            <div style="display:flex;flex-direction:column;gap:6px;align-items:flex-end;">
                <div style="font-weight:800;color:var(--primary);">{{ $p->total_akhir_formatted }}</div>
                <a href="{{ route('driver.pesanan.show', $p->id) }}" class="btn btn-primary btn-sm">▶ Lanjutkan</a>
            </div>
        </div>
    @endforeach
</div>
@endif

{{-- ===== POOL PESANAN TERBUKA ===== --}}
<div style="margin-bottom: 24px;">
    <div class="section-title">
        📦 Pool Pesanan — Tersedia untuk Diambil
        <span class="count-badge">{{ $stats['pool'] }}</span>
    </div>

    @if($poolPesanan->isEmpty())
        <div class="empty-state">
            <div class="icon">🎉</div>
            <div style="font-weight:600;">Tidak ada pesanan di pool saat ini</div>
            <div style="margin-top:4px;font-size:0.78rem;">Tunggu Admin memproses pesanan baru</div>
        </div>
    @else
        @foreach($poolPesanan as $p)
            <div class="pool-card">
                <div style="flex:1;">
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                        <span style="font-family:monospace;font-weight:700;color:var(--primary);font-size:0.9rem;">{{ $p->kode_pesanan }}</span>
                        <span class="pill pill-mencari">Menunggu Driver</span>
                        @if($p->metode_pembayaran === 'cod')
                            <span class="pill" style="background:#fee2e2;color:#991b1b;">💵 COD</span>
                        @endif
                    </div>
                    <div style="font-size:0.83rem;font-weight:600;">{{ $p->user->name }}</div>
                    <div style="font-size:0.75rem;color:#78716c;margin-top:2px;">
                        📍 {{ Str::limit($p->alamat_pengiriman, 70) }}
                    </div>
                    <div style="font-size:0.72rem;color:#78716c;margin-top:2px;">
                        🕐 Masuk {{ $p->created_at->diffForHumans() }}
                        @if($p->jarak_meter)
                            • 📏 {{ number_format($p->jarak_meter/1000,1) }} km • Ongkir: {{ $p->ongkir_formatted }}
                        @endif
                    </div>
                    <div style="font-size:0.75rem;font-weight:700;margin-top:4px;">
                        Total: {{ $p->total_akhir_formatted }}
                    </div>
                </div>
                <div style="display:flex;flex-direction:column;gap:6px;align-items:flex-end;">
                    <a href="{{ route('driver.pesanan.show', $p->id) }}" class="btn btn-secondary btn-sm">👁️ Detail</a>
                    <form method="POST" action="{{ route('driver.pesanan.ambil', $p->id) }}"
                          onsubmit="return confirm('Ambil pesanan {{ $p->kode_pesanan }}? Anda bertanggung jawab mengantarkannya.')">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm">🚗 Ambil Pesanan</button>
                    </form>
                </div>
            </div>
        @endforeach
    @endif
</div>

{{-- ===== RIWAYAT SELESAI ===== --}}
@if($pesananSelesai->isNotEmpty())
<div class="card">
    <div class="card-header">
        <div class="card-title">🎉 Riwayat Pengiriman Selesai</div>
    </div>
    @foreach($pesananSelesai as $p)
        <div class="riwayat-row">
            <div>
                <span style="font-family:monospace;font-weight:700;font-size:0.85rem;color:#78716c;">{{ $p->kode_pesanan }}</span>
                {{-- Alamat disamarkan untuk privasi customer --}}
                <div class="masked-addr">📍 {{ $p->masked_alamat }}</div>
            </div>
            <div style="display:flex;align-items:center;gap:8px;">
                <span class="pill pill-selesai">✅ Selesai</span>
                <span style="font-size:0.72rem;color:#78716c;">{{ $p->updated_at->format('d M, H:i') }}</span>
            </div>
        </div>
    @endforeach
</div>
@endif

@endsection
