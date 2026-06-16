<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventori;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    // Status yang boleh dipilih admin (tidak termasuk status driver)
    private const ADMIN_STATUSES = [
        'pending', 'diproses', 'siap', 'selesai', 'dibatalkan', 'tidak_diambil',
    ];

    // Semua status valid untuk validasi
    private const ALL_STATUSES = [
        'pending', 'diproses', 'siap',
        'mencari_driver', 'driver_menuju_resto', 'tiba_di_resto', 'sedang_mengantar',
        'selesai', 'dibatalkan', 'tidak_diambil',
    ];

    public function index(Request $request)
    {
        $query = Pesanan::with(['user', 'driver', 'detailPesanans'])->latest();

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

        $stats = [
            'pending'        => Pesanan::where('status', 'pending')->count(),
            'diproses'       => Pesanan::where('status', 'diproses')->count(),
            'mencari_driver' => Pesanan::where('status', 'mencari_driver')->count(),
            'perlu_refund'   => Pesanan::where('status_pembayaran', 'lunas')
                                    ->whereIn('status', ['dibatalkan', 'tidak_diambil'])->count(),
            'terlambat'      => Pesanan::where('opsi_pengiriman', 'take_away')
                                    ->where('status', 'siap')
                                    ->whereNotNull('siap_at')
                                    ->whereRaw('TIMESTAMPDIFF(HOUR, siap_at, NOW()) >= 2')
                                    ->count(),
        ];

        return view('admin.pesanan.index', compact('pesanans', 'stats'));
    }

    public function show(Pesanan $pesanan)
    {
        $pesanan->load(['user', 'driver', 'detailPesanans.produk']);
        return view('admin.pesanan.show', compact('pesanan'));
    }

    public function updateStatus(Request $request, Pesanan $pesanan)
    {
        $request->validate([
            'status'             => 'required|in:' . implode(',', self::ADMIN_STATUSES),
            'status_pembayaran'  => 'required|in:belum_dibayar,lunas',
            'catatan_pembatalan' => 'nullable|string|max:500',
        ]);

        $statusLama = $pesanan->status;
        $statusBaru = $request->status;

        // Pengurangan stok otomatis saat diproses
        if ($statusLama !== 'diproses' && $statusBaru === 'diproses') {
            $inventori = Inventori::first();
            if ($inventori) {
                $totalQty = $pesanan->detailPesanans->sum('qty');
                if ($inventori->stok < $totalQty) {
                    return back()->with('error',
                        'Stok tidak mencukupi! Sisa: ' . $inventori->stok .
                        ' ' . $inventori->satuan . ', butuh: ' . $totalQty . ' ' . $inventori->satuan
                    );
                }
                $inventori->kurangiStok($totalQty);
            }
        }

        // Rollback stok jika dari diproses → batal/pending
        if ($statusLama === 'diproses' && in_array($statusBaru, ['pending', 'dibatalkan'])) {
            $inventori = Inventori::first();
            if ($inventori) {
                $inventori->tambahStok($pesanan->detailPesanans->sum('qty'));
            }
        }

        $updateData = [
            'status'            => $statusBaru,
            'status_pembayaran' => $request->status_pembayaran,
        ];

        // Catat waktu siap & otomatis lempar ke pool driver jika delivery
        if ($statusBaru === 'siap' && $statusLama !== 'siap') {
            $updateData['siap_at'] = now();

            // Jika pesanan delivery → langsung set mencari_driver
            if ($pesanan->opsi_pengiriman === 'delivery') {
                $updateData['status'] = 'mencari_driver';
            }
        }

        // Catat catatan pembatalan
        if (in_array($statusBaru, ['dibatalkan', 'tidak_diambil']) && $request->filled('catatan_pembatalan')) {
            $updateData['catatan_pembatalan'] = $request->catatan_pembatalan;
        }

        // Jika admin manual set mencari_driver, izinkan juga
        if ($statusBaru === 'siap' && $pesanan->opsi_pengiriman === 'delivery') {
            // Already handled above — status set to mencari_driver
        }

        $pesanan->update($updateData);

        $finalStatus = $pesanan->fresh()->status;
        $msg = 'Status pesanan berhasil diperbarui!';
        if ($finalStatus === 'mencari_driver') {
            $msg .= ' Pesanan otomatis dimasukkan ke pool driver.';
        }

        return redirect()->route('admin.pesanan.show', $pesanan->id)->with('success', $msg);
    }

    public function verifikasiPembayaran(Pesanan $pesanan)
    {
        if (!$pesanan->bukti_pembayaran) {
            return back()->with('error', 'Pesanan ini tidak memiliki bukti pembayaran.');
        }
        $pesanan->update(['status_pembayaran' => 'lunas']);
        return back()->with('success', 'Pembayaran berhasil diverifikasi! Status diubah menjadi Lunas.');
    }

    public function tandaiTidakDiambil(Pesanan $pesanan)
    {
        if ($pesanan->status !== 'siap' || $pesanan->opsi_pengiriman !== 'take_away') {
            return back()->with('error', 'Pesanan ini tidak dapat ditandai sebagai Tidak Diambil.');
        }
        $pesanan->update([
            'status'             => 'tidak_diambil',
            'catatan_pembatalan' => 'Pesanan tidak diambil oleh customer.',
        ]);
        return back()->with('success', 'Pesanan berhasil ditandai sebagai Tidak Diambil.');
    }
}
