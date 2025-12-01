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
        Schema::create('t_pengeluaran', function (Blueprint $table) {
            $table->id();

            // Jenis pengeluaran 
            $table->string('jenis_pengeluaran');

            // Nama pengeluaran 
            $table->string('nama_pengeluaran');

            // Kategori 
            $table->string('kategori')->default('Lain-lain');

            // Tanggal pengeluaran
            $table->date('tanggal');

            // Nominal / jumlah 
            $table->decimal('nominal', 20, 2);

            // Verifikator 
            $table->string('verifikator')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_pengeluaran');
    }
};
