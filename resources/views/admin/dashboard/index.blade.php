@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Selamat datang di Panel Admin Cireng Pasrah')

@section('styles')
<style>
    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 16px;
        margin-bottom: 28px;
    }

    .stat-card {
        background: var(--card);
        border-radius: var(--radius);
        padding: 20px;
        border: 1px solid var(--border);
        box-shadow: var(--shadow);
        display: flex;
        align-items: flex-start;
        gap: 14px;
        position: relative;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-lg); }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0; right: 0;
        width: 80px; height: 80px;
        border-radius: 50%;
        opacity: 0.06;
    }

    .stat-card.orange::before { background: #f97316; }
    .stat-card.blue::before   { background: #3b82f6; }
    .stat-card.green::before  { background: #10b981; }
    .stat-card.purple::before { background: #8b5cf6; }
    .stat-card.red::before    { background: #ef4444; }

    .stat-icon {
        width: 44px; height: 44px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .stat-icon.orange { background: #fff7ed; }
    .stat-icon.blue   { background: #eff6ff; }
    .stat-icon.green  { background: #f0fdf4; }
    .stat-icon.purple { background: #faf5ff; }
    .stat-icon.red    { background: #fef2f2; }

    .stat-info { flex: 1; }
    .stat-label { font-size: 0.72rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; }
    .stat-value { font-size: 1.5rem; font-weight: 800; color: var(--text); margin: 4px 0; line-height: 1; }
    .stat-value.orange { color: var(--primary); }
    .stat-change { font-size: 0.72rem; color: var(--text-muted); }

    /* Charts Row */
    .charts-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 24px;
    }
    @media(max-width:900px) { .charts-row { grid-template-columns: 1fr; } }

    /* Chart */
    .chart-container { padding: 20px; }

    .bar-chart {
        display: flex;
        align-items: flex-end;
        gap: 8px;
        height: 120px;
        margin-top: 12px;
    }

    .bar-group {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
        height: 100%;
    }

    .bar-wrap {
        flex: 1;
        display: flex;
        align-items: flex-end;
        width: 100%;
    }

    .bar {
        width: 100%;
        background: linear-gradient(to top, #ea580c, #f97316);
        border-radius: 4px 4px 0 0;
        min-height: 4px;
        transition: all 0.5s ease;
        position: relative;
    }

    .bar:hover::after {
        content: attr(data-value);
        position: absolute;
        top: -28px;
        left: 50%;
        transform: translateX(-50%);
        background: #1e293b;
        color: #fff;
        padding: 3px 6px;
        border-radius: 4px;
        font-size: 0.65rem;
        font-weight: 600;
        white-space: nowrap;
        z-index: 10;
    }

    .bar-label { font-size: 0.6rem; color: var(--text-muted); font-weight: 600; white-space: nowrap; }
    .bar-val   { font-size: 0.65rem; color: var(--text-muted); }

    /* Transactions Table */
    .transactions-table td .kode { font-family: monospace; font-size: 0.8rem; color: var(--primary); font-weight: 600; }
    .transactions-table td .customer-name { font-weight: 600; }
    .transactions-table td .total { font-weight: 700; }

    /* Low stock warning */
    .stok-warning {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.78rem;
        font-weight: 600;
    }
    .stok-ok  { color: var(--success); }
    .stok-low { color: var(--warning); }
    .stok-critical { color: var(--danger); }

    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    .section-header h2 { font-size: 1rem; font-weight: 700; }
</style>
@endsection

@section('content')

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card orange">
        <div class="stat-icon orange">💰</div>
        <div class="stat-info">
            <div class="stat-label">Total Pendapatan</div>
            <div class="stat-value orange">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
            <div class="stat-change">Dari pesanan selesai & lunas</div>
        </div>
    </div>

    <div class="stat-card blue">
        <div class="stat-icon blue">📦</div>
        <div class="stat-info">
            <div class="stat-label">Pesanan Hari Ini</div>
            <div class="stat-value">{{ $pesananHariIni }}</div>
            <div class="stat-change">{{ now()->format('d M Y') }}</div>
        </div>
    </div>

    <div class="stat-card green">
        <div class="stat-icon green">🥣</div>
        <div class="stat-info">
            <div class="stat-label">Stok Cireng Mentah</div>
            <div class="stat-value">{{ $stokCireng }}</div>
            <div class="stat-change">
                @if($stokCireng <= 0)
                    <span class="stok-warning stok-critical">⛔ Stok habis!</span>
                @elseif($stokCireng <= 20)
                    <span class="stok-warning stok-low">⚠️ Stok menipis</span>
                @else
                    <span class="stok-warning stok-ok">✅ Stok aman</span>
                @endif
            </div>
        </div>
    </div>

    <div class="stat-card purple">
        <div class="stat-icon purple">👥</div>
        <div class="stat-info">
            <div class="stat-label">Total Customer</div>
            <div class="stat-value">{{ $totalCustomer }}</div>
            <div class="stat-change">Terdaftar</div>
        </div>
    </div>

    <div class="stat-card red">
        <div class="stat-icon red">⏳</div>
        <div class="stat-info">
            <div class="stat-label">Pesanan Pending</div>
            <div class="stat-value">{{ $pesananPending }}</div>
            <div class="stat-change">Menunggu konfirmasi</div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="charts-row">
    <!-- Pesanan Chart -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">📈 Pesanan 7 Hari Terakhir</div>
        </div>
        <div class="chart-container">
            @php
                $maxVal = max(array_column($chartData, 'value')) ?: 1;
            @endphp
            <div class="bar-chart">
                @foreach($chartData as $point)
                    <div class="bar-group">
                        <div class="bar-wrap">
                            <div class="bar"
                                 data-value="{{ $point['value'] }}"
                                 style="height: {{ ($point['value'] / $maxVal) * 100 }}%;">
                            </div>
                        </div>
                        <div class="bar-label">{{ $point['label'] }}</div>
                        <div class="bar-val">{{ $point['value'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Pendapatan Chart -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">💵 Pendapatan 7 Hari Terakhir</div>
        </div>
        <div class="chart-container">
            @php
                $maxPend = max(array_column($pendapatanChart, 'value')) ?: 1;
            @endphp
            <div class="bar-chart">
                @foreach($pendapatanChart as $point)
                    <div class="bar-group">
                        <div class="bar-wrap">
                            <div class="bar"
                                 style="height: {{ ($point['value'] / $maxPend) * 100 }}%; background: linear-gradient(to top, #059669, #10b981);"
                                 data-value="Rp {{ number_format($point['value'], 0, ',', '.') }}">
                            </div>
                        </div>
                        <div class="bar-label">{{ $point['label'] }}</div>
                        <div class="bar-val" style="font-size:0.55rem;">{{ $point['value'] > 0 ? number_format($point['value']/1000, 0) . 'K' : '0' }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Recent Transactions -->
<div class="card">
    <div class="card-header">
        <div class="card-title">📋 Transaksi Terbaru</div>
        <a href="{{ route('admin.pesanan.index') }}" class="btn btn-sm btn-secondary">Lihat Semua →</a>
    </div>
    <div class="table-wrapper">
        <table class="transactions-table">
            <thead>
                <tr>
                    <th>Kode Pesanan</th>
                    <th>Customer</th>
                    <th>Pengiriman</th>
                    <th>Total</th>
                    <th>Status Pesanan</th>
                    <th>Pembayaran</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksiTerbaru as $trx)
                    <tr>
                        <td>
                            <a href="{{ route('admin.pesanan.show', $trx->id) }}" class="kode">
                                {{ $trx->kode_pesanan }}
                            </a>
                        </td>
                        <td class="customer-name">{{ $trx->user->name }}</td>
                        <td>{{ $trx->opsi_pengiriman === 'take_away' ? '🏃 Take Away' : '🛵 Delivery' }}</td>
                        <td class="total">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge badge-{{ $trx->status }}">{{ $trx->status_label }}</span>
                        </td>
                        <td>
                            <span class="badge {{ $trx->status_pembayaran === 'lunas' ? 'badge-lunas' : 'badge-belum' }}">
                                {{ $trx->status_pembayaran === 'lunas' ? 'Lunas' : 'Belum' }}
                            </span>
                        </td>
                        <td style="color:var(--text-muted);font-size:0.78rem;">{{ $trx->created_at->diffForHumans() }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;color:var(--text-muted);padding:32px;">
                            Belum ada transaksi
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
