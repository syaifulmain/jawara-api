<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class IncomeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('income')->insert([
            [
                'name' => 'Penjualan Produk A',
                'income_type' => 'Penjualan',
                'date' => '2025-01-05',
                'amount' => 1500000.00,
                'date_verification' => '2025-01-06',
                'verification' => 'Verified',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Pendapatan Sewa',
                'income_type' => 'Sewa',
                'date' => '2025-01-08',
                'amount' => 2500000.00,
                'date_verification' => null,
                'verification' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Penjualan Produk B',
                'income_type' => 'Penjualan',
                'date' => '2025-02-01',
                'amount' => 1850000.00,
                'date_verification' => '2025-02-02',
                'verification' => 'Verified',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
