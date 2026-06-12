<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventori;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Statistik utama
        $totalPendapatan = Pesanan::where('status', 'selesai')
            ->where('status_pembayaran', 'lunas')
            ->sum('total_harga');

        $pesananHariIni = Pesanan::whereDate('created_at', $today)->count();

        $stokCireng = Inventori::first()?->stok ?? 0;

        $totalCustomer = User::where('role', 'customer')->count();

        $pesananPending = Pesanan::where('status', 'pending')->count();

        // Transaksi terbaru
        $transaksiTerbaru = Pesanan::with(['user', 'detailPesanans'])
            ->latest()
            ->take(10)
            ->get();

        // Grafik pesanan 7 hari terakhir
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartData[] = [
                'label' => $date->format('d M'),
                'value' => Pesanan::whereDate('created_at', $date)->count(),
            ];
        }

        // Pendapatan per hari (7 hari)
        $pendapatanChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $pendapatanChart[] = [
                'label' => $date->format('d M'),
                'value' => (float) Pesanan::whereDate('created_at', $date)
                    ->where('status_pembayaran', 'lunas')
                    ->sum('total_harga'),
            ];
        }

        return view('admin.dashboard.index', compact(
            'totalPendapatan',
            'pesananHariIni',
            'stokCireng',
            'totalCustomer',
            'pesananPending',
            'transaksiTerbaru',
            'chartData',
            'pendapatanChart'
        ));
    }
}
