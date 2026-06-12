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

    public function index()
    {
        $keranjang = session(self::SESSION_KEY, []);

        if (empty($keranjang)) {
            return redirect()->route('keranjang.index')
                ->with('error', 'Keranjang belanja Anda kosong. Silakan tambahkan produk terlebih dahulu.');
        }

        $total = array_sum(array_column($keranjang, 'subtotal'));
        $user  = auth()->user();

        return view('customer.checkout.index', compact('keranjang', 'total', 'user'));
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
            'metode_pembayaran' => 'required|in:cash,transfer',
            'catatan'           => 'nullable|string|max:500',
        ];

        $messages = [
            'opsi_pengiriman.required'   => 'Pilih opsi pengiriman.',
            'metode_pembayaran.required' => 'Pilih metode pembayaran.',
        ];

        // Jika delivery, alamat wajib
        if ($request->opsi_pengiriman === 'delivery') {
            $rules['alamat_pengiriman'] = 'required|string|max:500';
            $messages['alamat_pengiriman.required'] = 'Alamat pengiriman wajib diisi untuk opsi Delivery.';
        }

        // Jika transfer, bukti pembayaran wajib
        if ($request->metode_pembayaran === 'transfer') {
            $rules['bukti_pembayaran'] = 'required|image|mimes:jpg,jpeg,png,webp|max:2048';
            $messages['bukti_pembayaran.required'] = 'Bukti transfer wajib diunggah untuk pembayaran via Transfer.';
            $messages['bukti_pembayaran.image']    = 'File harus berupa gambar.';
            $messages['bukti_pembayaran.mimes']    = 'Format gambar harus jpg, jpeg, png, atau webp.';
            $messages['bukti_pembayaran.max']      = 'Ukuran gambar maksimal 2MB.';
        }

        // Validasi: cash hanya untuk take_away
        if ($request->metode_pembayaran === 'cash' && $request->opsi_pengiriman === 'delivery') {
            return back()->withErrors(['metode_pembayaran' => 'Pembayaran Cash hanya tersedia untuk opsi Take Away.'])
                ->withInput();
        }

        $request->validate($rules, $messages);

        DB::beginTransaction();
        try {
            $buktiPath = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $buktiPath = $request->file('bukti_pembayaran')
                    ->store('bukti', 'public');
            }

            $total = array_sum(array_column($keranjang, 'subtotal'));

            $pesanan = Pesanan::create([
                'user_id'            => auth()->id(),
                'kode_pesanan'       => Pesanan::generateKodePesanan(),
                'total_harga'        => $total,
                'opsi_pengiriman'    => $request->opsi_pengiriman,
                'alamat_pengiriman'  => $request->alamat_pengiriman,
                'metode_pembayaran'  => $request->metode_pembayaran,
                'status_pembayaran'  => $request->metode_pembayaran === 'transfer' ? 'belum_dibayar' : 'belum_dibayar',
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
