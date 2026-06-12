@extends('layouts.admin')

@section('title', 'Data Pelanggan')
@section('page-title', 'Data Pelanggan')
@section('page-subtitle', 'Daftar customer terdaftar')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">👥 Daftar Customer ({{ $customers->total() }})</div>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No. Telepon</th>
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
                                <div style="width:32px;height:32px;background:linear-gradient(135deg,var(--primary),var(--primary-dark));border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:0.78rem;flex-shrink:0;">
                                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                                </div>
                                <span style="font-weight:600;">{{ $customer->name }}</span>
                            </div>
                        </td>
                        <td style="color:var(--text-muted);">{{ $customer->email }}</td>
                        <td>{{ $customer->phone ?? '-' }}</td>
                        <td>
                            <span style="font-weight:700;color:var(--primary);">{{ $customer->pesanans_count }}</span>
                            <span style="font-size:0.75rem;color:var(--text-muted);"> pesanan</span>
                        </td>
                        <td style="color:var(--text-muted);font-size:0.8rem;">{{ $customer->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.customer.show', $customer) }}" class="btn btn-sm btn-secondary">👁️ Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;color:var(--text-muted);padding:40px;">
                            Belum ada customer terdaftar
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
