@extends('layouts.admin')

@section('title', 'Detail Customer: ' . $user->name)
@section('page-title', 'Detail Customer')
@section('page-subtitle', 'Riwayat pesanan customer')

@section('content')
<div style="margin-bottom:16px;">
    <a href="{{ route('admin.customer.index') }}" style="font-size:0.83rem;color:var(--text-muted);text-decoration:none;">
        ← Kembali ke Daftar Customer
    </a>
</div>

<div style="display:grid;grid-template-columns:300px 1fr;gap:20px;align-items:start;">
    <!-- Profile Card -->
    <div class="card">
        <div style="background:linear-gradient(135deg,var(--primary),var(--primary-dark));padding:28px;text-align:center;">
            <div style="width:64px;height:64px;background:rgba(255,255,255,0.2);border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:1.75rem;font-weight:800;color:#fff;margin-bottom:10px;">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div style="color:#fff;font-weight:700;font-size:1rem;">{{ $user->name }}</div>
            <div style="color:rgba(255,255,255,0.7);font-size:0.78rem;">Customer</div>
        </div>
        <div class="card-body">
            <div style="font-size:0.83rem;">
                <div style="padding:8px 0;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;">
                    <span style="color:var(--text-muted);">Email</span>
                    <span style="font-weight:600;font-size:0.78rem;">{{ $user->email }}</span>
                </div>
                <div style="padding:8px 0;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;">
                    <span style="color:var(--text-muted);">Telepon</span>
                    <span style="font-weight:600;">{{ $user->phone ?? '-' }}</span>
                </div>
                <div style="padding:8px 0;border-bottom:1px solid var(--border);">
                    <span style="color:var(--text-muted);">Alamat</span>
                    <div style="font-weight:500;margin-top:4px;font-size:0.78rem;">{{ $user->address ?? '-' }}</div>
                </div>
                <div style="padding:8px 0;display:flex;justify-content:space-between;">
                    <span style="color:var(--text-muted);">Bergabung</span>
                    <span style="font-weight:600;font-size:0.78rem;">{{ $user->created_at->format('d M Y') }}</span>
                </div>
            </div>
            <div style="margin-top:12px;padding:12px;background:#fff7ed;border-radius:8px;text-align:center;border:1px solid #fed7aa;">
                <div style="font-size:1.5rem;font-weight:800;color:var(--primary);">{{ $pesanans->total() }}</div>
                <div style="font-size:0.75rem;color:var(--text-muted);font-weight:600;">Total Pesanan</div>
            </div>
        </div>
    </div>

    <!-- Riwayat Pesanan -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">📋 Riwayat Pesanan</div>
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Kode Pesanan</th>
                        <th>Total</th>
                        <th>Pengiriman</th>
                        <th>Status</th>
                        <th>Pembayaran</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pesanans as $pesanan)
                        <tr>
                            <td style="font-family:monospace;font-weight:600;color:var(--primary);">
                                {{ $pesanan->kode_pesanan }}
                            </td>
                            <td style="font-weight:700;">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                            <td>{{ $pesanan->opsi_pengiriman === 'take_away' ? '🏃 Take Away' : '🛵 Delivery' }}</td>
                            <td><span class="badge badge-{{ $pesanan->status }}">{{ $pesanan->status_label }}</span></td>
                            <td>
                                <span class="badge {{ $pesanan->status_pembayaran === 'lunas' ? 'badge-lunas' : 'badge-belum' }}">
                                    {{ $pesanan->status_pembayaran === 'lunas' ? 'Lunas' : 'Belum' }}
                                </span>
                            </td>
                            <td style="font-size:0.78rem;color:var(--text-muted);">{{ $pesanan->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('admin.pesanan.show', $pesanan->id) }}" class="btn btn-sm btn-secondary">
                                    👁️ Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center;color:var(--text-muted);padding:40px;">
                                Customer belum memiliki pesanan
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
</div>
@endsection
