<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventori;
use Illuminate\Http\Request;

class InventoriController extends Controller
{
    public function index()
    {
        $inventoris = Inventori::latest()->paginate(10);
        return view('admin.inventori.index', compact('inventoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_item'  => 'required|string|max:255',
            'stok'       => 'required|integer|min:0',
            'satuan'     => 'required|string|max:50',
            'keterangan' => 'nullable|string|max:500',
        ]);

        Inventori::create($request->only(['nama_item', 'stok', 'satuan', 'keterangan']));

        return redirect()->route('admin.inventori.index')
            ->with('success', 'Item inventori berhasil ditambahkan!');
    }

    public function update(Request $request, Inventori $inventori)
    {
        $request->validate([
            'nama_item'  => 'required|string|max:255',
            'stok'       => 'required|integer|min:0',
            'satuan'     => 'required|string|max:50',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $inventori->update($request->only(['nama_item', 'stok', 'satuan', 'keterangan']));

        return redirect()->route('admin.inventori.index')
            ->with('success', 'Stok berhasil diperbarui!');
    }

    public function destroy(Inventori $inventori)
    {
        $inventori->delete();
        return redirect()->route('admin.inventori.index')
            ->with('success', 'Item inventori berhasil dihapus!');
    }

    public function tambahStok(Request $request, Inventori $inventori)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1',
        ]);

        $inventori->tambahStok($request->jumlah);

        return redirect()->route('admin.inventori.index')
            ->with('success', 'Stok berhasil ditambahkan sebesar ' . $request->jumlah . ' ' . $inventori->satuan . '!');
    }
}
