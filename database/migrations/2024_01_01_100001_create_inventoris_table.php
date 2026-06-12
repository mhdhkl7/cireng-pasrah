<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventoris', function (Blueprint $table) {
            $table->id();
            $table->string('nama_item');
            $table->integer('stok')->default(0);
            $table->string('satuan')->default('pcs');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventoris');
    }
};
