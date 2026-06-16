<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;

class DashboardController extends Controller
{
    public function index()
    {
        $driverId = auth()->id();

        // Pool terbuka: semua pesanan delivery yang belum diambil driver manapun
        $poolPesanan = Pesanan::where('status', 'mencari_driver')
            ->whereNull('driver_id')
            ->with(['user', 'detailPesanans'])
            ->latest()
            ->get();

        // Pesanan aktif saya: yang sedang saya tangani
        $pesananAktif = Pesanan::where('driver_id', $driverId)
            ->whereIn('status', [
                'driver_menuju_resto',
                'tiba_di_resto',
                'sedang_mengantar',
            ])
            ->with(['user', 'detailPesanans'])
            ->latest()
            ->get();

        // Riwayat selesai saya (10 terbaru)
        $pesananSelesai = Pesanan::where('driver_id', $driverId)
            ->where('status', 'selesai')
            ->latest()
            ->limit(10)
            ->get();

        $stats = [
            'pool'           => $poolPesanan->count(),
            'aktif'          => $pesananAktif->count(),
            'selesai_hari_ini' => Pesanan::where('driver_id', $driverId)
                ->where('status', 'selesai')
                ->whereDate('updated_at', today())
                ->count(),
        ];

        return view('driver.dashboard.index', compact(
            'poolPesanan', 'pesananAktif', 'pesananSelesai', 'stats'
        ));
    }
}
