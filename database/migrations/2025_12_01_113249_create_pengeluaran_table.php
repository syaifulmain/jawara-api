<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengeluaran', function (Blueprint $table) {
            $table->id();

            // Nama pengeluaran 
            $table->string('nama_pengeluaran');

            // Tanggal pengeluaran
            $table->date('tanggal');

            // Kategori
            $table->string('kategori')->default('Lain-lain');

            // Nominal jumlah
            $table->decimal('nominal', 20, 2);

            // Verifikator default 'Admin Jawara'
            $table->string('verifikator')->default('Admin Jawara');

            // Bukti foto pengeluaran (nama file)
            $table->string('bukti_pengeluaran')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluaran');
    }
};
