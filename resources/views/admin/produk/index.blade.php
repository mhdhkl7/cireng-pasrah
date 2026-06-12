@extends('layouts.admin')

@section('title', 'Manajemen Produk')
@section('page-title', 'Manajemen Produk')
@section('page-subtitle', 'Kelola menu cireng Anda')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">🍟 Daftar Produk</div>
        <a href="{{ route('admin.produk.create') }}" class="btn btn-primary">+ Tambah Produk</a>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Gambar</th>
                    <th>Nama Produk</th>
                    <th>Deskripsi</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($produks as $produk)
                    <tr>
                        <td>{{ $loop->index + $produks->firstItem() }}</td>
                        <td>
                            @if($produk->gambar)
                                <img src="{{ $produk->gambar_url }}" alt="{{ $produk->nama }}"
                                     style="width:56px;height:56px;object-fit:cover;border-radius:8px;border:1px solid var(--border);">
                            @else
                                <div style="width:56px;height:56px;background:#f0f9ff;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;border:1px solid var(--border);">
                                    🍟
                                </div>
                            @endif
                        </td>
                        <td style="font-weight:600;">{{ $produk->nama }}</td>
                        <td style="color:var(--text-muted);max-width:200px;">
                            {{ Str::limit($produk->deskripsi, 60) ?? '-' }}
                        </td>
                        <td style="font-weight:700;color:var(--primary);">{{ $produk->harga_formatted }}</td>
                        <td>
                            @if($produk->is_active)
                                <span class="badge" style="background:#d1fae5;color:#065f46;">✅ Aktif</span>
                            @else
                                <span class="badge" style="background:#fee2e2;color:#991b1b;">❌ Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex;gap:6px;">
                                <a href="{{ route('admin.produk.edit', $produk) }}"
                                   class="btn btn-sm btn-secondary">✏️ Edit</a>
                                <form method="POST" action="{{ route('admin.produk.destroy', $produk) }}"
                                      onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;color:var(--text-muted);padding:40px;">
                            Belum ada produk. <a href="{{ route('admin.produk.create') }}" style="color:var(--primary);">Tambahkan sekarang</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">
        @if($produks->onFirstPage())
            <span>«</span>
        @else
            <a href="{{ $produks->previousPageUrl() }}">«</a>
        @endif
        @for($i = 1; $i <= $produks->lastPage(); $i++)
            @if($i == $produks->currentPage())
                <span class="active-page">{{ $i }}</span>
            @else
                <a href="{{ $produks->url($i) }}">{{ $i }}</a>
            @endif
        @endfor
        @if($produks->hasMorePages())
            <a href="{{ $produks->nextPageUrl() }}">»</a>
        @else
            <span>»</span>
        @endif
    </div>
</div>
@endsection
