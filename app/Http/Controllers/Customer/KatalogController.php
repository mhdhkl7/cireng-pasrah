<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Produk;

class KatalogController extends Controller
{
    public function index()
    {
        $produks = Produk::where('is_active', true)->latest()->get();
        return view('customer.katalog.index', compact('produks'));
    }

    public function show(Produk $produk)
    {
        if (!$produk->is_active) {
            abort(404);
        }
        return view('customer.katalog.show', compact('produk'));
    }
}
