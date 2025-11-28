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
        ]);

        BroadcastModel::create([
            'title' => 'Informasi Kegiatan',
            'message' => 'Informasi mengenai kegiatan yang akan datang.',
            'published_at' => now(),
            'created_by' => $user->id,
        ]);
    }
}
