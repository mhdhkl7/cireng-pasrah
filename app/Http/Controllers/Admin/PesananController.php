<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventori;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        $query = Pesanan::with(['user', 'detailPesanans'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('status_pembayaran')) {
            $query->where('status_pembayaran', $request->status_pembayaran);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('kode_pesanan', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function ($u) use ($request) {
                      $u->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $pesanans = $query->paginate(15);
        return view('admin.pesanan.index', compact('pesanans'));
    }

    public function show(Pesanan $pesanan)
    {
        $pesanan->load(['user', 'detailPesanans.produk']);
        return view('admin.pesanan.show', compact('pesanan'));
    }

    public function updateStatus(Request $request, Pesanan $pesanan)
    {
        $request->validate([
            'status'            => 'required|in:pending,diproses,siap,selesai,dibatalkan',
            'status_pembayaran' => 'required|in:belum_dibayar,lunas',
        ]);

        $statusLama = $pesanan->status;
        $statusBaru = $request->status;

        // Logic pengurangan stok otomatis saat status berubah ke 'diproses'
        if ($statusLama !== 'diproses' && $statusBaru === 'diproses') {
            $inventori = Inventori::first();

            if ($inventori) {
                $totalQty = $pesanan->detailPesanans->sum('qty');

                if ($inventori->stok < $totalQty) {
                    return back()->with('error',
                        'Stok cireng mentah tidak mencukupi! Stok tersisa: ' . $inventori->stok .
                        ' ' . $inventori->satuan . ', dibutuhkan: ' . $totalQty . ' ' . $inventori->satuan . '.'
                    );
                }

                $inventori->kurangiStok($totalQty);
            }
        }

        // Jika status dikembalikan dari 'diproses' (rollback stok)
        if ($statusLama === 'diproses' && in_array($statusBaru, ['pending', 'dibatalkan'])) {
            $inventori = Inventori::first();
            if ($inventori) {
                $totalQty = $pesanan->detailPesanans->sum('qty');
                $inventori->tambahStok($totalQty);
            }
        }

        $pesanan->update([
            'status'            => $statusBaru,
            'status_pembayaran' => $request->status_pembayaran,
        ]);

        return redirect()->route('admin.pesanan.show', $pesanan->id)
            ->with('success', 'Status pesanan berhasil diperbarui!');
    }

    public function verifikasiPembayaran(Pesanan $pesanan)
    {
        if (!$pesanan->bukti_pembayaran) {
            return back()->with('error', 'Pesanan ini tidak memiliki bukti pembayaran.');
        }

        $pesanan->update(['status_pembayaran' => 'lunas']);

        return back()->with('success', 'Pembayaran berhasil diverifikasi! Status diubah menjadi Lunas.');
    }
}
