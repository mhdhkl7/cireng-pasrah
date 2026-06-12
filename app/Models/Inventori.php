<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventori extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_item',
        'stok',
        'satuan',
        'keterangan',
    ];

    protected $casts = [
        'stok' => 'integer',
    ];

    public function kurangiStok(int $jumlah): bool
    {
        if ($this->stok < $jumlah) {
            return false;
        }
        $this->stok -= $jumlah;
        $this->save();
        return true;
    }

    public function tambahStok(int $jumlah): void
    {
        $this->stok += $jumlah;
        $this->save();
    }
}
