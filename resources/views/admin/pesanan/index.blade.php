@extends('layouts.admin')

@section('title', 'Manajemen Pesanan')
@section('page-title', 'Manajemen Pesanan')
@section('page-subtitle', 'Kelola semua pesanan masuk')

@section('styles')
<style>
    .filter-bar {
        background: var(--card);
        border-radius: var(--radius);
        border: 1px solid var(--border);
        padding: 16px 20px;
        margin-bottom: 20px;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: center;
    }
    .filter-bar select, .filter-bar input {
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
</style>
@endsection

@section('content')
<!-- Filter Bar -->
<form method="GET" action="{{ route('admin.pesanan.index') }}" class="filter-bar">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="🔍 Cari kode/nama customer...">
    <select name="status">
        <option value="">Semua Status</option>
        <option value="pending"    {{ request('status') === 'pending'    ? 'selected' : '' }}>Pending</option>
        <option value="diproses"   {{ request('status') === 'diproses'   ? 'selected' : '' }}>Diproses</option>
        <option value="siap"       {{ request('status') === 'siap'       ? 'selected' : '' }}>Siap</option>
        <option value="selesai"    {{ request('status') === 'selesai'    ? 'selected' : '' }}>Selesai</option>
        <option value="dibatalkan" {{ request('status') === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
    </select>
    <select name="status_pembayaran">
        <option value="">Semua Pembayaran</option>
        <option value="belum_dibayar" {{ request('status_pembayaran') === 'belum_dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
        <option value="lunas"         {{ request('status_pembayaran') === 'lunas'         ? 'selected' : '' }}>Lunas</option>
    </select>
    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
    <a href="{{ route('admin.pesanan.index') }}" class="btn btn-secondary btn-sm">Reset</a>
    <span style="margin-left:auto;font-size:0.8rem;color:var(--text-muted);">
        Total: <strong>{{ $pesanans->total() }}</strong> pesanan
    </span>
</form>

<div class="card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Kode Pesanan</th>
                    <th>Customer</th>
                    <th>Pengiriman</th>
                    <th>Pembayaran</th>
                    <th>Total</th>
                    <th>Status Pesanan</th>
                    <th>Status Bayar</th>
                    <th>Waktu</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesanans as $pesanan)
                    <tr>
                        <td>
                            <a href="{{ route('admin.pesanan.show', $pesanan->id) }}"
                               style="color:var(--primary);font-weight:600;font-family:monospace;text-decoration:none;">
                                {{ $pesanan->kode_pesanan }}
                            </a>
                        </td>
                        <td style="font-weight:600;">{{ $pesanan->user->name }}</td>
                        <td>{{ $pesanan->opsi_pengiriman === 'take_away' ? '🏃 Take Away' : '🛵 Delivery' }}</td>
                        <td>
                            {{ $pesanan->metode_pembayaran === 'cash' ? '💵 Cash' : '📱 Transfer' }}
                            @if($pesanan->bukti_pembayaran)
                                <span style="font-size:0.65rem;background:#dbeafe;color:#1e40af;padding:2px 6px;border-radius:4px;margin-left:4px;">Ada Bukti</span>
                            @endif
                        </td>
                        <td style="font-weight:700;">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge badge-{{ $pesanan->status }}">{{ $pesanan->status_label }}</span>
                        </td>
                        <td>
                            <span class="badge {{ $pesanan->status_pembayaran === 'lunas' ? 'badge-lunas' : 'badge-belum' }}">
                                {{ $pesanan->status_pembayaran === 'lunas' ? 'Lunas' : 'Belum' }}
                            </span>
                        </td>
                        <td style="font-size:0.75rem;color:var(--text-muted);">{{ $pesanan->created_at->format('d/m/y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.pesanan.show', $pesanan->id) }}" class="btn btn-sm btn-secondary">
                                👁️ Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align:center;color:var(--text-muted);padding:40px;">
                            Tidak ada pesanan ditemukan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">
        @if($pesanans->onFirstPage()) <span>«</span>
        @else <a href="{{ $pesanans->previousPageUrl() }}">«</a> @endif
        @for($i = 1; $i <= $pesanans->lastPage(); $i++)
            @if($i == $pesanans->currentPage()) <span class="active-page">{{ $i }}</span>
            @else <a href="{{ $pesanans->url($i) }}">{{ $i }}</a> @endif
        @endfor
        @if($pesanans->hasMorePages()) <a href="{{ $pesanans->nextPageUrl() }}">»</a>
        @else <span>»</span> @endif
    </div>
</div>
@endsection
