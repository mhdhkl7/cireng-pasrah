@extends('layouts.customer')

@section('title', 'Checkout Pesanan')
@section('styles')
<style>
    .checkout-layout {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 20px;
        align-items: start;
    }
    @media(max-width: 900px) { .checkout-layout { grid-template-columns: 1fr; } }

    .section-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e7e5e4;
        overflow: hidden;
        margin-bottom: 18px;
    }
    .section-header {
        padding: 15px 20px;
        border-bottom: 1px solid #f5f5f4;
        font-size: 0.9rem;
        font-weight: 700;
        color: #1c1917;
    }
    .section-body { padding: 20px; }

    .form-group { margin-bottom: 18px; }
    .form-label { display: block; font-size: 0.83rem; font-weight: 600; color: #292524; margin-bottom: 6px; }
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
    .form-error { font-size: 0.75rem; color: #dc2626; margin-top: 4px; }

    /* Radio cards */
    .option-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    @media(max-width:500px) { .option-grid { grid-template-columns: 1fr; } }
    .option-grid.three-cols { grid-template-columns: repeat(3, 1fr); }
    @media(max-width:600px) { .option-grid.three-cols { grid-template-columns: 1fr; } }

    .option-card {
        cursor: pointer;
        border: 2px solid #e7e5e4;
        border-radius: 12px;
        padding: 12px 14px;
        transition: border-color 0.2s, background 0.2s;
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }
    .option-card:has(input:checked) {
        border-color: #0ea5e9;
        background: #f0f9ff;
    }
    .option-icon { font-size: 1.4rem; flex-shrink: 0; margin-top: 1px; }
    .option-label { font-size: 0.85rem; font-weight: 700; color: #1c1917; }
    .option-desc { font-size: 0.72rem; color: #78716c; margin-top: 1px; }
    .option-card input[type=radio] { display: none; }

    /* Maps Section */
    #map-section { display: none; }
    #map {
        width: 100%;
        height: 300px;
        border-radius: 10px;
        border: 1.5px solid #e7e5e4;
        background: #f0f9ff;
        margin-bottom: 12px;
    }
    .ongkir-info {
        background: linear-gradient(135deg, #f0f9ff, #d1fae5);
        border: 1px solid #bae6fd;
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #0369a1;
        display: none;
        margin-top: 10px;
    }

    /* COD banner */
    .cod-info {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 10px;
        padding: 12px 14px;
        font-size: 0.83rem;
        color: #065f46;
        font-weight: 500;
        display: none;
        margin-top: 8px;
    }

    /* Bukti transfer */
    #bukti-wrap { display: none; margin-top: 8px; }
    .upload-area {
        border: 2px dashed #bae6fd;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: border-color 0.2s;
    }
    .upload-area:hover { border-color: #0ea5e9; }

    /* Order summary */
    .summary-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 8px 0; border-bottom: 1px solid #f5f5f4;
        font-size: 0.83rem;
    }
    .summary-row:last-child { border-bottom: none; }
    .summary-key { color: #78716c; }
    .summary-val { font-weight: 600; }
    .summary-total {
        display: flex; justify-content: space-between; align-items: center;
        padding: 14px 0 0; border-top: 2px solid #e7e5e4; margin-top: 6px;
    }
    .total-label { font-size: 1rem; font-weight: 700; }
    .total-value { font-size: 1.2rem; font-weight: 900; color: #0ea5e9; }

    .item-row { display: flex; align-items: center; gap: 10px; padding: 8px 0; border-bottom: 1px solid #f5f5f4; }
    .item-row:last-child { border-bottom: none; }
    .item-thumb { width: 42px; height: 42px; border-radius: 8px; overflow: hidden; background: #f0f9ff; flex-shrink: 0; display:flex;align-items:center;justify-content:center; }
    .item-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .item-name { font-size: 0.83rem; font-weight: 700; flex: 1; }
    .item-price { font-size: 0.75rem; color: #78716c; }
    .item-subtotal { font-size: 0.83rem; font-weight: 700; color: #0ea5e9; }

    .btn-submit {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #0ea5e9, #10b981);
        color: #fff;
        border: none;
        border-radius: 12px;
        font-size: 0.95rem;
        font-weight: 700;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        box-shadow: 0 4px 15px rgba(14,165,233,0.35);
        transition: all 0.2s;
    }
    .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(14,165,233,0.45); }
    .btn-submit:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
</style>
@endsection

@section('content')
<div style="font-size:1.5rem;font-weight:900;color:#1c1917;margin-bottom:4px;">🛒 Checkout</div>
<div style="color:#78716c;font-size:0.875rem;margin-bottom:24px;">Lengkapi informasi pengiriman & pembayaran</div>

<form method="POST" action="{{ route('checkout.proses') }}" enctype="multipart/form-data" id="checkout-form">
@csrf

<div class="checkout-layout">
    {{-- Left Column --}}
    <div>
        {{-- Opsi Pengiriman --}}
        <div class="section-card">
            <div class="section-header">🚚 Opsi Pengiriman</div>
            <div class="section-body">
                <div class="option-grid">
                    <label class="option-card">
                        <input type="radio" name="opsi_pengiriman" value="take_away" onchange="onPengirimanChange()" required>
                        <div>
                            <div class="option-icon">🏃</div>
                        </div>
                        <div>
                            <div class="option-label">Take Away</div>
                            <div class="option-desc">Ambil pesanan langsung di toko kami.</div>
                        </div>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="opsi_pengiriman" value="delivery" onchange="onPengirimanChange()">
                        <div>
                            <div class="option-icon">🛵</div>
                        </div>
                        <div>
                            <div class="option-label">Delivery</div>
                            <div class="option-desc">Diantar ke lokasi Anda. Ongkir dihitung per KM.</div>
                        </div>
                    </label>
                </div>
                @error('opsi_pengiriman') <div class="form-error">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- Alamat Pengiriman + Maps (hanya delivery) --}}
        <div class="section-card" id="map-section">
            <div class="section-header">📍 Lokasi Pengiriman</div>
            <div class="section-body">
                <div class="form-group">
                    <label class="form-label">Alamat Lengkap <span class="required">*</span></label>
                    <textarea name="alamat_pengiriman" id="alamat_pengiriman" class="form-control" rows="2"
                              placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan...">{{ old('alamat_pengiriman', $user->address) }}</textarea>
                    @error('alamat_pengiriman') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group" style="margin-top:14px;">
                    <label class="form-label">Perkiraan Jarak Pengiriman (Meter) <span class="required">*</span></label>
                    <div style="display:flex;gap:10px;align-items:center;">
                        <input type="number" id="jarak_meter_input" class="form-control" placeholder="Contoh: 2500 (untuk 2.5 km)" min="1" value="{{ old('jarak_meter') }}" style="flex:1;">
                        <button type="button" onclick="hitungOngkirManual()" id="btn-hitung"
                                style="padding:11px 18px;background:linear-gradient(135deg,#0ea5e9,#10b981);color:#fff;border:none;border-radius:10px;font-size:0.83rem;font-weight:700;cursor:pointer;font-family:'Inter',sans-serif;white-space:nowrap;">
                            📏 Hitung Ongkir
                        </button>
                    </div>
                    <div style="font-size:0.75rem;color:#78716c;margin-top:6px;">Masukkan perkiraan jarak dari toko kami ke lokasi Anda dalam satuan <strong>meter</strong>.</div>
                </div>

                <div class="ongkir-info" id="ongkir-info">
                    <div id="ongkir-text"></div>
                </div>

                {{-- Hidden fields --}}
                <input type="hidden" name="jarak_meter" id="jarak_meter" value="{{ old('jarak_meter', 0) }}">
            </div>
        </div>

        {{-- Metode Pembayaran --}}
        <div class="section-card">
            <div class="section-header">💳 Metode Pembayaran</div>
            <div class="section-body">
                <div class="option-grid three-cols" id="payment-options">
                    {{-- Take Away: Cash & Transfer --}}
                    <label class="option-card" id="opt-cash">
                        <input type="radio" name="metode_pembayaran" value="cash" onchange="onPaymentChange()" required>
                        <div>
                            <div class="option-icon">💵</div>
                            <div class="option-label">Cash</div>
                            <div class="option-desc">Bayar di tempat</div>
                        </div>
                    </label>
                    <label class="option-card" id="opt-transfer">
                        <input type="radio" name="metode_pembayaran" value="transfer" onchange="onPaymentChange()">
                        <div>
                            <div class="option-icon">📱</div>
                            <div class="option-label">Transfer</div>
                            <div class="option-desc">Upload bukti transfer</div>
                        </div>
                    </label>
                    <label class="option-card" id="opt-cod" style="display:none;">
                        <input type="radio" name="metode_pembayaran" value="cod" onchange="onPaymentChange()">
                        <div>
                            <div class="option-icon">🚗</div>
                            <div class="option-label">COD</div>
                            <div class="option-desc">Bayar saat diterima</div>
                        </div>
                    </label>
                </div>
                @error('metode_pembayaran') <div class="form-error">{{ $message }}</div> @enderror

                {{-- COD Info --}}
                <div class="cod-info" id="cod-info">
                    🚗 <strong>Cash on Delivery</strong>: Bayar lunas saat pesanan sudah tiba di tangan Anda. Driver kami yang akan menagih.
                </div>

                {{-- Bukti Transfer --}}
                <div id="bukti-wrap">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Bukti Transfer <span class="required">*</span></label>
                        <div class="upload-area" onclick="document.getElementById('bukti_pembayaran').click()">
                            <div style="font-size:2rem;margin-bottom:6px;">📸</div>
                            <div style="font-size:0.83rem;font-weight:600;color:#0ea5e9;">Klik untuk upload bukti transfer</div>
                            <div style="font-size:0.72rem;color:#78716c;margin-top:3px;">Format: JPG, PNG, WEBP | Maks: 2MB</div>
                            <div id="file-name" style="margin-top:8px;font-size:0.78rem;color:#059669;font-weight:600;"></div>
                        </div>
                        <input type="file" name="bukti_pembayaran" id="bukti_pembayaran"
                               accept="image/*" style="display:none" onchange="showFileName(this)">
                        @error('bukti_pembayaran') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div style="margin-top:12px;background:#fffbeb;border:1px solid #fde68a;border-radius:10px;padding:10px 14px;font-size:0.78rem;color:#92400e;">
                        💳 <strong>Info Transfer:</strong><br>
                        BCA: 1234567890 a.n. Cireng Pasrah<br>
                        GoPay / OVO: 081234567890
                    </div>
                </div>
            </div>
        </div>

        {{-- Catatan --}}
        <div class="section-card">
            <div class="section-header">📝 Catatan Pesanan (Opsional)</div>
            <div class="section-body">
                <textarea name="catatan" class="form-control" rows="2"
                          placeholder="Contoh: Tidak pakai sambal, extra kecap...">{{ old('catatan') }}</textarea>
            </div>
        </div>
    </div>

    {{-- Right Column: Ringkasan --}}
    <div>
        <div class="section-card" style="position:sticky;top:80px;">
            <div class="section-header">🛒 Ringkasan Pesanan</div>
            <div class="section-body">
                {{-- Items --}}
                @foreach($keranjang as $item)
                    <div class="item-row">
                        <div class="item-thumb">
                            @if(isset($item['gambar']) && $item['gambar'])
                                <img src="{{ asset('storage/' . $item['gambar']) }}" alt="{{ $item['nama'] }}">
                            @else
                                🍟
                            @endif
                        </div>
                        <div class="item-name">{{ $item['nama'] }}
                            <div class="item-price">x{{ $item['qty'] }}</div>
                        </div>
                        <div class="item-subtotal">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</div>
                    </div>
                @endforeach

                {{-- Summary rows --}}
                <div style="margin-top:14px;">
                    <div class="summary-row">
                        <span class="summary-key">Subtotal Produk</span>
                        <span class="summary-val">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-row" id="ongkir-row" style="display:none;">
                        <span class="summary-key">Ongkir</span>
                        <span class="summary-val" id="ongkir-display">Rp 0</span>
                    </div>
                    <div class="summary-total">
                        <span class="total-label">Total</span>
                        <span class="total-value" id="total-display">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                </div>

                <button type="submit" class="btn-submit" id="btn-submit" style="margin-top:18px;">
                    🎉 Buat Pesanan
                </button>

                <div style="font-size:0.72rem;color:#a8a29e;text-align:center;margin-top:10px;">
                    Dengan memesan, Anda menyetujui syarat & ketentuan Cireng Pasrah
                </div>
            </div>
        </div>
    </div>
</div>
</form>
@endsection

@section('scripts')
<script>
<script>
const SUBTOTAL = {{ $subtotal }};
let currentOngkir = 0;

function hitungOngkirManual() {
    const jarakInput = document.getElementById('jarak_meter_input').value;
    const jarakMeter = parseInt(jarakInput);

    if (!jarakMeter || jarakMeter <= 0) {
        alert('Masukkan jarak dalam meter yang valid! Contoh: 2500');
        return;
    }

    const btn = document.getElementById('btn-hitung');
    btn.disabled = true;
    btn.textContent = 'Menghitung...';

    document.getElementById('jarak_meter').value = jarakMeter;

    // Fetch ongkir dari server
    fetch('{{ route("checkout.hitungOngkir") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ jarak_meter: jarakMeter }),
    })
    .then(r => r.json())
    .then(data => {
        currentOngkir = data.ongkir;
        const ongkirEl = document.getElementById('ongkir-info');
        ongkirEl.style.display = 'block';

        const jarakKm = (jarakMeter / 1000).toFixed(1);
        document.getElementById('ongkir-text').innerHTML =
            `📏 Jarak: <strong>${jarakKm} km</strong> &nbsp;|&nbsp; 🚗 Ongkir: <strong>${data.ongkir_formatted}</strong>`;

        // Update summary
        document.getElementById('ongkir-row').style.display = 'flex';
        document.getElementById('ongkir-display').textContent = data.ongkir_formatted;
        const total = SUBTOTAL + currentOngkir;
        document.getElementById('total-display').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
        document.getElementById('btn-submit').disabled = false;
    })
    .catch(err => {
        alert('Terjadi kesalahan saat menghitung ongkir.');
        console.error(err);
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '📏 Hitung Ongkir';
    });
}

// ── Toggle Delivery / Take Away ──────────────────────────
function onPengirimanChange() {
    const val = document.querySelector('input[name=opsi_pengiriman]:checked')?.value;
    const mapSection = document.getElementById('map-section');
    const codOpt     = document.getElementById('opt-cod');

    if (val === 'delivery') {
        mapSection.style.display = 'block';
        codOpt.style.display = 'flex'; // show COD option
        document.getElementById('btn-submit').disabled = true; // Force user to calculate ongkir
    } else {
        mapSection.style.display = 'none';
        codOpt.style.display = 'none'; // hide COD for take away
        
        // Reset ongkir
        currentOngkir = 0;
        document.getElementById('jarak_meter').value = 0;
        document.getElementById('jarak_meter_input').value = '';
        document.getElementById('ongkir-row').style.display = 'none';
        document.getElementById('total-display').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(SUBTOTAL);
        document.getElementById('ongkir-info').style.display = 'none';
        document.getElementById('btn-submit').disabled = false; // Enable submit

        // If COD was selected, deselect
        const codInput = document.querySelector('input[name=metode_pembayaran][value=cod]');
        if (codInput && codInput.checked) codInput.checked = false;
        onPaymentChange();
    }
}

// ── Toggle Bukti Transfer / COD ─────────────────────────
function onPaymentChange() {
    const val = document.querySelector('input[name=metode_pembayaran]:checked')?.value;
    document.getElementById('bukti-wrap').style.display  = val === 'transfer' ? 'block' : 'none';
    document.getElementById('cod-info').style.display    = val === 'cod'      ? 'block' : 'none';
}

function showFileName(input) {
    const name = input.files[0]?.name;
    if (name) document.getElementById('file-name').textContent = '✅ ' + name;
}
</script>
@endsection
