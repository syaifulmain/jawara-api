<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class PengeluaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // Kategori: Operasional RT/RW
            [
                'nama_pengeluaran'  => 'Pembelian ATK',
                'tanggal'           => '2025-01-01',
                'nominal'           => 500000,
                'kategori'          => 'Operasional RT/RW',
                'verifikator'       => 'Admin Jawara',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'nama_pengeluaran'  => 'Biaya Listrik Kantor RT',
                'tanggal'           => '2025-01-03',
                'nominal'           => 750000,
                'kategori'          => 'Operasional RT/RW',
                'verifikator'       => 'Admin Jawara',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'nama_pengeluaran'  => 'Pembelian Kertas Formulir',
                'tanggal'           => '2025-01-05',
                'nominal'           => 300000,
                'kategori'          => 'Operasional RT/RW',
                'verifikator'       => 'Admin Jawara',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'nama_pengeluaran'  => 'Biaya Internet Kantor RT',
                'tanggal'           => '2025-01-07',
                'nominal'           => 250000,
                'kategori'          => 'Operasional RT/RW',
                'verifikator'       => 'Admin Jawara',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'nama_pengeluaran'  => 'Alat Kebersihan Kantor RT',
                'tanggal'           => '2025-01-09',
                'nominal'           => 400000,
                'kategori'          => 'Operasional RT/RW',
                'verifikator'       => 'Admin Jawara',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],

            // Kategori: Kegiatan Sosial
            [
                'nama_pengeluaran'  => 'Bantuan Sembako Warga',
                'tanggal'           => '2025-02-01',
                'nominal'           => 2000000,
                'kategori'          => 'Kegiatan Sosial',
                'verifikator'       => 'Admin Jawara',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'nama_pengeluaran'  => 'Donasi Panti Asuhan',
                'tanggal'           => '2025-02-03',
                'nominal'           => 1500000,
                'kategori'          => 'Kegiatan Sosial',
                'verifikator'       => 'Admin Jawara',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'nama_pengeluaran'  => 'Pembelian Obat-obatan Warga',
                'tanggal'           => '2025-02-05',
                'nominal'           => 800000,
                'kategori'          => 'Kegiatan Sosial',
                'verifikator'       => 'Admin Jawara',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'nama_pengeluaran'  => 'Acara Santunan Anak Yatim',
                'tanggal'           => '2025-02-07',
                'nominal'           => 1200000,
                'kategori'          => 'Kegiatan Sosial',
                'verifikator'       => 'Admin Jawara',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'nama_pengeluaran'  => 'Kegiatan Bakti Sosial Lingkungan',
                'tanggal'           => '2025-02-09',
                'nominal'           => 1000000,
                'kategori'          => 'Kegiatan Sosial',
                'verifikator'       => 'Admin Jawara',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],

            // Kategori: Pemeliharaan Fasilitas
            [
                'nama_pengeluaran'  => 'Perbaikan Lampu Jalan',
                'tanggal'           => '2025-03-01',
                'nominal'           => 500000,
                'kategori'          => 'Pemeliharaan Fasilitas',
                'verifikator'       => 'Admin Jawara',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'nama_pengeluaran'  => 'Pengecatan Gedung RT',
                'tanggal'           => '2025-03-03',
                'nominal'           => 1000000,
                'kategori'          => 'Pemeliharaan Fasilitas',
                'verifikator'       => 'Admin Jawara',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'nama_pengeluaran'  => 'Perbaikan Pagar Lingkungan',
                'tanggal'           => '2025-03-05',
                'nominal'           => 750000,
                'kategori'          => 'Pemeliharaan Fasilitas',
                'verifikator'       => 'Admin Jawara',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'nama_pengeluaran'  => 'Servis AC Kantor RT',
                'tanggal'           => '2025-03-07',
                'nominal'           => 600000,
                'kategori'          => 'Pemeliharaan Fasilitas',
                'verifikator'       => 'Admin Jawara',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'nama_pengeluaran'  => 'Pembersihan Saluran Air Lingkungan',
                'tanggal'           => '2025-03-09',
                'nominal'           => 400000,
                'kategori'          => 'Pemeliharaan Fasilitas',
                'verifikator'       => 'Admin Jawara',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],

            // Kategori: Pembangunan
            [
                'nama_pengeluaran'  => 'Pembangunan Mushola',
                'tanggal'           => '2025-04-01',
                'nominal'           => 5000000,
                'kategori'          => 'Pembangunan',
                'verifikator'       => 'Admin Jawara',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'nama_pengeluaran'  => 'Pembangunan Pos Keamanan',
                'tanggal'           => '2025-04-03',
                'nominal'           => 3500000,
                'kategori'          => 'Pembangunan',
                'verifikator'       => 'Admin Jawara',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'nama_pengeluaran'  => 'Renovasi Balai Warga',
                'tanggal'           => '2025-04-05',
                'nominal'           => 4200000,
                'kategori'          => 'Pembangunan',
                'verifikator'       => 'Admin Jawara',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'nama_pengeluaran'  => 'Pembangunan Taman Lingkungan',
                'tanggal'           => '2025-04-07',
                'nominal'           => 2800000,
                'kategori'          => 'Pembangunan',
                'verifikator'       => 'Admin Jawara',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'nama_pengeluaran'  => 'Pembangunan Tempat Sampah Terpadu',
                'tanggal'           => '2025-04-09',
                'nominal'           => 1500000,
                'kategori'          => 'Pembangunan',
                'verifikator'       => 'Admin Jawara',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],

            // Kategori: Kegiatan Warga
            [
                'nama_pengeluaran'  => 'Perayaan HUT RT',
                'tanggal'           => '2025-05-01',
                'nominal'           => 1200000,
                'kategori'          => 'Kegiatan Warga',
                'verifikator'       => 'Admin Jawara',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'nama_pengeluaran'  => 'Gotong Royong Membersihkan Lingkungan',
                'tanggal'           => '2025-05-03',
                'nominal'           => 800000,
                'kategori'          => 'Kegiatan Warga',
                'verifikator'       => 'Admin Jawara',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'nama_pengeluaran'  => 'Acara Bazar Lingkungan',
                'tanggal'           => '2025-05-05',
                'nominal'           => 1000000,
                'kategori'          => 'Kegiatan Warga',
                'verifikator'       => 'Admin Jawara',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'nama_pengeluaran'  => 'Olahraga Bersama Warga',
                'tanggal'           => '2025-05-07',
                'nominal'           => 700000,
                'kategori'          => 'Kegiatan Warga',
                'verifikator'       => 'Admin Jawara',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'nama_pengeluaran'  => 'Acara Ramah Tamah Warga',
                'tanggal'           => '2025-05-09',
                'nominal'           => 600000,
                'kategori'          => 'Kegiatan Warga',
                'verifikator'       => 'Admin Jawara',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],

            // Kategori: Keamanan & Kebersihan
            [
                'nama_pengeluaran'  => 'Patroli Malam RT',
                'tanggal'           => '2025-06-01',
                'nominal'           => 500000,
                'kategori'          => 'Keamanan & Kebersihan',
                'verifikator'       => 'Admin Jawara',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'nama_pengeluaran'  => 'Pembersihan Saluran Air',
                'tanggal'           => '2025-06-03',
                'nominal'           => 300000,
                'kategori'          => 'Keamanan & Kebersihan',
                'verifikator'       => 'Admin Jawara',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'nama_pengeluaran'  => 'Pengecatan Rambu Lalu Lintas Lingkungan',
                'tanggal'           => '2025-06-05',
                'nominal'           => 450000,
                'kategori'          => 'Keamanan & Kebersihan',
                'verifikator'       => 'Admin Jawara',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'nama_pengeluaran'  => 'Pembelian APD Kebersihan',
                'tanggal'           => '2025-06-07',
                'nominal'           => 250000,
                'kategori'          => 'Keamanan & Kebersihan',
                'verifikator'       => 'Admin Jawara',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'nama_pengeluaran'  => 'Pemasangan CCTV Lingkungan',
                'tanggal'           => '2025-06-09',
                'nominal'           => 1200000,
                'kategori'          => 'Keamanan & Kebersihan',
                'verifikator'       => 'Admin Jawara',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],

            // Kategori: Lain-lain
            [
                'nama_pengeluaran'  => 'Hadiah Lomba Lingkungan',
                'tanggal'           => '2025-07-01',
                'nominal'           => 400000,
                'kategori'          => 'Lain-lain',
                'verifikator'       => 'Admin Jawara',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'nama_pengeluaran'  => 'Biaya Administrasi Lainnya',
                'tanggal'           => '2025-07-03',
                'nominal'           => 300000,
                'kategori'          => 'Lain-lain',
                'verifikator'       => 'Admin Jawara',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'nama_pengeluaran'  => 'Pengiriman Surat Undangan',
                'tanggal'           => '2025-07-05',
                'nominal'           => 200000,
                'kategori'          => 'Lain-lain',
                'verifikator'       => 'Admin Jawara',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'nama_pengeluaran'  => 'Cetak Banner Kegiatan',
                'tanggal'           => '2025-07-07',
                'nominal'           => 350000,
                'kategori'          => 'Lain-lain',
                'verifikator'       => 'Admin Jawara',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'nama_pengeluaran'  => 'Biaya Lain-lain Tak Terduga',
                'tanggal'           => '2025-07-09',
                'nominal'           => 500000,
                'kategori'          => 'Lain-lain',
                'verifikator'       => 'Admin Jawara',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
        ];

        DB::table('pengeluaran')->insert($data);
    }
}
