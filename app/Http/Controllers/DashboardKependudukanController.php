<?php

namespace App\Http\Controllers;

use App\Models\ResidentModel;
use App\Models\FamilyModel;
use App\Models\AddressModel;
use Illuminate\Support\Facades\DB;

class DashboardKependudukanController extends Controller
{
    public function index()
    {
        // Total Statistik
        $totalPenduduk = ResidentModel::where('is_alive', true)->count();
        $totalKeluarga = FamilyModel::where('is_active', true)->count();
        $totalRumah = AddressModel::count();
        $rumahDitempati = AddressModel::whereHas('familyHistory', function ($q) {
            $q->whereNull('moved_out_at');
        })->count();
        $rumahKosong = $totalRumah - $rumahDitempati;

        // Distribusi Gender (untuk PieChart)
        $genderStats = ResidentModel::where('is_alive', true)
            ->select('gender', DB::raw('count(*) as total'))
            ->groupBy('gender')
            ->get()
            ->map(function ($item) {
                return [
                    'gender' => $item->gender,
                    'label' => $item->gender === 'M' ? 'Laki-laki' : 'Perempuan',
                    'total' => (int) $item->total,
                ];
            });

        // Distribusi Agama (untuk PieChart)
        $religionStats = ResidentModel::where('is_alive', true)
            ->select('religion', DB::raw('count(*) as total'))
            ->groupBy('religion')
            ->orderByDesc('total')
            ->get()
            ->map(function ($item) {
                return [
                    'religion' => $item->religion,
                    'total' => (int) $item->total,
                ];
            });

        // Distribusi Golongan Darah (untuk BarChart)
        $bloodTypeStats = ResidentModel::where('is_alive', true)
            ->select('blood_type', DB::raw('count(*) as total'))
            ->groupBy('blood_type')
            ->orderByDesc('total')
            ->get()
            ->map(function ($item) {
                return [
                    'blood_type' => $item->blood_type,
                    'total' => (int) $item->total,
                ];
            });

        // Distribusi Usia (untuk BarChart)
        $ageDistribution = $this->getAgeDistribution();

        // Distribusi Pendidikan (untuk BarChart horizontal)
        $educationStats = ResidentModel::where('is_alive', true)
            ->whereNotNull('last_education')
            ->select('last_education', DB::raw('count(*) as total'))
            ->groupBy('last_education')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'education' => $item->last_education,
                    'total' => (int) $item->total,
                ];
            });

        // Distribusi Pekerjaan (untuk BarChart horizontal)
        $occupationStats = ResidentModel::where('is_alive', true)
            ->whereNotNull('occupation')
            ->select('occupation', DB::raw('count(*) as total'))
            ->groupBy('occupation')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'occupation' => $item->occupation,
                    'total' => (int) $item->total,
                ];
            });

        // Status Kepemilikan Rumah (untuk PieChart)
        $ownershipStats = FamilyModel::where('is_active', true)
            ->join('family_address_history', 'families.id', '=', 'family_address_history.family_id')
            ->whereNull('family_address_history.moved_out_at')
            ->select('family_address_history.status', DB::raw('count(*) as total'))
            ->groupBy('family_address_history.status')
            ->get()
            ->map(function ($item) {
                return [
                    'status' => $item->status,
                    'label' => ucfirst($item->status),
                    'total' => (int) $item->total,
                ];
            });

        // Peran dalam Keluarga (untuk BarChart)
        $familyRoleStats = ResidentModel::where('is_alive', true)
            ->select('family_role', DB::raw('count(*) as total'))
            ->groupBy('family_role')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'role' => $item->family_role,
                    'total' => (int) $item->total,
                ];
            });

        return response()->json([
            'success' => true,
            'message' => 'Dashboard kependudukan berhasil dimuat',
            'data' => [
                'summary' => [
                    'total_penduduk' => $totalPenduduk,
                    'total_keluarga' => $totalKeluarga,
                    'total_rumah' => $totalRumah,
                    'rumah_ditempati' => $rumahDitempati,
                    'rumah_kosong' => $rumahKosong,
                    'laki_laki' => (int) ($genderStats->firstWhere('gender', 'M')['total'] ?? 0),
                    'perempuan' => (int) ($genderStats->firstWhere('gender', 'F')['total'] ?? 0),
                ],
                'gender_chart' => $genderStats,
                'religion_chart' => $religionStats,
                'blood_type_chart' => $bloodTypeStats,
                'age_distribution_chart' => $ageDistribution,
                'education_chart' => $educationStats,
                'occupation_chart' => $occupationStats,
                'ownership_chart' => $ownershipStats,
                'family_role_chart' => $familyRoleStats,
            ],
        ]);
    }

    private function getAgeDistribution()
    {
        $residents = ResidentModel::where('is_alive', true)
            ->whereNotNull('birth_date')
            ->get();

        $ageGroups = [
            '0-10' => 0,
            '11-20' => 0,
            '21-30' => 0,
            '31-40' => 0,
            '41-50' => 0,
            '51-60' => 0,
            '60+' => 0,
        ];

        foreach ($residents as $resident) {
            $age = $resident->birth_date->age;

            if ($age <= 10) $ageGroups['0-10']++;
            elseif ($age <= 20) $ageGroups['11-20']++;
            elseif ($age <= 30) $ageGroups['21-30']++;
            elseif ($age <= 40) $ageGroups['31-40']++;
            elseif ($age <= 50) $ageGroups['41-50']++;
            elseif ($age <= 60) $ageGroups['51-60']++;
            else $ageGroups['60+']++;
        }

        return collect($ageGroups)->map(function ($total, $range) {
            return [
                'age_range' => $range,
                'total' => $total,
            ];
        })->values();
    }
}
