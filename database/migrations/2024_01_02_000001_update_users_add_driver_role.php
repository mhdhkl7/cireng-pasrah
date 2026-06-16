<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite tidak support modifikasi enum langsung, gunakan raw SQL
        // Untuk MySQL:
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('customer', 'admin', 'driver') NOT NULL DEFAULT 'customer'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('customer', 'admin') NOT NULL DEFAULT 'customer'");
    }
};
