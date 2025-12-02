<?php

namespace Database\Seeders;

use App\Models\IncomeCategory;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IncomeCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin user for created_by
        $adminUser = User::where('role', 'admin')->first();
        
        if (!$adminUser) {
            $this->command->info('Admin user not found. Creating default admin user.');
            $adminUser = User::create([
                'name' => 'Admin System',
                'email' => 'admin@system.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]);
        }

        $categories = [
            [
                'name' => 'Iuran Bulanan RT 03',
                'type' => 'bulanan',
                'nominal' => 25000,
                'description' => 'Iuran rutin bulanan untuk kegiatan RT 03',
                'created_by' => $adminUser->id,
            ],
            [
                'name' => 'Iuran Kebersihan Mingguan',
                'type' => 'mingguan', 
                'nominal' => 5000,
                'description' => 'Iuran mingguan untuk kebersihan lingkungan',
                'created_by' => $adminUser->id,
            ],
            [
                'name' => 'Iuran Keamanan Tahunan',
                'type' => 'tahunan',
                'nominal' => 300000,
                'description' => 'Iuran tahunan untuk biaya keamanan lingkungan',
                'created_by' => $adminUser->id,
            ],
            [
                'name' => 'Dana Pembangunan Masjid',
                'type' => 'sekali_bayar',
                'nominal' => 100000,
                'description' => 'Dana sumbangan untuk pembangunan masjid baru',
                'created_by' => $adminUser->id,
            ],
            [
                'name' => 'Iuran HUT RI',
                'type' => 'tahunan',
                'nominal' => 50000,
                'description' => 'Iuran untuk perayaan HUT Kemerdekaan Indonesia',
                'created_by' => $adminUser->id,
            ],
        ];

        foreach ($categories as $category) {
            IncomeCategory::create($category);
        }

        $this->command->info('Income categories seeded successfully.');
    }
}
