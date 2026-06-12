<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kode_pesanan',
        'total_harga',
        'opsi_pengiriman',
        'alamat_pengiriman',
        'metode_pembayaran',
        'status_pembayaran',
        'bukti_pembayaran',
        'status',
        'catatan',
    ];

    protected $casts = [
        'total_harga' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detailPesanans()
    {
        return $this->hasMany(DetailPesanan::class);
    }

    public function getTotalHargaFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending'     => 'status-pending',
            'diproses'    => 'status-diproses',
            'siap'        => 'status-siap',
            'selesai'     => 'status-selesai',
            'dibatalkan'  => 'status-dibatalkan',
            default       => 'status-pending',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'     => 'Menunggu Konfirmasi',
            'diproses'    => 'Sedang Diproses',
            'siap'        => 'Siap Diambil/Dikirim',
            'selesai'     => 'Selesai',
            'dibatalkan'  => 'Dibatalkan',
            default       => 'Pending',
        };
    }

    public function getBuktiPembayaranUrlAttribute(): ?string
    {
        if ($this->bukti_pembayaran) {
            return asset('storage/' . $this->bukti_pembayaran);
        }
        return null;
    }

    public static function generateKodePesanan(): string
    {
        $prefix = 'CRG';
        $date = date('Ymd');
        $random = strtoupper(substr(uniqid(), -5));
        return $prefix . $date . $random;
    }
}
