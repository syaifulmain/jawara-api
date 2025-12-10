<?php

namespace App\Http\Controllers;

use App\Models\ActivityModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardKegiatanController extends Controller
{
    public function index(Request $request)
    {
        $year = (int) $request->input('year', now()->year);
        $today = Carbon::today();

        // Total kegiatan berdasarkan kategori (untuk PieChart)
        $categoryStats = ActivityModel::whereYear('date', $year)
            ->select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->get()
            ->map(function ($item) {
                return [
                    'category' => $item->category,
                    'label' => $this->getCategoryLabel($item->category),
                    'total' => (int) $item->total,
                ];
            });

        // Total kegiatan
        $totalKegiatan = ActivityModel::whereYear('date', $year)->count();

        // Kegiatan per bulan (untuk LineChart/BarChart)
        $monthlyChart = [];
        for ($month = 1; $month <= 12; $month++) {
            $keagamaan = ActivityModel::where('category', 'keagamaan')
                ->whereYear('date', $year)
                ->whereMonth('date', $month)
                ->count();

            $pendidikan = ActivityModel::where('category', 'pendidikan')
                ->whereYear('date', $year)
                ->whereMonth('date', $month)
                ->count();

            $lainnya = ActivityModel::where('category', 'lainnya')
                ->whereYear('date', $year)
                ->whereMonth('date', $month)
                ->count();

            $monthlyChart[] = [
                'month' => $month,
                'month_name' => date('M', mktime(0, 0, 0, $month, 1)),
                'keagamaan' => $keagamaan,
                'pendidikan' => $pendidikan,
                'lainnya' => $lainnya,
                'total' => $keagamaan + $pendidikan + $lainnya,
            ];
        }

        // Kegiatan bulan ini
        $kegiatanBulanIni = ActivityModel::whereYear('date', $year)
            ->whereMonth('date', now()->month)
            ->count();

        // Kegiatan mendatang (upcoming)
        $upcomingActivities = ActivityModel::where('date', '>=', now())
            ->orderBy('date', 'asc')
            ->limit(5)
            ->get()
            ->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'name' => $activity->name,
                    'category' => $activity->category,
                    'category_label' => $this->getCategoryLabel($activity->category),
                    'date' => optional($activity->date)->toIso8601String(),
                    'location' => $activity->location,
                    'person_in_charge' => $activity->person_in_charge,
                ];
            });

        // Kegiatan berdasarkan waktu
        $sudahLewat = ActivityModel::whereYear('date', $year)
            ->where('date', '<', $today)
            ->count();

        $hariIni = ActivityModel::whereDate('date', $today)
            ->count();

        $akanDatang = ActivityModel::whereYear('date', $year)
            ->where('date', '>', $today)
            ->count();

        // Top 5 lokasi kegiatan (untuk BarChart)
        $topLocations = ActivityModel::whereYear('date', $year)
            ->select('location', DB::raw('count(*) as total'))
            ->groupBy('location')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'location' => $item->location,
                    'total' => (int) $item->total,
                ];
            });

        // Top 5 penanggung jawab (untuk BarChart)
        $topPersonInCharge = ActivityModel::whereYear('date', $year)
            ->select('person_in_charge', DB::raw('count(*) as total'))
            ->groupBy('person_in_charge')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'person_in_charge' => $item->person_in_charge,
                    'total' => (int) $item->total,
                ];
            });

        return response()->json([
            'success' => true,
            'message' => 'Dashboard kegiatan berhasil dimuat',
            'data' => [
                'year' => $year,
                'summary' => [
                    'total_kegiatan' => $totalKegiatan,
                    'kegiatan_bulan_ini' => $kegiatanBulanIni,
                    'sudah_lewat' => $sudahLewat,
                    'hari_ini' => $hariIni,
                    'akan_datang' => $akanDatang,
                ],
                'category_chart' => $categoryStats,
                'monthly_chart' => $monthlyChart,
                'top_locations_chart' => $topLocations,
                'top_person_in_charge_chart' => $topPersonInCharge,
                'upcoming_activities' => $upcomingActivities,
            ],
        ]);
    }

    private function getCategoryLabel($category)
    {
        return [
            'keagamaan' => 'Keagamaan',
            'pendidikan' => 'Pendidikan',
            'lainnya' => 'Lainnya',
        ][$category] ?? 'Lainnya';
    }
}
