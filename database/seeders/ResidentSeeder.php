<?php

namespace Database\Seeders;

use App\Models\AddressModel;
use App\Models\FamilyAddressHistoryModel;
use App\Models\FamilyModel;
use App\Models\ResidentModel;
use App\Models\User;
use Illuminate\Database\Seeder;

class ResidentSeeder extends Seeder
{
    public function run(): void
    {
        // Create addresses
        $addresses = [
            'Jl. Merdeka No. 1',
            'Jl. Sudirman No. 23',
            'Jl. Pahlawan No. 45',
            'Jl. Veteran No. 12',
            'Jl. Diponegoro No. 78',
        ];

        $addressModels = [];
        foreach ($addresses as $address) {
            $addressModels[] = AddressModel::create(['address' => $address]);
        }

        // Create families and residents
        $familiesData = [
            ['name' => 'Keluarga Budi Santoso', 'address_idx' => 0, 'status' => 'owner'],
            ['name' => 'Keluarga Siti Nurhaliza', 'address_idx' => 1, 'status' => 'tenant'],
            ['name' => 'Keluarga Ahmad Dahlan', 'address_idx' => 2, 'status' => 'owner'],
            ['name' => 'Keluarga Dewi Sartika', 'address_idx' => 3, 'status' => 'tenant'],
            ['name' => 'Keluarga Raden Wijaya', 'address_idx' => 4, 'status' => 'owner'],
        ];

        $nikCounter = 1;

        foreach ($familiesData as $idx => $familyData) {
            // Create family
            $family = FamilyModel::create([
                'name' => $familyData['name'],
                'is_active' => true,
            ]);

            // Create family head
            $user = User::create([
                'name' => str_replace('Keluarga ', '', $familyData['name']),
                'email' => 'family' . ($idx + 1) . '@example.com',
                'password' => bcrypt('password'),
                'is_active' => true,
            ]);

            $headResident = ResidentModel::create([
                'user_id' => $user->id,
                'family_id' => $family->id,
                'full_name' => str_replace('Keluarga ', '', $familyData['name']),
                'nik' => '3201' . str_pad($nikCounter++, 12, '0', STR_PAD_LEFT),
                'phone_number' => '0812' . str_pad($idx + 1, 8, '0', STR_PAD_LEFT),
                'birth_place' => 'Jakarta',
                'birth_date' => now()->subYears(rand(30, 50)),
                'gender' => $idx % 2 === 0 ? 'M' : 'F',
                'religion' => 'Islam',
                'blood_type' => ['A', 'B', 'AB', 'O'][rand(0, 3)],
                'family_role' => 'Kepala Keluarga',
                'last_education' => 'S1',
                'occupation' => 'Pegawai Swasta',
                'is_family_head' => true,
                'is_alive' => true,
                'is_active' => true,
            ]);

            // Update family head
            $family->update(['head_resident_id' => $headResident->id]);

            // Create family members
            for ($i = 0; $i < rand(2, 4); $i++) {
                ResidentModel::create([
                    'family_id' => $family->id,
                    'full_name' => 'Anggota ' . ($i + 1) . ' ' . $family->name,
                    'nik' => '3201' . str_pad($nikCounter++, 12, '0', STR_PAD_LEFT),
                    'phone_number' => '0813' . str_pad($nikCounter, 8, '0', STR_PAD_LEFT),
                    'birth_place' => 'Bandung',
                    'birth_date' => now()->subYears(rand(5, 25)),
                    'gender' => rand(0, 1) === 0 ? 'M' : 'F',
                    'religion' => 'Islam',
                    'blood_type' => ['A', 'B', 'AB', 'O'][rand(0, 3)],
                    'family_role' => ['Anak', 'Istri', 'Suami'][rand(0, 2)],
                    'last_education' => ['SD', 'SMP', 'SMA'][rand(0, 2)],
                    'occupation' => ['Pelajar', 'Mahasiswa', 'Pegawai'][rand(0, 2)],
                    'is_family_head' => false,
                    'is_alive' => true,
                    'is_active' => true,
                ]);
            }

            // Create address history
            FamilyAddressHistoryModel::create([
                'family_id' => $family->id,
                'address_id' => $addressModels[$familyData['address_idx']]->id,
                'status' => $familyData['status'],
                'moved_in_at' => now()->subYears(rand(1, 5)),
                'moved_out_at' => null,
            ]);
        }
    }
}
