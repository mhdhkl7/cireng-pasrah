<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;

class KeranjangController extends Controller
{
    private const SESSION_KEY = 'keranjang';

    public function index()
    {
        $keranjang = session(self::SESSION_KEY, []);
        $total     = $this->hitungTotal($keranjang);
        return view('customer.keranjang.index', compact('keranjang', 'total'));
    }

    public function tambah(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'qty'       => 'required|integer|min:1|max:100',
        ]);

        $produk = Produk::findOrFail($request->produk_id);

        if (!$produk->is_active) {
            return back()->with('error', 'Produk tidak tersedia.');
        }

        $keranjang = session(self::SESSION_KEY, []);
        $key       = 'produk_' . $produk->id;

        if (isset($keranjang[$key])) {
            $keranjang[$key]['qty']      += $request->qty;
            $keranjang[$key]['subtotal']  = $keranjang[$key]['qty'] * $keranjang[$key]['harga'];
        } else {
            $keranjang[$key] = [
                'produk_id'   => $produk->id,
                'nama'        => $produk->nama,
                'harga'       => (float) $produk->harga,
                'qty'         => $request->qty,
                'subtotal'    => (float) $produk->harga * $request->qty,
                'gambar'      => $produk->gambar,
            ];
        }

        session([self::SESSION_KEY => $keranjang]);

        return back()->with('success', $produk->nama . ' berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'produk_id' => 'required',
            'qty'       => 'required|integer|min:1|max:100',
        ]);

        $keranjang = session(self::SESSION_KEY, []);
        $key       = 'produk_' . $request->produk_id;

        if (isset($keranjang[$key])) {
            $keranjang[$key]['qty']     = $request->qty;
            $keranjang[$key]['subtotal'] = $keranjang[$key]['harga'] * $request->qty;
            session([self::SESSION_KEY => $keranjang]);
        }

        return back()->with('success', 'Keranjang berhasil diperbarui.');
    }

    public function hapus(Request $request)
    {
        $request->validate([
            'produk_id' => 'required',
        ]);

        $keranjang = session(self::SESSION_KEY, []);
        $key       = 'produk_' . $request->produk_id;

        unset($keranjang[$key]);
        session([self::SESSION_KEY => $keranjang]);

        return back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    public function kosongkan()
    {
        session()->forget(self::SESSION_KEY);
        return back()->with('success', 'Keranjang berhasil dikosongkan.');
    }

    private function hitungTotal(array $keranjang): float
    {
        return array_sum(array_column($keranjang, 'subtotal'));
    }
}
