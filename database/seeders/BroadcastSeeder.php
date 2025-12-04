<?php

namespace Database\Seeders;

use App\Models\BroadcastModel;
use App\Models\User;
use Illuminate\Database\Seeder;

class BroadcastSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if (!$user) {
            $this->command->warn('No user found. Please create a user first.');
            return;
        }

        BroadcastModel::create([
            'title' => 'Pengumuman Penting',
            'message' => 'Ini adalah pengumuman penting untuk semua anggota.',
            'published_at' => now(),
            'created_by' => $user->id,
            // 'photo' => 'https://upload.wikimedia.org/wikipedia/commons/a/a8/Meitei_Houdong_%28Meetei_Houdong%29_-_A_typical_Meitei_domestic_cat_%28Meetei_house_cat%29_01.jpg',
            // 'document' => 'https://mag.wcoomd.org/uploads/2018/05/blank.pdf',
        ]);

        BroadcastModel::create([
            'title' => 'Informasi Kegiatan',
            'message' => 'Informasi mengenai kegiatan yang akan datang.',
            'published_at' => now(),
            'created_by' => $user->id,
            // 'photo' => 'https://upload.wikimedia.org/wikipedia/commons/a/a8/Meitei_Houdong_%28Meetei_Houdong%29_-_A_typical_Meitei_domestic_cat_%28Meetei_house_cat%29_01.jpg',
            // 'document' => 'https://mag.wcoomd.org/uploads/2018/05/blank.pdf',
        ]);
    }
}
