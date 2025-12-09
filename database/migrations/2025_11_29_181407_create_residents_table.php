<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('residents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('family_id')->constrained('families');
            $table->string('full_name', 150);
            $table->string('nik', 16)->unique();
            $table->string('phone_number', 20)->nullable();
            $table->string('birth_place', 100)->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['M', 'F']);
            $table->enum('religion', ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghuchu', 'Lainnya'])->nullable();
            $table->enum('blood_type', ['A', 'B', 'AB', 'O', 'Tidak Tahu'])->nullable();
            $table->string('family_role', 50);
            $table->string('last_education', 100)->nullable();
            $table->string('occupation', 100)->nullable();
            $table->boolean('is_family_head')->default(false);
            $table->boolean('is_alive')->default(true);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residents');
    }
};
