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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Kode tagihan (IR175458A501)
            $table->foreignId('family_id')->constrained('families'); // FK ke families
            $table->foreignId('income_category_id')->constrained('income_categories'); // FK ke income_categories
            $table->date('periode'); // Periode tagihan
            $table->decimal('amount', 15, 2); // Nominal tagihan
            $table->enum('status', ['unpaid', 'pending', 'paid', 'rejected', 'overdue'])->default('unpaid'); // Status pembayaran
            $table->string('payment_proof')->nullable(); // Bukti pembayaran (file path)
            $table->timestamp('paid_at')->nullable(); // Tanggal user upload bukti bayar
            $table->foreignId('verified_by')->nullable()->constrained('users'); // Admin yang verifikasi/reject
            $table->timestamp('verified_at')->nullable(); // Tanggal verifikasi/reject
            $table->text('rejection_reason')->nullable(); // Alasan ditolak
            $table->foreignId('created_by')->constrained('users'); // Yang membuat tagihan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
