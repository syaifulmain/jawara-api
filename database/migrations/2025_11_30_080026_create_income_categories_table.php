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
        Schema::create('income_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama Iuran
            $table->enum('type', ['bulanan', 'mingguan', 'tahunan', 'sekali_bayar']); // Jenis Iuran
            $table->decimal('nominal', 15, 2); // Nominal
            $table->text('description')->nullable(); // Deskripsi
            $table->foreignId('created_by')->constrained('users'); // Dibuat oleh siapa
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('income_categories');
    }
};
