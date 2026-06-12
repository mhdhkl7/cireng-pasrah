<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Produk extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'slug',
        'deskripsi',
        'harga',
        'gambar',
        'is_active',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($produk) {
            if (empty($produk->slug)) {
                $produk->slug = Str::slug($produk->nama);
            }
        });

        static::updating(function ($produk) {
            if ($produk->isDirty('nama')) {
                $produk->slug = Str::slug($produk->nama);
            }
        });
    }

    public function detailPesanans()
    {
        return $this->hasMany(DetailPesanan::class);
    }

    public function getHargaFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    public function getGambarUrlAttribute(): string
    {
        if ($this->gambar) {
            return asset($this->gambar);
        }
        return asset('images/no-image.png');
    }
}
