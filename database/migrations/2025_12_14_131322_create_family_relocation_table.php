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
        Schema::create('family_relocations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('relocation_type');
            $table->date('relocation_date');

            $table->string('reason');

            $table->foreignId('family_id')->constrained('families');

            $table->foreignId('past_address_id')->constrained('addresses');

            $table->foreignId('new_address_id')->nullable()->constrained('addresses');

            $table->foreignId('created_by')->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_relocation');
    }
};
