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
        Schema::table('family_relocations', function (Blueprint $table) {
            $table->dropForeign(['past_address_id']);
            $table->unsignedBigInteger('past_address_id')->nullable()->change();
            $table->foreign('past_address_id')->references('id')->on('addresses')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('family_relocations', function (Blueprint $table) {
            //
        });
    }
};
