<?php

namespace App\Http\Controllers;

use App\Models\IncomeModel;
use App\Models\PengeluaranModel;
use App\Traits\ApiResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;

class LaporanKeuanganController extends Controller
{
    use ApiResponse;

    public function downloadPdf(Request $request): mixed
    {
        try {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'type' => 'required|in:semua,pemasukan,pengeluaran',
            ]);

            $data = $this->getLaporanData($request);

            $pdf = Pdf::loadView('laporan.keuangan', $data);

            return $pdf->download('laporan-keuangan-' . now()->format('Y-m-d') . '.pdf');
        } catch (Exception $e) {
            return $this->errorResponse('Gagal download laporan', 500, $e->getMessage());
        }
    }

    private function getLaporanData(Request $request): array
    {
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();
        $type = $request->type;

        // Ambil data pemasukan
        $pemasukan = collect();
        if ($type === 'semua' || $type === 'pemasukan') {
            $pemasukan = IncomeModel::whereBetween('date', [$startDate, $endDate])
                ->get()
                ->map(function ($item) {
                    return [
                        'tanggal' => $item->date->format('Y-m-d H:i'),
                        'jenis' => 'Pemasukan',
                        'kategori' => $item->income_type,
                        'deskripsi' => $item->name,
                        'jumlah' => $item->amount,
                        'formatted_jumlah' => '+ Rp' . number_format($item->amount, 0, ',', '.'),
                    ];
                });
        }

        // Ambil data pengeluaran
        $pengeluaran = collect();
        if ($type === 'semua' || $type === 'pengeluaran') {
            $pengeluaran = PengeluaranModel::whereBetween('tanggal', [$startDate, $endDate])
                ->get()
                ->map(function ($item) {
                    return [
                        'tanggal' => $item->tanggal->format('Y-m-d H:i'),
                        'jenis' => 'Pengeluaran',
                        'kategori' => $item->kategori,
                        'deskripsi' => $item->nama_pengeluaran,
                        'jumlah' => $item->nominal,
                        'formatted_jumlah' => '- Rp' . number_format($item->nominal, 0, ',', '.'),
                    ];
                });
        }

        // Gabungkan dan urutkan berdasarkan tanggal
        $transaksi = $pemasukan->merge($pengeluaran)
            ->sortBy('tanggal')
            ->values();

        // Hitung total
        $totalPemasukan = $pemasukan->sum('jumlah');
        $totalPengeluaran = $pengeluaran->sum('jumlah');
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        return [
            'judul' => 'Laporan Keuangan',
            'periode' => [
                'start' => $startDate->format('d F Y'),
                'end' => $endDate->format('d F Y'),
                'text' => $startDate->format('d F Y') . ' sampai ' . $endDate->format('d F Y'),
            ],
            'ringkasan' => [
                'total_pemasukan' => $totalPemasukan,
                'total_pengeluaran' => $totalPengeluaran,
                'saldo_akhir' => $saldoAkhir,
                'formatted_total_pemasukan' => 'Rp' . number_format($totalPemasukan, 0, ',', '.'),
                'formatted_total_pengeluaran' => 'Rp' . number_format($totalPengeluaran, 0, ',', '.'),
                'formatted_saldo_akhir' => 'Rp' . number_format($saldoAkhir, 0, ',', '.'),
            ],
            'transaksi' => $transaksi,
            'dicetak_pada' => Carbon::now()->locale('id')->translatedFormat('l, d F Y H.i'),
        ];
    }


    public function cetakLaporan(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'type' => 'required|in:semua,pemasukan,pengeluaran',
            ]);

            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $type = $request->type;

            // Ambil data pemasukan
            $pemasukan = collect();
            if (in_array($type, ['semua', 'pemasukan'])) {
                $pemasukan = IncomeModel::whereBetween('date', [$startDate, $endDate])
                    ->orderBy('date', 'asc')
                    ->orderBy('created_at', 'asc')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'tanggal' => Carbon::parse($item->date)->format('Y-m-d H:i'),
                            'jenis' => 'Pemasukan',
                            'kategori' => $item->income_type,
                            'deskripsi' => $item->name ?? '-',
                            'jumlah' => (float) $item->amount,
                            'formatted_jumlah' => '+ Rp' . number_format((float) $item->amount, 0, ',', '.'),
                        ];
                    });
            }

            // Ambil data pengeluaran
            $pengeluaran = collect();
            if (in_array($type, ['semua', 'pengeluaran'])) {
                $pengeluaran = PengeluaranModel::whereBetween('tanggal', [$startDate, $endDate])
                    ->orderBy('tanggal', 'asc')
                    ->orderBy('created_at', 'asc')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'tanggal' => Carbon::parse($item->tanggal)->format('Y-m-d H:i'),
                            'jenis' => 'Pengeluaran',
                            'kategori' => $item->kategori,
                            'deskripsi' => $item->nama_pengeluaran ?? '-',
                            'jumlah' => (float) $item->nominal,
                            'formatted_jumlah' => '- Rp' . number_format((float) $item->nominal, 0, ',', '.'),
                        ];
                    });
            }

            // Gabungkan dan urutkan berdasarkan tanggal
            $transaksi = $pemasukan->merge($pengeluaran)
                ->sortBy('tanggal')
                ->values();

            // Hitung total
            $totalPemasukan = $pemasukan->sum('jumlah');
            $totalPengeluaran = $pengeluaran->sum('jumlah');
            $saldoAkhir = $totalPemasukan - $totalPengeluaran;

            return $this->successResponse([
                'judul' => 'Laporan Keuangan',
                'periode' => [
                    'start' => $startDate->locale('id')->translatedFormat('d F Y'),
                    'end' => $endDate->locale('id')->translatedFormat('d F Y'),
                    'text' => $startDate->locale('id')->translatedFormat('d F Y') . ' sampai ' . $endDate->locale('id')->translatedFormat('d F Y'),
                ],
                'ringkasan' => [
                    'total_pemasukan' => $totalPemasukan,
                    'total_pengeluaran' => $totalPengeluaran,
                    'saldo_akhir' => $saldoAkhir,
                    'formatted_total_pemasukan' => 'Rp' . number_format($totalPemasukan, 0, ',', '.'),
                    'formatted_total_pengeluaran' => 'Rp' . number_format($totalPengeluaran, 0, ',', '.'),
                    'formatted_saldo_akhir' => 'Rp' . number_format($saldoAkhir, 0, ',', '.'),
                ],
                'transaksi' => $transaksi,
                'dicetak_pada' => Carbon::now()->locale('id')->translatedFormat('l, d F Y H.i'),
            ], 'Laporan keuangan berhasil dibuat');

        } catch (Exception $e) {
            return $this->errorResponse('Gagal membuat laporan keuangan', 500, $e->getMessage());
        }
    }
}
