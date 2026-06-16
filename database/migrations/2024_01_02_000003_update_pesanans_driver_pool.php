<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Extend enum status dengan status driver pool
        DB::statement("ALTER TABLE pesanans MODIFY COLUMN status
            ENUM(
                'pending',
                'diproses',
                'siap',
                'mencari_driver',
                'driver_menuju_resto',
                'tiba_di_resto',
                'sedang_mengantar',
                'selesai',
                'dibatalkan',
                'tidak_diambil'
            ) NOT NULL DEFAULT 'pending'");

        Schema::table('pesanans', function (Blueprint $table) {
            // 2. Foreign key ke driver (nullable — belum tentu ada driver)
            $table->unsignedBigInteger('driver_id')->nullable()->after('user_id');
            $table->foreign('driver_id')->references('id')->on('users')->nullOnDelete();

            // 3. Timestamp saat driver ambil pesanan
            $table->timestamp('diambil_driver_at')->nullable()->after('siap_at');
        });
    }

    public function down(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            $table->dropForeign(['driver_id']);
            $table->dropColumn(['driver_id', 'diambil_driver_at']);
        });

        DB::statement("ALTER TABLE pesanans MODIFY COLUMN status
            ENUM('pending','diproses','siap','selesai','dibatalkan','tidak_diambil')
            NOT NULL DEFAULT 'pending'");
    }
};
