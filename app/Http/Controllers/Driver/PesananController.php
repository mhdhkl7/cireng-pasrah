<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    /** Daftar pesanan milik driver yang login (aktif + selesai) */
    public function index()
    {
        $driverId = auth()->id();

        $pesanans = Pesanan::where('driver_id', $driverId)
            ->with(['user', 'detailPesanans'])
            ->latest()
            ->paginate(20);

        return view('driver.pesanan.index', compact('pesanans'));
    }

    /** Detail satu pesanan */
    public function show(Pesanan $pesanan)
    {
        // Driver hanya bisa lihat pesanannya sendiri atau yang ada di pool
        if ($pesanan->driver_id && $pesanan->driver_id !== auth()->id()) {
            abort(403, 'Pesanan ini bukan milik Anda.');
        }

        $pesanan->load(['user', 'detailPesanans.produk']);
        return view('driver.pesanan.show', compact('pesanan'));
    }

    /**
     * Driver ambil pesanan dari pool.
     * Syarat: status = mencari_driver, driver_id masih null.
     */
    public function ambil(Pesanan $pesanan)
    {
        if ($pesanan->status !== 'mencari_driver' || !is_null($pesanan->driver_id)) {
            return back()->with('error', 'Pesanan ini sudah diambil oleh driver lain atau tidak tersedia.');
        }

        $pesanan->update([
            'driver_id'          => auth()->id(),
            'status'             => 'driver_menuju_resto',
            'diambil_driver_at'  => now(),
        ]);

        return redirect()->route('driver.pesanan.show', $pesanan->id)
            ->with('success', 'Pesanan ' . $pesanan->kode_pesanan . ' berhasil diambil! Segera menuju toko.');
    }

    /**
     * Update status pesanan secara bertahap oleh driver.
     * Flow: driver_menuju_resto → tiba_di_resto → sedang_mengantar → selesai
     */
    public function updateStatus(Request $request, Pesanan $pesanan)
    {
        if ($pesanan->driver_id !== auth()->id()) {
            abort(403, 'Pesanan ini bukan milik Anda.');
        }

        $allowedTransitions = [
            'driver_menuju_resto' => 'tiba_di_resto',
            'tiba_di_resto'       => 'sedang_mengantar',
            'sedang_mengantar'    => 'selesai',
        ];

        $statusBaru = $allowedTransitions[$pesanan->status] ?? null;

        if (!$statusBaru) {
            return back()->with('error', 'Status pesanan tidak dapat diperbarui lagi.');
        }

        $updateData = ['status' => $statusBaru];

        // Jika selesai dan COD → tandai lunas sekaligus
        if ($statusBaru === 'selesai' && $pesanan->isCodBelumBayar()) {
            $updateData['status_pembayaran'] = 'lunas';
        }

        $pesanan->update($updateData);

        $labels = [
            'tiba_di_resto'    => 'Anda sudah tiba di toko!',
            'sedang_mengantar' => 'Pesanan sedang dalam perjalanan!',
            'selesai'          => 'Pesanan berhasil diantarkan! 🎉',
        ];

        return back()->with('success', $labels[$statusBaru] ?? 'Status diperbarui.');
    }

    /**
     * Driver terima pembayaran tunai (COD) saat sedang_mengantar.
     * Tandai status_pembayaran = lunas, status = selesai.
     */
    public function terimaTunai(Pesanan $pesanan)
    {
        if ($pesanan->driver_id !== auth()->id()) {
            abort(403, 'Pesanan ini bukan milik Anda.');
        }

        if ($pesanan->status !== 'sedang_mengantar') {
            return back()->with('error', 'Pembayaran tunai hanya bisa diterima saat status "Sedang Mengantar".');
        }

        if (!$pesanan->isCodBelumBayar()) {
            return back()->with('error', 'Pesanan ini bukan COD atau sudah lunas.');
        }

        $pesanan->update([
            'status'            => 'selesai',
            'status_pembayaran' => 'lunas',
        ]);

        return redirect()->route('driver.dashboard')
            ->with('success', 'Pembayaran tunai diterima! Pesanan ' . $pesanan->kode_pesanan . ' selesai.');
    }

    /**
     * Driver batalkan pesanan.
     * HANYA boleh saat status = driver_menuju_resto.
     */
    public function batal(Pesanan $pesanan)
    {
        if ($pesanan->driver_id !== auth()->id()) {
            abort(403, 'Pesanan ini bukan milik Anda.');
        }

        if (!$pesanan->canDriverCancel()) {
            return back()->with('error', 'Anda sudah tidak dapat membatalkan pesanan ini.');
        }

        // Kembalikan pesanan ke pool supaya driver lain bisa ambil
        $pesanan->update([
            'status'             => 'mencari_driver',
            'driver_id'          => null,
            'diambil_driver_at'  => null,
            'catatan_pembatalan' => 'Driver membatalkan: kembali ke pool.',
        ]);

        return redirect()->route('driver.dashboard')
            ->with('warning', 'Pesanan dibatalkan dan dikembalikan ke pool driver.');
    }
}
