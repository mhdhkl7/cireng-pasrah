<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah enum value 'tidak_diambil' ke status
        DB::statement("ALTER TABLE pesanans MODIFY COLUMN status ENUM('pending','diproses','siap','selesai','dibatalkan','tidak_diambil') NOT NULL DEFAULT 'pending'");

        Schema::table('pesanans', function (Blueprint $table) {
            // 2. Timestamp saat pesanan berubah jadi 'siap'
            $table->timestamp('siap_at')->nullable()->after('status');

            // 3. Kolom ongkir & jarak
            $table->decimal('ongkir', 10, 2)->default(0)->after('total_harga');
            $table->integer('jarak_meter')->nullable()->after('ongkir');

            // 4. Catatan pembatalan / refund
            $table->text('catatan_pembatalan')->nullable()->after('catatan');
        });
    }

    public function down(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            $table->dropColumn(['siap_at', 'ongkir', 'jarak_meter', 'catatan_pembatalan']);
        });

        DB::statement("ALTER TABLE pesanans MODIFY COLUMN status ENUM('pending','diproses','siap','selesai','dibatalkan') NOT NULL DEFAULT 'pending'");
    }
};
