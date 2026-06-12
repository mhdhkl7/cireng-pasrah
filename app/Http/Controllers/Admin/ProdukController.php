<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = Produk::latest()->paginate(10);
        return view('admin.produk.index', compact('produks'));
    }

    public function create()
    {
        return view('admin.produk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:255',
            'deskripsi'=> 'nullable|string',
            'harga'    => 'required|numeric|min:0',
            'gambar'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active'=> 'nullable|boolean',
        ], [
            'nama.required'  => 'Nama produk wajib diisi.',
            'harga.required' => 'Harga wajib diisi.',
            'harga.numeric'  => 'Harga harus berupa angka.',
            'gambar.image'   => 'File harus berupa gambar.',
            'gambar.max'     => 'Ukuran gambar maksimal 2MB.',
        ]);

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('produk', 'public');
        }

        Produk::create([
            'nama'      => $request->nama,
            'slug'      => Str::slug($request->nama),
            'deskripsi' => $request->deskripsi,
            'harga'     => $request->harga,
            'gambar'    => $gambarPath,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(Produk $produk)
    {
        return view('admin.produk.edit', compact('produk'));
    }

    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga'     => 'required|numeric|min:0',
            'gambar'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $gambarPath = $produk->gambar;
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($produk->gambar) {
                Storage::disk('public')->delete($produk->gambar);
            }
            $gambarPath = $request->file('gambar')->store('produk', 'public');
        }

        $produk->update([
            'nama'      => $request->nama,
            'slug'      => Str::slug($request->nama),
            'deskripsi' => $request->deskripsi,
            'harga'     => $request->harga,
            'gambar'    => $gambarPath,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Produk $produk)
    {
        if ($produk->gambar) {
            Storage::disk('public')->delete($produk->gambar);
        }
        $produk->delete();

        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk berhasil dihapus!');
    }
}
