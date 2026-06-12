@extends('layouts.customer')

@section('title', 'Katalog Cireng')
@section('styles')
<style>
    .page-header {
        text-align: center;
        margin-bottom: 36px;
        padding: 40px 24px;
        background: linear-gradient(135deg, #e0f2fe, #d1fae5);
        border-radius: 20px;
        border: 1px solid rgba(14,165,233,0.15);
        position: relative;
        overflow: hidden;
    }
    .page-header::before {
        content: '';
        position: absolute;
        top: -50px; right: -50px;
        width: 200px; height: 200px;
        background: radial-gradient(circle, rgba(14,165,233,0.15) 0%, transparent 70%);
        border-radius: 50%;
    }
    .page-header h2 {
        font-size: 2rem;
        font-weight: 900;
        color: #0f172a;
        position: relative;
    }
    .page-header h2 span { 
        background: linear-gradient(135deg, #0ea5e9, #10b981);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .page-header p {
        color: #64748b;
        margin-top: 8px;
        font-size: 0.95rem;
        position: relative;
    }

    .produk-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 24px;
    }

    .produk-card {
        background: #fff;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(14,165,233,0.06);
        border: 1px solid #f8fafc;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        display: flex;
        flex-direction: column;
    }
    .produk-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 40px rgba(14,165,233,0.12);
    }

    .produk-img-wrap {
        aspect-ratio: 4/3;
        overflow: hidden;
        background: linear-gradient(135deg, #e0f2fe, #d1fae5);
        position: relative;
    }

    .produk-img-wrap img {
        width: 100%; height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    .produk-card:hover .produk-img-wrap img { transform: scale(1.05); }

    .produk-img-placeholder {
        width: 100%; height: 100%;
        display: flex; align-items: center; justify-content: center;
        font-size: 4rem;
    }

    .produk-body {
        padding: 18px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .produk-nama {
        font-size: 1.05rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 6px;
    }

    .produk-desc {
        font-size: 0.83rem;
        color: #64748b;
        line-height: 1.5;
        flex: 1;
        margin-bottom: 14px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .produk-harga {
        font-size: 1.25rem;
        font-weight: 800;
        color: #0ea5e9;
        margin-bottom: 14px;
    }

    .produk-actions form {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .qty-input {
        width: 64px;
        padding: 8px 10px;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.875rem;
        text-align: center;
        font-family: 'Inter', sans-serif;
    }
    .qty-input:focus {
        outline: none;
        border-color: #0ea5e9;
    }

    .btn-add-cart {
        flex: 1;
        padding: 9px 14px;
        background: linear-gradient(135deg, #0ea5e9, #10b981);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 700;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }
    .btn-add-cart:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(14,165,233,0.35);
    }

    .empty-state {
        text-align: center;
        padding: 80px 24px;
    }
    .empty-state .emoji { font-size: 4rem; margin-bottom: 16px; }
    .empty-state h3 { font-size: 1.25rem; font-weight: 700; color: #0f172a; margin-bottom: 8px; }
    .empty-state p { color: #64748b; }
</style>
@endsection

@section('content')
<div class="page-header">
    <h2>🍟 Katalog <span>Cireng</span> Kami</h2>
    <p>Pilih varian cireng favorit kamu, segar dan renyah setiap hari!</p>
</div>

@if($produks->isEmpty())
    <div class="empty-state">
        <div class="emoji">😔</div>
        <h3>Belum Ada Produk</h3>
        <p>Produk sedang dalam persiapan. Silakan cek kembali nanti!</p>
    </div>
@else
    <div class="produk-grid">
        @foreach($produks as $produk)
            <div class="produk-card">
                <div class="produk-img-wrap">
                    @if($produk->gambar)
                        <img src="{{ $produk->gambar_url }}" alt="{{ $produk->nama }}">
                    @else
                        <div class="produk-img-placeholder">🍟</div>
                    @endif
                </div>
                <div class="produk-body">
                    <div class="produk-nama">{{ $produk->nama }}</div>
                    @if($produk->deskripsi)
                        <div class="produk-desc">{{ $produk->deskripsi }}</div>
                    @endif
                    <div class="produk-harga">{{ $produk->harga_formatted }}</div>
                    <div class="produk-actions">
                        <form method="POST" action="{{ route('keranjang.tambah') }}">
                            @csrf
                            <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                            <input type="number" name="qty" value="1" min="1" max="100"
                                   class="qty-input" title="Jumlah">
                            <button type="submit" class="btn-add-cart">
                                🛒 Tambah
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
