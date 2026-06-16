<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\DetailPesanan;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CheckoutController extends Controller
{
    private const SESSION_KEY = 'keranjang';

    // Koordinat toko (dari .env)
    private function getStoreLat(): float  { return (float) env('STORE_LAT', -6.200000); }
    private function getStorelng(): float  { return (float) env('STORE_LNG', 106.816666); }
    private function getOngkirPerKm(): int { return (int) env('ONGKIR_PER_KM', 3000); }

    public function index()
    {
        $keranjang = session(self::SESSION_KEY, []);

        if (empty($keranjang)) {
            return redirect()->route('keranjang.index')
                ->with('error', 'Keranjang belanja Anda kosong. Silakan tambahkan produk terlebih dahulu.');
        }

        $subtotal = array_sum(array_column($keranjang, 'subtotal'));
        $user     = auth()->user();

        return view('customer.checkout.index', compact('keranjang', 'subtotal', 'user'));
    }

    /**
     * Endpoint AJAX: hitung ongkir berdasarkan jarak dari Google Maps
     */
    public function hitungOngkir(Request $request)
    {
        $request->validate([
            'jarak_meter' => 'required|numeric|min:0',
        ]);

        $jarakMeter = (int) $request->jarak_meter;
        $jarakKm    = $jarakMeter / 1000;
        $ongkir     = (int) ceil($jarakKm * $this->getOngkirPerKm());

        return response()->json([
            'jarak_meter' => $jarakMeter,
            'jarak_km'    => round($jarakKm, 2),
            'ongkir'      => $ongkir,
            'ongkir_formatted' => 'Rp ' . number_format($ongkir, 0, ',', '.'),
        ]);
    }

    public function proses(Request $request)
    {
        $keranjang = session(self::SESSION_KEY, []);

        if (empty($keranjang)) {
            return redirect()->route('katalog.index')
                ->with('error', 'Keranjang Anda kosong.');
        }

        // Validasi dasar
        $rules = [
            'opsi_pengiriman'   => 'required|in:take_away,delivery',
            'metode_pembayaran' => 'required|in:cash,transfer,cod',
            'catatan'           => 'nullable|string|max:500',
        ];

        $messages = [
            'opsi_pengiriman.required'   => 'Pilih opsi pengiriman.',
            'metode_pembayaran.required' => 'Pilih metode pembayaran.',
        ];

        // Jika delivery, alamat & jarak wajib
        if ($request->opsi_pengiriman === 'delivery') {
            $rules['alamat_pengiriman'] = 'required|string|max:500';
            $rules['jarak_meter']       = 'required|integer|min:0';
            $messages['alamat_pengiriman.required'] = 'Alamat pengiriman wajib diisi untuk opsi Delivery.';
            $messages['jarak_meter.required']       = 'Jarak pengiriman wajib dihitung untuk Delivery.';
        }

        // Jika transfer, bukti pembayaran wajib
        if ($request->metode_pembayaran === 'transfer') {
            $rules['bukti_pembayaran'] = 'required|image|mimes:jpg,jpeg,png,webp|max:2048';
            $messages['bukti_pembayaran.required'] = 'Bukti transfer wajib diunggah untuk pembayaran via Transfer.';
            $messages['bukti_pembayaran.image']    = 'File harus berupa gambar.';
            $messages['bukti_pembayaran.mimes']    = 'Format gambar harus jpg, jpeg, png, atau webp.';
            $messages['bukti_pembayaran.max']      = 'Ukuran gambar maksimal 2MB.';
        }

        $request->validate($rules, $messages);

        DB::beginTransaction();
        try {
            $buktiPath = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $buktiPath = $request->file('bukti_pembayaran')
                    ->store('bukti', 'public');
            }

            $subtotal    = array_sum(array_column($keranjang, 'subtotal'));
            $jarakMeter  = $request->opsi_pengiriman === 'delivery' ? (int) $request->jarak_meter : 0;
            $ongkir      = $jarakMeter > 0 ? (int) ceil(($jarakMeter / 1000) * $this->getOngkirPerKm()) : 0;
            $totalHarga  = $subtotal; // total_harga = subtotal produk saja (ongkir terpisah)

            // Tentukan status pembayaran awal
            $statusPembayaran = 'belum_dibayar'; // transfer + COD = belum lunas, nunggu konfirmasi

            $pesanan = Pesanan::create([
                'user_id'            => auth()->id(),
                'kode_pesanan'       => Pesanan::generateKodePesanan(),
                'total_harga'        => $totalHarga,
                'ongkir'             => $ongkir,
                'jarak_meter'        => $jarakMeter ?: null,
                'opsi_pengiriman'    => $request->opsi_pengiriman,
                'alamat_pengiriman'  => $request->alamat_pengiriman,
                'metode_pembayaran'  => $request->metode_pembayaran,
                'status_pembayaran'  => $statusPembayaran,
                'bukti_pembayaran'   => $buktiPath,
                'status'             => 'pending',
                'catatan'            => $request->catatan,
            ]);

            foreach ($keranjang as $item) {
                $produk = Produk::find($item['produk_id']);
                if (!$produk) {
                    throw new \Exception('Produk tidak ditemukan: ' . $item['nama']);
                }

                DetailPesanan::create([
                    'pesanan_id'  => $pesanan->id,
                    'produk_id'   => $item['produk_id'],
                    'nama_produk' => $item['nama'],
                    'harga'       => $item['harga'],
                    'qty'         => $item['qty'],
                    'subtotal'    => $item['subtotal'],
                ]);
            }

            DB::commit();

            // Kosongkan keranjang setelah berhasil checkout (revisi #11)
            session()->forget(self::SESSION_KEY);

            return redirect()->route('pesanan.show', $pesanan->kode_pesanan)
                ->with('success', 'Pesanan berhasil dibuat! Kode pesanan Anda: ' . $pesanan->kode_pesanan);

        } catch (\Exception $e) {
            DB::rollBack();
            if ($buktiPath) {
                Storage::disk('public')->delete($buktiPath);
            }
            return back()->with('error', 'Gagal membuat pesanan: ' . $e->getMessage())->withInput();
        }
    }
}
