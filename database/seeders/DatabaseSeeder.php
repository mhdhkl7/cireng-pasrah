<?php

namespace Database\Seeders;

use App\Models\Inventori;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // === ADMIN ===
        User::create([
            'name'     => 'Pak Haikal',
            'email'    => 'admin@cireng.shop',
            'password' => Hash::make('password123'),
            'role'     => 'admin',
            'phone'    => '081234567890',
            'address'  => 'Jl. Cireng Raya No. 1, Medan',
        ]);

        // === CUSTOMER SAMPLE ===
        $customers = [
            'Prasetyo Aditama',
            'Aditia Rach',
            'Rehan Pratama Pasaribu',
            'Steephen Parnaehan Situmeang',
            'Richard Pangihutan Simanjuntak',
            'Walrian Sihombing'
        ];

        foreach ($customers as $index => $customerName) {
            $emailName = str_replace(' ', '', strtolower($customerName));
            User::create([
                'name'     => $customerName,
                'email'    => $emailName . '@cireng.shop',
                'password' => Hash::make('password123'),
                'role'     => 'customer',
                'phone'    => '082' . rand(100000000, 999999999),
                'address'  => 'Jl. Customer No. ' . ($index + 1) . ', Medan',
            ]);
        }

        // === PRODUK CIRENG ===
            $produks = [
            [
                'nama'      => 'Cireng Original',
                'deskripsi' => 'Cireng klasik dengan tepung aci pilihan, renyah di luar dan kenyal di dalam. Cocok dinikmati dengan saus kacang pedas.',
                'harga'     => 3000,
            ],
            [
                'nama'      => 'Cireng Isi Ayam Pedas',
                'deskripsi' => 'Cireng premium dengan isian ayam suwir berbumbu pedas gurih. Dijamin bikin ketagihan!',
                'harga'     => 5000,
            ],
            [
                'nama'      => 'Cireng Isi Keju',
                'deskripsi' => 'Perpaduan sempurna cireng renyah dengan isian keju mozzarella yang meleleh. Favorit anak-anak!',
                'harga'     => 5000,
            ],
            [
                'nama'      => 'Cireng Isi Abon',
                'deskripsi' => 'Cireng dengan isian abon sapi pilihan yang gurih dan empuk. Pilihan yang mengenyangkan.',
                'harga'     => 4500,
            ],
            [
                'nama'      => 'Cireng Bumbu Rujak',
                'deskripsi' => 'Cireng original yang disajikan dengan bumbu rujak khas Sunda. Asam, manis, dan pedas!',
                'harga'     => 4000,
            ],
            [
                'nama'      => 'Cireng Isi Daging BBQ',
                'deskripsi' => 'Cireng premium dengan isian daging sapi bumbu BBQ yang lezat dan mengenyangkan.',
                'harga'     => 6000,
            ],
        ];

        foreach ($produks as $data) {
            Produk::create([
                'nama'      => $data['nama'],
                'slug'      => Str::slug($data['nama']),
                'deskripsi' => $data['deskripsi'],
                'harga'     => $data['harga'],
                'gambar'    => null,
                'is_active' => true,
            ]);
        }

        // === INVENTORI ===
        Inventori::create([
            'nama_item'  => 'Cireng Mentah',
            'stok'       => 500,
            'satuan'     => 'pcs',
            'keterangan' => 'Stok cireng mentah siap goreng. Kurangi otomatis saat pesanan diproses.',
        ]);

        Inventori::create([
            'nama_item'  => 'Tepung Aci / Tapioka',
            'stok'       => 25,
            'satuan'     => 'kg',
            'keterangan' => 'Bahan baku utama pembuatan cireng.',
        ]);

        Inventori::create([
            'nama_item'  => 'Minyak Goreng',
            'stok'       => 10,
            'satuan'     => 'liter',
            'keterangan' => 'Minyak goreng untuk menggoreng cireng.',
        ]);

        $this->command->info('✅ Seeder berhasil! Data yang dibuat:');
        $this->command->info('  👤 Admin: admin@cireng.shop | password: password123');
        $this->command->info('  👤 Customers created: ' . count($customers));
        $this->command->info('  🍟 Produk: ' . count($produks) . ' varian cireng');
        $this->command->info('  📦 Inventori: 3 item stok');
    }
}
