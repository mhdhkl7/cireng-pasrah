<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'driver_id',
        'kode_pesanan',
        'total_harga',
        'ongkir',
        'jarak_meter',
        'opsi_pengiriman',
        'alamat_pengiriman',
        'metode_pembayaran',
        'status_pembayaran',
        'bukti_pembayaran',
        'status',
        'siap_at',
        'diambil_driver_at',
        'catatan',
        'catatan_pembatalan',
    ];

    protected $casts = [
        'total_harga'       => 'decimal:2',
        'ongkir'            => 'decimal:2',
        'jarak_meter'       => 'integer',
        'siap_at'           => 'datetime',
        'diambil_driver_at' => 'datetime',
    ];

    // ─── Relationships ──────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function detailPesanans()
    {
        return $this->hasMany(DetailPesanan::class);
    }

    // ─── Formatted Attributes ────────────────────────────────

    public function getTotalHargaFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }

    public function getOngkirFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->ongkir, 0, ',', '.');
    }

    public function getTotalAkhirAttribute(): float
    {
        return (float) $this->total_harga + (float) $this->ongkir;
    }

    public function getTotalAkhirFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->total_akhir, 0, ',', '.');
    }

    public function getBuktiPembayaranUrlAttribute(): ?string
    {
        return $this->bukti_pembayaran
            ? asset('storage/' . $this->bukti_pembayaran)
            : null;
    }

    // ─── Status Labels & Badges ──────────────────────────────

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'             => 'Menunggu Konfirmasi',
            'diproses'            => 'Sedang Diproses',
            'siap'                => 'Siap Diambil',
            'mencari_driver'      => 'Mencari Driver',
            'driver_menuju_resto' => 'Driver Menuju Resto',
            'tiba_di_resto'       => 'Driver di Resto',
            'sedang_mengantar'    => 'Sedang Diantar',
            'selesai'             => 'Selesai',
            'dibatalkan'          => 'Dibatalkan',
            'tidak_diambil'       => 'Tidak Diambil',
            default               => 'Pending',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending'             => 'badge-pending',
            'diproses'            => 'badge-diproses',
            'siap'                => 'badge-siap',
            'mencari_driver'      => 'badge-mencari',
            'driver_menuju_resto' => 'badge-driver',
            'tiba_di_resto'       => 'badge-driver',
            'sedang_mengantar'    => 'badge-driver',
            'selesai'             => 'badge-selesai',
            'dibatalkan'          => 'badge-dibatalkan',
            'tidak_diambil'       => 'badge-tidak-diambil',
            default               => 'badge-pending',
        };
    }

    // ─── Status Helper Methods ───────────────────────────────

    /** Apakah pesanan ini ada di pool (belum diambil driver)? */
    public function isInDriverPool(): bool
    {
        return $this->status === 'mencari_driver' && is_null($this->driver_id);
    }

    /** Apakah pesanan ini sedang ditangani oleh driver tertentu? */
    public function isHandledByDriver(int $driverId): bool
    {
        return $this->driver_id === $driverId
            && in_array($this->status, [
                'driver_menuju_resto',
                'tiba_di_resto',
                'sedang_mengantar',
            ]);
    }

    /** Apakah driver masih boleh membatalkan pesanan ini? */
    public function canDriverCancel(): bool
    {
        return $this->status === 'driver_menuju_resto';
    }

    /** Apakah pesanan ini COD yang belum dibayar? */
    public function isCodBelumBayar(): bool
    {
        return $this->metode_pembayaran === 'cod'
            && $this->status_pembayaran === 'belum_dibayar';
    }

    /** Apakah pesanan ini perlu refund? (lunas + dibatalkan/tidak_diambil) */
    public function getPerluRefundAttribute(): bool
    {
        return $this->status_pembayaran === 'lunas'
            && in_array($this->status, ['dibatalkan', 'tidak_diambil']);
    }

    /** Apakah take away sudah siap > 2 jam dan belum diambil? */
    public function getTerlambatDiambilAttribute(): bool
    {
        return $this->opsi_pengiriman === 'take_away'
            && $this->status === 'siap'
            && $this->siap_at !== null
            && $this->siap_at->diffInHours(now()) >= 2;
    }

    /** Samarkan alamat: "Jl. Merdeka No. 10" → "Jl. Merd***" */
    public function getMaskedAlamatAttribute(): string
    {
        if (!$this->alamat_pengiriman) return '-';
        $words = explode(' ', $this->alamat_pengiriman);
        $result = [];
        foreach ($words as $i => $word) {
            // Samarkan kata-kata setelah index ke-2 (biarkan 2 kata pertama)
            if ($i >= 2 && strlen($word) > 3) {
                $result[] = substr($word, 0, 4) . '***';
            } else {
                $result[] = $word;
            }
        }
        return implode(' ', $result);
    }

    // ─── Static Helpers ─────────────────────────────────────

    public static function generateKodePesanan(): string
    {
        $prefix = 'CRG';
        $date   = date('Ymd');
        $random = strtoupper(substr(uniqid(), -5));
        return $prefix . $date . $random;
    }
}
