@extends('layouts.customer')

@section('title', 'Checkout')
@section('styles')
<style>
    .checkout-layout {
        display: grid;
        grid-template-columns: 1fr 360px;
        gap: 24px;
        align-items: start;
    }
    @media(max-width: 900px) { .checkout-layout { grid-template-columns: 1fr; } }

    .section-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e7e5e4;
        overflow: hidden;
        margin-bottom: 20px;
    }

    .section-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid #f5f5f4;
        font-size: 0.95rem;
        font-weight: 700;
        color: #1c1917;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-card-body { padding: 20px; }

    .form-group { margin-bottom: 18px; }

    .form-label {
        display: block;
        font-size: 0.83rem;
        font-weight: 600;
        color: #292524;
        margin-bottom: 7px;
    }
    .form-label .required { color: #dc2626; }

    .form-control {
        width: 100%;
        padding: 11px 14px;
        border: 1.5px solid #e7e5e4;
        border-radius: 10px;
        font-size: 0.875rem;
        font-family: 'Inter', sans-serif;
        color: #1c1917;
        background: #fafaf9;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-control:focus {
        outline: none;
        border-color: #0ea5e9;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(14,165,233,0.12);
    }
    textarea.form-control { resize: vertical; min-height: 80px; }

    .form-error {
        font-size: 0.75rem;
        color: #dc2626;
        margin-top: 5px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* Radio Options */
    .radio-options {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }
    .radio-option {
        position: relative;
    }
    .radio-option input[type="radio"] {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }
    .radio-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 14px 12px;
        border: 2px solid #e7e5e4;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s;
        text-align: center;
    }
    .radio-option input:checked + .radio-label {
        border-color: #0ea5e9;
        background: #f0f9ff;
    }
    .radio-label:hover { border-color: #0ea5e9; background: #f0f9ff; }
    .radio-icon { font-size: 1.5rem; }
    .radio-title { font-size: 0.85rem; font-weight: 700; color: #1c1917; }
    .radio-desc { font-size: 0.72rem; color: #78716c; }

    /* Address section toggle */
    #section-alamat { display: none; }
    #section-bukti { display: none; }

    /* Upload area */
    .upload-area {
        border: 2px dashed #e7e5e4;
        border-radius: 12px;
        padding: 24px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        position: relative;
    }
    .upload-area:hover { border-color: #0ea5e9; background: #f0f9ff; }
    .upload-area input[type="file"] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
    }
    .upload-icon { font-size: 2rem; margin-bottom: 8px; }
    .upload-title { font-size: 0.875rem; font-weight: 600; color: #1c1917; }
    .upload-hint { font-size: 0.75rem; color: #78716c; margin-top: 4px; }
    .upload-preview { margin-top: 12px; }
    .upload-preview img { max-width: 100%; max-height: 200px; border-radius: 8px; object-fit: contain; }

    /* Bank Info */
    .bank-info {
        background: #fafaf9;
        border: 1px solid #e7e5e4;
        border-radius: 10px;
        padding: 14px;
        margin-top: 12px;
    }
    .bank-row { display: flex; justify-content: space-between; font-size: 0.83rem; padding: 4px 0; }
    .bank-row .bank-key { color: #78716c; }
    .bank-row .bank-val { font-weight: 700; color: #1c1917; }

    /* Cash info */
    .cash-info {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 10px;
        padding: 14px;
        font-size: 0.83rem;
        color: #15803d;
        display: none;
        margin-top: 12px;
    }

    /* Summary */
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

    .summary-body { padding: 16px 20px; }

    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #f5f5f4;
        font-size: 0.83rem;
    }
    .summary-item:last-of-type { border-bottom: none; }
    .summary-item .item-name { color: #78716c; }
    .summary-item .item-val { font-weight: 600; }

    .summary-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 0 0;
        border-top: 2px solid #f5f5f4;
        margin-top: 4px;
    }
    .summary-total .label { font-weight: 700; font-size: 1rem; }
    .summary-total .value { font-weight: 800; color: #0ea5e9; font-size: 1.15rem; }

    .btn-order {
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
        box-shadow: 0 4px 15px rgba(14,165,233,0.3);
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .btn-order:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(14,165,233,0.4); }

    .page-title { font-size: 1.5rem; font-weight: 800; color: #1c1917; margin-bottom: 6px; }
    .page-title span { color: #0ea5e9; }
    .page-subtitle { color: #78716c; font-size: 0.875rem; margin-bottom: 28px; }
</style>
@endsection

@section('content')
<div class="page-title">✅ <span>Checkout</span></div>
<div class="page-subtitle">Lengkapi informasi pengiriman dan pembayaran Anda</div>

<form method="POST" action="{{ route('checkout.proses') }}" enctype="multipart/form-data" id="form-checkout">
@csrf

<div class="checkout-layout">
    <!-- Left Column -->
    <div>
        <!-- Opsi Pengiriman -->
        <div class="section-card">
            <div class="section-card-header">🚗 Opsi Pengiriman</div>
            <div class="section-card-body">
                <div class="radio-options">
                    <div class="radio-option">
                        <input type="radio" name="opsi_pengiriman" id="take_away" value="take_away"
                               {{ old('opsi_pengiriman', 'take_away') === 'take_away' ? 'checked' : '' }}>
                        <label for="take_away" class="radio-label">
                            <span class="radio-icon">🏃</span>
                            <span class="radio-title">Take Away</span>
                            <span class="radio-desc">Ambil sendiri di toko</span>
                        </label>
                    </div>
                    <div class="radio-option">
                        <input type="radio" name="opsi_pengiriman" id="delivery" value="delivery"
                               {{ old('opsi_pengiriman') === 'delivery' ? 'checked' : '' }}>
                        <label for="delivery" class="radio-label">
                            <span class="radio-icon">🛵</span>
                            <span class="radio-title">Delivery</span>
                            <span class="radio-desc">Antar ke alamat Anda</span>
                        </label>
                    </div>
                </div>
                @error('opsi_pengiriman')
                    <div class="form-error">⚠️ {{ $message }}</div>
                @enderror

                <!-- Alamat Pengiriman (muncul jika delivery) -->
                <div id="section-alamat" style="margin-top:16px;">
                    <div class="form-group" style="margin-bottom:0;">
                        <label for="alamat_pengiriman" class="form-label">
                            Alamat Pengiriman <span class="required">*</span>
                        </label>
                        <textarea name="alamat_pengiriman" id="alamat_pengiriman" class="form-control"
                                  rows="3" placeholder="Masukkan alamat lengkap termasuk kelurahan, kecamatan...">{{ old('alamat_pengiriman', $user->address) }}</textarea>
                        @error('alamat_pengiriman')
                            <div class="form-error">⚠️ {{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Metode Pembayaran -->
        <div class="section-card">
            <div class="section-card-header">💳 Metode Pembayaran</div>
            <div class="section-card-body">
                <div class="radio-options">
                    <div class="radio-option">
                        <input type="radio" name="metode_pembayaran" id="cash" value="cash"
                               {{ old('metode_pembayaran', 'cash') === 'cash' ? 'checked' : '' }}>
                        <label for="cash" class="radio-label">
                            <span class="radio-icon">💵</span>
                            <span class="radio-title">Cash</span>
                            <span class="radio-desc">Bayar di tempat (Take Away)</span>
                        </label>
                    </div>
                    <div class="radio-option">
                        <input type="radio" name="metode_pembayaran" id="transfer" value="transfer"
                               {{ old('metode_pembayaran') === 'transfer' ? 'checked' : '' }}>
                        <label for="transfer" class="radio-label">
                            <span class="radio-icon">📱</span>
                            <span class="radio-title">Transfer</span>
                            <span class="radio-desc">Bank / E-Wallet</span>
                        </label>
                    </div>
                </div>
                @error('metode_pembayaran')
                    <div class="form-error">⚠️ {{ $message }}</div>
                @enderror

                <!-- Info Cash -->
                <div id="info-cash" class="cash-info" style="display:block;">
                    ✅ Pembayaran dilakukan langsung saat mengambil pesanan di toko.
                </div>

                <!-- Bagian Transfer -->
                <div id="section-bukti" style="margin-top:16px;">
                    <div class="bank-info">
                        <div style="font-size:0.8rem;font-weight:700;color:#1c1917;margin-bottom:8px;">📌 Rekening Tujuan</div>
                        <div class="bank-row">
                            <span class="bank-key">Bank</span>
                            <span class="bank-val">BCA / GoPay / Dana</span>
                        </div>
                        <div class="bank-row">
                            <span class="bank-key">No. Rekening</span>
                            <span class="bank-val">0812-3456-7890</span>
                        </div>
                        <div class="bank-row">
                            <span class="bank-key">Nama</span>
                            <span class="bank-val">Cireng Pasrah</span>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top:16px;margin-bottom:0;">
                        <label class="form-label">
                            Upload Bukti Transfer <span class="required">*</span>
                        </label>
                        <div class="upload-area" id="upload-area">
                            <input type="file" name="bukti_pembayaran" id="bukti_pembayaran"
                                   accept="image/jpg,image/jpeg,image/png,image/webp"
                                   onchange="previewBukti(this)">
                            <div id="upload-placeholder">
                                <div class="upload-icon">📸</div>
                                <div class="upload-title">Klik atau drag foto bukti transfer</div>
                                <div class="upload-hint">Format: JPG, PNG, WEBP (Maks. 2MB)</div>
                            </div>
                            <div class="upload-preview" id="preview-container" style="display:none;">
                                <img id="preview-img" src="" alt="Preview">
                                <div style="font-size:0.75rem;color:#15803d;margin-top:8px;font-weight:600;">✅ Foto siap diunggah</div>
                            </div>
                        </div>
                        @error('bukti_pembayaran')
                            <div class="form-error">⚠️ {{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Catatan -->
        <div class="section-card">
            <div class="section-card-header">📝 Catatan Tambahan</div>
            <div class="section-card-body">
                <div class="form-group" style="margin-bottom:0;">
                    <textarea name="catatan" class="form-control" rows="3"
                              placeholder="Contoh: Cirengnya jangan terlalu pedas, tolong dibungkus terpisah, dll.">{{ old('catatan') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column - Order Summary -->
    <div>
        <div class="summary-card">
            <div class="summary-header">
                <h3>🛒 Ringkasan Pesanan</h3>
            </div>
            <div class="summary-body">
                @foreach($keranjang as $item)
                    <div class="summary-item">
                        <span class="item-name">{{ $item['nama'] }} x{{ $item['qty'] }}</span>
                        <span class="item-val">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                    </div>
                @endforeach

                <div class="summary-total">
                    <span class="label">💰 Total Pembayaran</span>
                    <span class="value">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>

                <button type="submit" class="btn-order">
                    ✅ Buat Pesanan Sekarang
                </button>

                <a href="{{ route('keranjang.index') }}"
                   style="display:block;text-align:center;margin-top:12px;font-size:0.8rem;color:#78716c;text-decoration:none;">
                    ← Kembali ke Keranjang
                </a>
            </div>
        </div>
    </div>
</div>
</form>
@endsection

@section('scripts')
<script>
    const radioDelivery   = document.getElementById('delivery');
    const radioTakeAway   = document.getElementById('take_away');
    const radioCash       = document.getElementById('cash');
    const radioTransfer   = document.getElementById('transfer');
    const sectionAlamat   = document.getElementById('section-alamat');
    const sectionBukti    = document.getElementById('section-bukti');
    const infoCash        = document.getElementById('info-cash');

    function toggleAlamat() {
        sectionAlamat.style.display = radioDelivery.checked ? 'block' : 'none';
    }

    function togglePembayaran() {
        if (radioTransfer.checked) {
            sectionBukti.style.display = 'block';
            infoCash.style.display = 'none';
            // If delivery selected and transfer, make cash radio invalid
            if (radioDelivery.checked) {
                infoCash.style.display = 'none';
            }
        } else {
            sectionBukti.style.display = 'none';
            infoCash.style.display = 'block';
        }
    }

    function previewBukti(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('preview-container').style.display = 'block';
                document.getElementById('upload-placeholder').style.display = 'none';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    radioDelivery.addEventListener('change', toggleAlamat);
    radioTakeAway.addEventListener('change', toggleAlamat);
    radioCash.addEventListener('change', togglePembayaran);
    radioTransfer.addEventListener('change', togglePembayaran);

    // Initialize on page load
    toggleAlamat();
    togglePembayaran();

    // Validasi: cash tidak bisa untuk delivery
    document.getElementById('form-checkout').addEventListener('submit', function(e) {
        if (radioDelivery.checked && radioCash.checked) {
            e.preventDefault();
            alert('⚠️ Pembayaran Cash hanya tersedia untuk opsi Take Away. Silakan pilih Transfer untuk Delivery.');
        }
    });
</script>
@endsection
