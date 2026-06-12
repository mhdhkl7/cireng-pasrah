@extends('layouts.customer')

@section('title', 'Keranjang Belanja')
@section('styles')
<style>
    .page-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1c1917;
        margin-bottom: 6px;
    }
    .page-title span { color: #0ea5e9; }
    .page-subtitle { color: #78716c; font-size: 0.875rem; margin-bottom: 28px; }

    .keranjang-layout {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 24px;
        align-items: start;
    }

    @media(max-width: 768px) {
        .keranjang-layout { grid-template-columns: 1fr; }
    }

    /* Cart Items */
    .cart-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e7e5e4;
        overflow: hidden;
    }

    .cart-header {
        padding: 18px 20px;
        border-bottom: 1px solid #f5f5f4;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .cart-header h3 { font-size: 0.95rem; font-weight: 700; }

    .btn-kosongkan {
        font-size: 0.78rem;
        color: #dc2626;
        background: none;
        border: none;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        font-weight: 600;
        display: flex; align-items: center; gap: 4px;
        padding: 6px 10px;
        border-radius: 6px;
        transition: background 0.2s;
    }
    .btn-kosongkan:hover { background: #fee2e2; }

    .cart-item {
        display: grid;
        grid-template-columns: 72px 1fr auto auto;
        gap: 14px;
        align-items: center;
        padding: 16px 20px;
        border-bottom: 1px solid #f5f5f4;
    }
    .cart-item:last-child { border-bottom: none; }

    .item-img {
        width: 72px; height: 72px;
        border-radius: 10px;
        overflow: hidden;
        background: linear-gradient(135deg, #f0f9ff, #ccfbf1);
        flex-shrink: 0;
    }
    .item-img img { width: 100%; height: 100%; object-fit: cover; }
    .item-img-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 1.75rem; }

    .item-info h4 { font-size: 0.9rem; font-weight: 700; color: #1c1917; margin-bottom: 4px; }
    .item-price { font-size: 0.8rem; color: #78716c; }
    .item-subtotal { font-size: 0.9rem; font-weight: 700; color: #0ea5e9; }

    .qty-form {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .qty-form input[type="number"] {
        width: 56px;
        padding: 6px 8px;
        border: 1.5px solid #e7e5e4;
        border-radius: 8px;
        font-size: 0.83rem;
        text-align: center;
        font-family: 'Inter', sans-serif;
    }
    .qty-form input:focus { outline: none; border-color: #0ea5e9; }

    .btn-update {
        padding: 5px 10px;
        background: #f5f5f4;
        border: 1px solid #e7e5e4;
        border-radius: 6px;
        font-size: 0.72rem;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        font-weight: 600;
        color: #78716c;
        transition: background 0.2s;
    }
    .btn-update:hover { background: #e7e5e4; }

    .btn-hapus {
        padding: 6px 8px;
        background: none;
        border: none;
        color: #dc2626;
        cursor: pointer;
        border-radius: 6px;
        font-size: 1rem;
        transition: background 0.2s;
    }
    .btn-hapus:hover { background: #fee2e2; }

    /* Summary Card */
    .summary-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e7e5e4;
        overflow: hidden;
        position: sticky;
        top: 80px;
    }

    .summary-header {
        background: linear-gradient(135deg, #0ea5e9, #10b981);
        padding: 18px 20px;
        color: #fff;
    }
    .summary-header h3 { font-size: 1rem; font-weight: 700; }

    .summary-body { padding: 20px; }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #f5f5f4;
        font-size: 0.875rem;
    }
    .summary-row:last-of-type { border-bottom: none; }
    .summary-row .label { color: #78716c; }
    .summary-row .value { font-weight: 600; color: #1c1917; }
    .summary-row.total .label { font-weight: 700; color: #1c1917; font-size: 1rem; }
    .summary-row.total .value { font-weight: 800; color: #0ea5e9; font-size: 1.1rem; }

    .btn-checkout {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #0ea5e9, #10b981);
        color: #fff;
        border: none;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        margin-top: 16px;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 4px 15px rgba(14,165,233,0.3);
        transition: all 0.2s;
    }
    .btn-checkout:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(14,165,233,0.4); }

    .empty-cart {
        text-align: center;
        padding: 80px 24px;
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e7e5e4;
    }
    .empty-cart .emoji { font-size: 4rem; margin-bottom: 16px; }
    .empty-cart h3 { font-size: 1.2rem; font-weight: 700; margin-bottom: 8px; }
    .empty-cart p { color: #78716c; margin-bottom: 20px; }

    .btn-belanja {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: linear-gradient(135deg, #0ea5e9, #10b981);
        color: #fff;
        border-radius: 12px;
        font-weight: 700;
        text-decoration: none;
        font-size: 0.9rem;
        box-shadow: 0 4px 12px rgba(14,165,233,0.3);
        transition: all 0.2s;
    }
    .btn-belanja:hover { transform: translateY(-2px); }
</style>
@endsection

@section('content')
<div class="page-title">🛒 Keranjang <span>Belanja</span></div>
<div class="page-subtitle">Review pesanan Anda sebelum melanjutkan ke checkout</div>

@if(empty($keranjang))
    <div class="empty-cart">
        <div class="emoji">🛒</div>
        <h3>Keranjang Masih Kosong</h3>
        <p>Belum ada produk yang ditambahkan. Yuk mulai belanja!</p>
        <a href="{{ route('katalog.index') }}" class="btn-belanja">🍟 Lihat Katalog</a>
    </div>
@else
    <div class="keranjang-layout">
        <!-- Cart Items -->
        <div class="cart-card">
            <div class="cart-header">
                <h3>🍟 Item Pesanan ({{ count($keranjang) }})</h3>
                <form method="POST" action="{{ route('keranjang.kosongkan') }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-kosongkan"
                            onclick="return confirm('Kosongkan semua keranjang?')">
                        🗑️ Kosongkan Semua
                    </button>
                </form>
            </div>

            @foreach($keranjang as $key => $item)
                <div class="cart-item">
                    <div class="item-img">
                        @if($item['gambar'])
                            <img src="{{ asset('storage/' . $item['gambar']) }}" alt="{{ $item['nama'] }}">
                        @else
                            <div class="item-img-placeholder">🍟</div>
                        @endif
                    </div>

                    <div class="item-info">
                        <h4>{{ $item['nama'] }}</h4>
                        <div class="item-price">Rp {{ number_format($item['harga'], 0, ',', '.') }} / pcs</div>
                    </div>

                    <form method="POST" action="{{ route('keranjang.update') }}" class="qty-form">
                        @csrf @method('PATCH')
                        <input type="hidden" name="produk_id" value="{{ $item['produk_id'] }}">
                        <input type="number" name="qty" value="{{ $item['qty'] }}" min="1" max="100">
                        <button type="submit" class="btn-update">✓</button>
                    </form>

                    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:8px;">
                        <div class="item-subtotal">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</div>
                        <form method="POST" action="{{ route('keranjang.hapus') }}">
                            @csrf @method('DELETE')
                            <input type="hidden" name="produk_id" value="{{ $item['produk_id'] }}">
                            <button type="submit" class="btn-hapus" title="Hapus">🗑️</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Order Summary -->
        <div class="summary-card">
            <div class="summary-header">
                <h3>📊 Ringkasan Pesanan</h3>
            </div>
            <div class="summary-body">
                @foreach($keranjang as $item)
                    <div class="summary-row">
                        <span class="label">{{ $item['nama'] }} x{{ $item['qty'] }}</span>
                        <span class="value">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                    </div>
                @endforeach

                <div class="summary-row total">
                    <span class="label">💰 Total</span>
                    <span class="value">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>

                <a href="{{ route('checkout.index') }}" class="btn-checkout">
                    ✅ Lanjut ke Checkout
                </a>
                <a href="{{ route('katalog.index') }}" style="display:block;text-align:center;margin-top:10px;font-size:0.8rem;color:#78716c;text-decoration:none;">
                    ← Lanjut Belanja
                </a>
            </div>
        </div>
    </div>
@endif
@endsection
