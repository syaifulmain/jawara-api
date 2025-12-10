<?php

namespace App\Http\Controllers;

use App\Models\BillModel;
use App\Models\IncomeModel;
use App\Models\PengeluaranModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardKeuanganController extends Controller
{
    public function index(Request $request)
    {
        $year = (int) $request->input('year', now()->year);

        // Total Pemasukan dari Bills yang sudah paid
        $totalPemasukan = BillModel::where('status', 'paid')
            ->whereYear('verified_at', $year)
            ->sum('amount');

        // Total Pemasukan dari Income table
        $totalIncome = IncomeModel::whereYear('date', $year)
            ->sum('amount');

        // Total Pemasukan Keseluruhan
        $totalPemasukanKeseluruhan = $totalPemasukan + $totalIncome;

        // Total Pengeluaran
        $totalPengeluaran = PengeluaranModel::whereYear('tanggal', $year)
            ->sum('nominal');

        // Saldo
        $saldo = $totalPemasukanKeseluruhan - $totalPengeluaran;

        // Grafik Pemasukan vs Pengeluaran per Bulan (untuk fl_chart LineChart/BarChart)
        $chartData = [];
        for ($month = 1; $month <= 12; $month++) {
            $pemasukanBills = BillModel::where('status', 'paid')
                ->whereYear('verified_at', $year)
                ->whereMonth('verified_at', $month)
                ->sum('amount');

            $pemasukanIncome = IncomeModel::whereYear('date', $year)
                ->whereMonth('date', $month)
                ->sum('amount');

            $pengeluaran = PengeluaranModel::whereYear('tanggal', $year)
                ->whereMonth('tanggal', $month)
                ->sum('nominal');

            $chartData[] = [
                'month' => $month,
                'month_name' => date('M', mktime(0, 0, 0, $month, 1)),
                'pemasukan' => (float) ($pemasukanBills + $pemasukanIncome),
                'pengeluaran' => (float) $pengeluaran,
            ];
        }

        // Statistik Status Tagihan (untuk fl_chart PieChart)
        $billsStats = BillModel::whereYear('created_at', $year)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->map(function ($item) {
                return [
                    'status' => $item->status,
                    'label' => $this->getStatusLabel($item->status),
                    'total' => (int) $item->total,
                ];
            });

        // Top 5 Kategori Pengeluaran (untuk fl_chart BarChart)
        $topPengeluaran = PengeluaranModel::whereYear('tanggal', $year)
            ->select('kategori', DB::raw('sum(nominal) as total'))
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'kategori' => $item->kategori,
                    'total' => (float) $item->total,
                ];
            });

        // Pending Bills Count
        $pendingBillsCount = BillModel::where('status', 'pending')
            ->whereYear('created_at', $year)
            ->count();

        // Overdue Bills Count
        $overdueBillsCount = BillModel::where('status', 'overdue')
            ->whereYear('created_at', $year)
            ->count();

        return response()->json([
            'success' => true,
            'message' => 'Dashboard keuangan berhasil dimuat',
            'data' => [
                'year' => $year,
                'summary' => [
                    'total_pemasukan' => (float) $totalPemasukanKeseluruhan,
                    'total_pemasukan_formatted' => 'Rp. ' . number_format($totalPemasukanKeseluruhan, 0, ',', '.'),
                    'total_pengeluaran' => (float) $totalPengeluaran,
                    'total_pengeluaran_formatted' => 'Rp. ' . number_format($totalPengeluaran, 0, ',', '.'),
                    'saldo' => (float) $saldo,
                    'saldo_formatted' => 'Rp. ' . number_format($saldo, 0, ',', '.'),
                    'pending_bills' => $pendingBillsCount,
                    'overdue_bills' => $overdueBillsCount,
                ],
                'monthly_chart' => $chartData,
                'bills_status_chart' => $billsStats,
                'top_pengeluaran_chart' => $topPengeluaran,
            ],
        ]);
    }

    private function getStatusLabel($status)
    {
        return [
            'unpaid' => 'Belum Dibayar',
            'pending' => 'Menunggu Verifikasi',
            'paid' => 'Sudah Dibayar',
            'rejected' => 'Ditolak',
            'overdue' => 'Terlambat',
        ][$status] ?? 'Unknown';
    }
}
