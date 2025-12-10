<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AspirasiModel;
use App\Models\User;
use Carbon\Carbon;

class AspirasiSeeder extends Seeder
{
    public function run()
    {
        // Data aspirasi umum dari berbagai user
        AspirasiModel::create([
            'user_id'    => 1,
            'title'      => 'Perbaikan Fasilitas Jalan',
            'message'    => 'Jalan di RW 04 banyak berlubang dan membutuhkan perbaikan segera.',
            'status'     => 'pending',
            'attachments' => [
                'foto_jalan_1.jpg',
                'foto_jalan_2.jpg'
            ],
            'created_at' => '2025-11-30 10:00:00',
            'updated_at' => '2025-11-30 10:00:00',
        ]);

        AspirasiModel::create([
            'user_id'    => 2,
            'title'      => 'Lampu Penerangan Umum Mati',
            'message'    => 'Beberapa lampu penerangan mati.',
            'status'     => 'approved',
            'attachments' => ['lampu_mati.jpg'],
            'created_at' => '2025-11-20 14:30:00',
            'updated_at' => '2025-11-20 14:30:00',
        ]);

        AspirasiModel::create([
            'user_id'    => 3,
            'title'      => 'Masalah Sampah',
            'message'    => 'Sampah menumpuk di sekitar taman RT03.',
            'status'     => 'rejected',
            'attachments' => null,
            'created_at' => '2025-10-01 08:15:00',
            'updated_at' => '2025-10-01 08:15:00',
        ]);

        AspirasiModel::create([
            'user_id'    => 1,
            'title'      => 'Pengajuan Perbaikan Saluran Air',
            'message'    => 'Saat hujan saluran tidak berfungsi.',
            'status'     => 'pending',
            'attachments' => ['saluran_air.jpg'],
            'created_at' => '2025-12-07 09:45:00',
            'updated_at' => '2025-12-07 09:45:00',
        ]);

        AspirasiModel::create([
            'user_id'    => 2,
            'title'      => 'Permintaan Tempat Sampah',
            'message'    => 'Mohon penambahan tempat sampah.',
            'status'     => 'approved',
            'attachments' => null,
            'created_at' => '2025-12-09 16:20:00',
            'updated_at' => '2025-12-09 16:20:00',
        ]);

        // Riwayat aspirasi khusus untuk user@example.com
        $userSpecific = User::where('email', 'user@example.com')->first();
        if (! $userSpecific) {
            $userSpecific = User::first();
        }

        $riwayatSamples = [
            ['title' => 'Laporan Kebersihan Lingkungan', 'message' => 'Area dekat pos ronda dipenuhi sampah, mohon penanganan.','status' => 'pending'],
            ['title' => 'Permintaan Perbaikan PJU', 'message' => 'Lampu jalan di RT05 sering mati pada malam hari.','status' => 'approved'],
            ['title' => 'Usulan Pengecatan Marka Jalan', 'message' => 'Marka zebra cross sudah pudar, perlu pengecatan ulang.','status' => 'pending'],
            ['title' => 'Keluhan Saluran Tersumbat', 'message' => 'Air menggenang di depan rumah setelah hujan deras.','status' => 'rejected'],
            ['title' => 'Permintaan Penambahan TPS', 'message' => 'Butuh tempat sampah di dekat taman bermain anak-anak.','status' => 'approved'],
        ];

        foreach ($riwayatSamples as $i => $s) {
            AspirasiModel::create([
                'user_id' => $userSpecific->id,
                'title' => $s['title'],
                'message' => $s['message'],
                'status' => $s['status'],
                'attachments' => null,
                'created_at' => Carbon::now()->subDays(5 - $i)->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->subDays(5 - $i)->format('Y-m-d H:i:s'),
            ]);
        }
    }
}
