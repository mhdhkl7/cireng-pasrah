@extends('layouts.driver')

@section('title', 'Detail Pesanan ' . $pesanan->kode_pesanan)

@section('styles')
<style>
    /* ── Progress Bar ──────────────────────────── */
    .progress-bar {
        display: flex;
        align-items: center;
        gap: 0;
        margin-bottom: 24px;
        overflow-x: auto;
        padding-bottom: 4px;
    }
    .progress-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        min-width: 90px;
    }
    .progress-dot {
        width: 32px; height: 32px;
        border-radius: 50%;
        border: 2px solid #e7e5e4;
        background: #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.8rem;
        font-weight: 800;
        color: #a8a29e;
        position: relative;
        z-index: 1;
        transition: all 0.2s;
    }
    .progress-dot.done   { background: #10b981; border-color: #10b981; color: #fff; }
    .progress-dot.active { background: var(--primary); border-color: var(--primary); color: #fff; box-shadow: 0 0 0 4px rgba(217,119,6,0.2); }
    .progress-label { font-size: 0.6rem; color: #78716c; margin-top: 4px; text-align: center; white-space: nowrap; }
    .progress-line {
        flex: 1;
        height: 2px;
        background: #e7e5e4;
        margin-top: -16px;
        min-width: 20px;
    }
    .progress-line.done { background: #10b981; }

    /* ── Action Box ────────────────────────────── */
    .action-box {
        background: #fff;
        border: 1px solid #e7e5e4;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 16px;
    }
    .action-header {
        padding: 14px 18px;
        background: var(--primary);
        color: #fff;
        font-weight: 700;
        font-size: 0.9rem;
    }
    .action-body { padding: 18px; }

    /* ── COD Alert ──────────────────────────────── */
    .cod-alert {
        background: #fee2e2;
        border: 1px solid #fca5a5;
        border-radius: 10px;
        padding: 14px;
        margin-bottom: 14px;
        font-size: 0.83rem;
        color: #991b1b;
        font-weight: 600;
    }

    /* ── Info Grid ──────────────────────────────── */
    .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 16px; }
    @media(max-width:600px){ .info-grid { grid-template-columns: 1fr; } }

    .info-row-d { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f5f5f4; font-size: 0.83rem; }
    .info-row-d:last-child { border-bottom: none; }
    .info-key-d { color: #78716c; }
    .info-val-d { font-weight: 600; text-align: right; }
</style>
@endsection

@section('content')
<div style="margin-bottom:16px;">
    <a href="{{ route('driver.dashboard') }}" style="font-size:0.83rem;color:#78716c;text-decoration:none;">← Dashboard</a>
</div>

{{-- Header Pesanan --}}
<div style="background:#fffbeb;border:1px solid #fde68a;border-radius:12px;padding:18px 20px;margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
    <div>
        <div style="font-size:1.1rem;font-weight:900;">{{ $pesanan->kode_pesanan }}</div>
        <div style="font-size:0.78rem;color:#78716c;">{{ $pesanan->created_at->format('d F Y, H:i') }} WIB</div>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <span style="padding:4px 12px;border-radius:20px;font-size:0.75rem;font-weight:700;background:#fef3c7;color:#92400e;">
            {{ $pesanan->status_label }}
        </span>
        @if($pesanan->metode_pembayaran === 'cod')
            <span style="padding:4px 12px;border-radius:20px;font-size:0.75rem;font-weight:700;background:#fee2e2;color:#991b1b;">💵 COD</span>
        @endif
        @if($pesanan->status_pembayaran === 'lunas')
            <span style="padding:4px 12px;border-radius:20px;font-size:0.75rem;font-weight:700;background:#d1fae5;color:#065f46;">✅ Lunas</span>
        @endif
    </div>
</div>

{{-- Progress Steps --}}
@php
    $steps = [
        'driver_menuju_resto' => ['label' => 'Menuju Resto', 'icon' => '🏃'],
        'tiba_di_resto'       => ['label' => 'Di Resto',     'icon' => '🏪'],
        'sedang_mengantar'    => ['label' => 'Mengantar',    'icon' => '🛵'],
        'selesai'             => ['label' => 'Selesai',      'icon' => '✅'],
    ];
    $stepOrder = array_keys($steps);
    $currentIdx = array_search($pesanan->status, $stepOrder);
@endphp

@if(in_array($pesanan->status, $stepOrder))
<div class="progress-bar">
    @foreach($steps as $sKey => $sVal)
        @php
            $idx = array_search($sKey, $stepOrder);
            $isDone   = $idx < $currentIdx;
            $isActive = $idx === $currentIdx;
        @endphp
        @if(!$loop->first)
            <div class="progress-line {{ ($isDone || $isActive) ? 'done' : '' }}"></div>
        @endif
        <div class="progress-step">
            <div class="progress-dot {{ $isDone ? 'done' : ($isActive ? 'active' : '') }}">
                {{ $isDone ? '✓' : $sVal['icon'] }}
            </div>
            <div class="progress-label">{{ $sVal['label'] }}</div>
        </div>
    @endforeach
</div>
@endif

{{-- COD Alert --}}
@if($pesanan->isCodBelumBayar() && $pesanan->status === 'sedang_mengantar')
    <div class="cod-alert">
        💵 Pesanan ini menggunakan COD! Tagih pembayaran sebesar <strong>{{ $pesanan->total_akhir_formatted }}</strong> kepada customer saat tiba.
    </div>
@endif

{{-- Info Grid --}}
<div class="info-grid">
    <div class="card">
        <div class="card-header"><div class="card-title">👤 Customer</div></div>
        <div class="card-body">
            <div class="info-row-d">
                <span class="info-key-d">Nama</span>
                <span class="info-val-d">{{ $pesanan->user->name }}</span>
            </div>
            <div class="info-row-d">
                <span class="info-key-d">Telepon</span>
                <span class="info-val-d">
                    @if($pesanan->user->phone)
                        <a href="tel:{{ $pesanan->user->phone }}" style="color:var(--primary);">{{ $pesanan->user->phone }}</a>
                    @else
                        -
                    @endif
                </span>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><div class="card-title">📍 Tujuan Pengiriman</div></div>
        <div class="card-body">
            <div style="font-size:0.83rem;margin-bottom:8px;">{{ $pesanan->alamat_pengiriman }}</div>
            @if($pesanan->jarak_meter)
                <div style="font-size:0.75rem;color:#78716c;">📏 {{ number_format($pesanan->jarak_meter/1000,2) }} km • Ongkir: {{ $pesanan->ongkir_formatted }}</div>
            @endif
        </div>
    </div>
</div>

{{-- Item Pesanan --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-header"><div class="card-title">🍟 Item Pesanan</div></div>
    <div class="card-body">
        @foreach($pesanan->detailPesanans as $detail)
            <div style="display:flex;align-items:center;gap:12px;padding:8px 0;border-bottom:1px solid #f5f5f4;">
                <div style="width:44px;height:44px;border-radius:8px;overflow:hidden;background:#f0f9ff;flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                    @if($detail->produk && $detail->produk->gambar)
                        <img src="{{ asset('storage/' . $detail->produk->gambar) }}" alt="" style="width:100%;height:100%;object-fit:cover;">
                    @else
                        🍟
                    @endif
                </div>
                <div style="flex:1;">
                    <div style="font-weight:700;font-size:0.875rem;">{{ $detail->nama_produk }}</div>
                    <div style="font-size:0.75rem;color:#78716c;">{{ $detail->harga_formatted }} × {{ $detail->qty }}</div>
                </div>
                <div style="font-weight:700;color:var(--primary);">{{ $detail->subtotal_formatted }}</div>
            </div>
        @endforeach
        <div style="display:flex;justify-content:space-between;padding-top:10px;border-top:2px solid #f5f5f4;margin-top:4px;">
            <span style="font-weight:700;">Subtotal Produk</span>
            <span style="font-weight:700;">{{ $pesanan->total_harga_formatted }}</span>
        </div>
        @if($pesanan->ongkir > 0)
            <div style="display:flex;justify-content:space-between;padding-top:6px;">
                <span style="color:#78716c;">Ongkir</span>
                <span style="font-weight:600;">{{ $pesanan->ongkir_formatted }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding-top:6px;font-size:1rem;">
                <span style="font-weight:800;">TOTAL</span>
                <span style="font-weight:900;color:var(--primary);">{{ $pesanan->total_akhir_formatted }}</span>
            </div>
        @endif
        @if($pesanan->catatan)
            <div style="margin-top:12px;background:#fffbeb;padding:10px;border-radius:8px;border:1px solid #fde68a;font-size:0.83rem;color:#92400e;">
                📝 Catatan: {{ $pesanan->catatan }}
            </div>
        @endif
    </div>
</div>

{{-- ═══════ ACTION BUTTONS ═══════ --}}

{{-- 1. Pool: belum diambil siapa pun --}}
@if($pesanan->status === 'mencari_driver' && is_null($pesanan->driver_id))
    <div class="action-box">
        <div class="action-header">📦 Pesanan Tersedia di Pool</div>
        <div class="action-body">
            <p style="font-size:0.875rem;color:#78716c;margin-bottom:14px;">Pesanan ini belum diambil driver manapun. Klik tombol di bawah untuk mengambilnya.</p>
            <form method="POST" action="{{ route('driver.pesanan.ambil', $pesanan->id) }}"
                  onsubmit="return confirm('Ambil pesanan ini? Anda bertanggung jawab mengantarkannya.')">
                @csrf
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:14px;font-size:1rem;">
                    🚗 Ambil Pesanan Ini
                </button>
            </form>
        </div>
    </div>

{{-- 2. Tombol bertahap saat driver aktif menangani --}}
@elseif($pesanan->driver_id === auth()->id() && in_array($pesanan->status, ['driver_menuju_resto','tiba_di_resto','sedang_mengantar']))
    <div class="action-box">
        <div class="action-header">⚙️ Update Status Pengiriman</div>
        <div class="action-body">
            @php
                $nextLabels = [
                    'driver_menuju_resto' => ['icon' => '🏪', 'text' => 'Saya Sudah Tiba di Toko'],
                    'tiba_di_resto'       => ['icon' => '🛵', 'text' => 'Mulai Antar ke Customer'],
                    'sedang_mengantar'    => ['icon' => '✅', 'text' => 'Pesanan Sudah Diterima Customer'],
                ];
                $next = $nextLabels[$pesanan->status] ?? null;
            @endphp

            @if($next)
                {{-- COD: tampilkan tombol khusus saat sedang_mengantar --}}
                @if($pesanan->status === 'sedang_mengantar' && $pesanan->isCodBelumBayar())
                    <div style="margin-bottom:12px;font-size:0.83rem;color:#78716c;">
                        Pilih cara menyelesaikan pesanan:
                    </div>
                    {{-- Tombol Terima Tunai COD --}}
                    <form method="POST" action="{{ route('driver.pesanan.terimaTunai', $pesanan->id) }}"
                          onsubmit="return confirm('Konfirmasi Anda sudah menerima uang tunai {{ $pesanan->total_akhir_formatted }} dari customer?')"
                          style="margin-bottom:10px;">
                        @csrf
                        <button type="submit" class="btn btn-success" style="width:100%;justify-content:center;padding:14px;font-size:1rem;">
                            💵 Terima Pembayaran Tunai & Selesai
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('driver.pesanan.updateStatus', $pesanan->id) }}"
                          onsubmit="return confirm('Update status pesanan?')"
                          style="margin-bottom:10px;">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:14px;font-size:1rem;">
                            {{ $next['icon'] }} {{ $next['text'] }}
                        </button>
                    </form>
                @endif
            @endif

            {{-- Tombol Batal — HANYA saat driver_menuju_resto --}}
            @if($pesanan->canDriverCancel())
                <form method="POST" action="{{ route('driver.pesanan.batal', $pesanan->id) }}"
                      onsubmit="return confirm('Batalkan pesanan ini? Pesanan akan dikembalikan ke pool driver lain.')">
                    @csrf
                    <button type="submit" class="btn btn-danger" style="width:100%;justify-content:center;padding:10px;font-size:0.875rem;">
                        ❌ Batalkan (Kembalikan ke Pool)
                    </button>
                </form>
            @endif
        </div>
    </div>

{{-- 3. Selesai --}}
@elseif($pesanan->status === 'selesai')
    <div style="text-align:center;padding:20px;background:#d1fae5;border-radius:12px;color:#065f46;font-weight:700;font-size:1rem;">
        🎉 Pesanan ini sudah Selesai!
        @if($pesanan->status_pembayaran === 'lunas')
            <div style="font-size:0.8rem;margin-top:4px;">✅ Pembayaran Lunas</div>
        @endif
    </div>
@endif

@endsection
