@extends('layouts.admin')

@section('title', 'Data Pelanggan')
@section('page-title', 'Data Pelanggan & Driver')
@section('page-subtitle', 'Kelola user, hapus akun, dan assign role driver')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">👥 Daftar User ({{ $customers->total() }})</div>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No. Telepon</th>
                    <th>Role</th>
                    <th>Total Pesanan</th>
                    <th>Bergabung</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                    <tr>
                        <td>{{ $loop->index + $customers->firstItem() }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div style="width:32px;height:32px;background:{{ $customer->isDriver() ? '#f59e0b' : 'var(--primary)' }};border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:0.78rem;flex-shrink:0;">
                                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                                </div>
                                <span style="font-weight:600;">{{ $customer->name }}</span>
                            </div>
                        </td>
                        <td style="color:var(--text-muted);">{{ $customer->email }}</td>
                        <td>{{ $customer->phone ?? '-' }}</td>
                        <td>
                            @if($customer->isDriver())
                                <span class="badge" style="background:#fef3c7;color:#92400e;border:1px solid #fde68a;">🚗 Driver</span>
                            @else
                                <span class="badge" style="background:#dbeafe;color:#1e40af;">👤 Customer</span>
                            @endif
                        </td>
                        <td>
                            <span style="font-weight:700;color:var(--primary);">{{ $customer->pesanans_count }}</span>
                            <span style="font-size:0.75rem;color:var(--text-muted);"> pesanan</span>
                        </td>
                        <td style="color:var(--text-muted);font-size:0.8rem;">{{ $customer->created_at->format('d M Y') }}</td>
                        <td>
                            <div style="display:flex;gap:6px;flex-wrap:wrap;">
                                <a href="{{ route('admin.customer.show', $customer) }}" class="btn btn-sm btn-secondary">👁️ Detail</a>

                                {{-- Toggle Driver Role --}}
                                <form method="POST" action="{{ route('admin.customer.assignDriver', $customer) }}"
                                      onsubmit="return confirm('{{ $customer->isDriver() ? 'Ubah role ke Customer?' : 'Jadikan Driver?' }}')">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-sm" style="background:{{ $customer->isDriver() ? '#fef3c7' : '#f0fdf4' }};color:{{ $customer->isDriver() ? '#92400e' : '#15803d' }};border:1px solid {{ $customer->isDriver() ? '#fde68a' : '#bbf7d0' }};">
                                        {{ $customer->isDriver() ? '👤 Ke Customer' : '🚗 Jadi Driver' }}
                                    </button>
                                </form>

                                {{-- Hapus User --}}
                                @if($customer->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.customer.destroy', $customer) }}"
                                          onsubmit="return confirm('Yakin hapus akun \"{{ $customer->name }}\"? Semua data pesanan juga akan terhapus!')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">🗑️ Hapus</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center;color:var(--text-muted);padding:40px;">
                            Belum ada user terdaftar
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">
        @if($customers->onFirstPage()) <span>«</span>
        @else <a href="{{ $customers->previousPageUrl() }}">«</a> @endif
        @for($i = 1; $i <= $customers->lastPage(); $i++)
            @if($i == $customers->currentPage()) <span class="active-page">{{ $i }}</span>
            @else <a href="{{ $customers->url($i) }}">{{ $i }}</a> @endif
        @endfor
        @if($customers->hasMorePages()) <a href="{{ $customers->nextPageUrl() }}">»</a>
        @else <span>»</span> @endif
    </div>
</div>
@endsection
