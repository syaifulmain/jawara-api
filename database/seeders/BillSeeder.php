<?php

namespace Database\Seeders;

use App\Models\BillModel;
use App\Models\IncomeCategoryModel;
use App\Models\FamilyModel;
use App\Models\User;
use Illuminate\Database\Seeder;

class BillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing data
        $families = FamilyModel::where('is_active', true)->get();
        $categories = IncomeCategoryModel::get();
        $admin = User::where('role', 'admin')->first();

        if ($families->isEmpty() || $categories->isEmpty()) {
            $this->command->warn('Please run FamilySeeder and IncomeCategorySeeder first');
            return;
        }

        $statuses = ['unpaid', 'pending', 'paid', 'rejected', 'overdue'];
        $currentPeriode = now();

        // Generate bills for last 3 months
        for ($i = 0; $i < 3; $i++) {
            $periode = $currentPeriode->copy()->subMonths($i);
            $codePrefix = 'IR' . $periode->format('Ym');

            foreach ($families as $family) {
                foreach ($categories as $category) {
                    // Skip some bills randomly to make it realistic
                    if (rand(1, 10) > 8) continue;

                    // Distribute status realistically
                    $random = rand(1, 100);
                    if ($random <= 40) {
                        $status = 'paid';      // 40% paid
                    } elseif ($random <= 55) {
                        $status = 'unpaid';    // 15% unpaid
                    } elseif ($random <= 70) {
                        $status = 'pending';   // 15% pending
                    } elseif ($random <= 85) {
                        $status = 'overdue';   // 15% overdue
                    } else {
                        $status = 'rejected';  // 15% rejected
                    }
                    
                    $codeNumber = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
                    
                    $billData = [
                        'code' => $codePrefix . $codeNumber,
                        'family_id' => $family->id,
                        'income_category_id' => $category->id,
                        'periode' => $periode->format('Y-m-d'),
                        'amount' => $category->nominal,
                        'status' => $status,
                        'created_by' => $admin->id,
                        'created_at' => $periode->copy()->addDays(rand(1, 5)),
                    ];

                    // Add payment data for pending/paid/rejected bills
                    if (in_array($status, ['pending', 'paid', 'rejected'])) {
                        $billData['paid_at'] = $periode->copy()->addDays(rand(6, 25));
                        $billData['payment_proof'] = 'payment_proof_' . rand(100000, 999999) . '.jpg';
                    }
                    
                    // Add verification for paid bills
                    if ($status === 'paid') {
                        $billData['verified_by'] = $admin->id;
                        $billData['verified_at'] = $periode->copy()->addDays(rand(26, 30));
                    }
                    
                    // Add rejection reason for rejected bills
                    if ($status === 'rejected') {
                        $rejectionReasons = [
                            'Bukti pembayaran tidak jelas',
                            'Nominal tidak sesuai',
                            'Foto bukti transfer tidak valid',
                            'Bank tujuan tidak sesuai',
                            'Nama pengirim tidak sesuai dengan kepala keluarga'
                        ];
                        $billData['rejection_reason'] = $rejectionReasons[array_rand($rejectionReasons)];
                        $billData['verified_by'] = $admin->id;
                        $billData['verified_at'] = $periode->copy()->addDays(rand(26, 30));
                    }

                    BillModel::create($billData);
                }
            }
        }

        // Create some specific test bills
        $testBills = [
            [
                'code' => 'IR202412TEST001',
                'family_id' => $families->first()->id,
                'income_category_id' => $categories->first()->id,
                'periode' => '2024-12-01',
                'amount' => 50000,
                'status' => 'unpaid',
                'created_by' => $admin->id,
            ],
            [
                'code' => 'IR202412TEST002',
                'family_id' => $families->skip(1)->first()->id ?? $families->first()->id,
                'income_category_id' => $categories->skip(1)->first()->id ?? $categories->first()->id,
                'periode' => '2024-12-01',
                'amount' => 75000,
                'status' => 'pending',
                'paid_at' => now()->subDays(5),
                'payment_proof' => 'payment_test_002.jpg',
                'created_by' => $admin->id,
            ],
            [
                'code' => 'IR202412TEST003',
                'family_id' => $families->skip(2)->first()->id ?? $families->first()->id,
                'income_category_id' => $categories->skip(2)->first()->id ?? $categories->first()->id,
                'periode' => '2024-12-01',
                'amount' => 100000,
                'status' => 'paid',
                'paid_at' => now()->subDays(3),
                'payment_proof' => 'payment_test_003.jpg',
                'verified_by' => $admin->id,
                'verified_at' => now()->subDays(1),
                'created_by' => $admin->id,
            ],
            [
                'code' => 'IR202412TEST004',
                'family_id' => $families->skip(3)->first()->id ?? $families->first()->id,
                'income_category_id' => $categories->first()->id,
                'periode' => '2024-12-01',
                'amount' => 50000,
                'status' => 'rejected',
                'paid_at' => now()->subDays(4),
                'payment_proof' => 'payment_test_004_invalid.jpg',
                'verified_by' => $admin->id,
                'verified_at' => now()->subDays(2),
                'rejection_reason' => 'Bukti pembayaran tidak jelas, mohon upload ulang dengan foto yang lebih jelas',
                'created_by' => $admin->id,
            ],
            [
                'code' => 'IR202412TEST005',
                'family_id' => $families->skip(4)->first()->id ?? $families->first()->id,
                'income_category_id' => $categories->first()->id,
                'periode' => '2024-11-01',
                'amount' => 50000,
                'status' => 'overdue',
                'created_by' => $admin->id,
            ],
        ];

        foreach ($testBills as $billData) {
            BillModel::create($billData);
        }

        $this->command->info('Sample bills created successfully!');
    }
}
