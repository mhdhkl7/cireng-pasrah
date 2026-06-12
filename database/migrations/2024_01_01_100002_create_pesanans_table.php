<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('kode_pesanan')->unique();
            $table->decimal('total_harga', 12, 2)->default(0);
            $table->enum('opsi_pengiriman', ['take_away', 'delivery'])->default('take_away');
            $table->text('alamat_pengiriman')->nullable();
            $table->enum('metode_pembayaran', ['cash', 'transfer'])->default('cash');
            $table->enum('status_pembayaran', ['belum_dibayar', 'lunas'])->default('belum_dibayar');
            $table->string('bukti_pembayaran')->nullable();
            $table->enum('status', ['pending', 'diproses', 'siap', 'selesai', 'dibatalkan'])->default('pending');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
