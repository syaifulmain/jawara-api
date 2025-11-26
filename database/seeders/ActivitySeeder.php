<?php

namespace Database\Seeders;

use App\Models\ActivityModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Pengajian Jumat',
                'category' => 'keagamaan',
                'date' => now()->addDays(7),
                'location' => 'Masjid Agung',
                'person_in_charge' => 'Ustadz Ahmad',
                'description' => 'Pengajian mingguan untuk masyarakat.',
            ],
            [
                'name' => 'Seminar Pendidikan',
                'category' => 'pendidikan',
                'date' => now()->addDays(14),
                'location' => 'Aula Kampus',
                'person_in_charge' => 'Dr. Siti',
                'description' => 'Seminar tentang metode pembelajaran modern.',
            ],
            [
                'name' => 'Bazar Lingkungan',
                'category' => 'lainnya',
                'date' => now()->addDays(21),
                'location' => 'Lapangan RT',
                'person_in_charge' => 'Panitia RT',
                'description' => 'Bazar untuk menggalang dana kegiatan.',
            ],
        ];

        foreach ($data as $item) {
            ActivityModel::create($item);
        }
    }
}
