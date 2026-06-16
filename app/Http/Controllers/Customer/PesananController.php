<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;

class PesananController extends Controller
{
    public function index()
    {
        $pesanans = Pesanan::where('user_id', auth()->id())
            ->with(['detailPesanans.produk'])  // load produk untuk gambar (#9)
            ->latest()
            ->paginate(10);

        return view('customer.pesanan.index', compact('pesanans'));
    }

    public function show(string $kode)
    {
        $pesanan = Pesanan::where('kode_pesanan', $kode)
            ->where('user_id', auth()->id())
            ->with(['detailPesanans.produk', 'user'])
            ->firstOrFail();

        return view('customer.pesanan.show', compact('pesanan'));
    }
}
