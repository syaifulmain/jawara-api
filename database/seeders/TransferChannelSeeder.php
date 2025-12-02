<?php

namespace Database\Seeders;

use App\Models\TransferChannel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransferChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TransferChannel::create([
            'name' => 'Red Lion Bank',
            'account_number' => '1234567890',
            'owner_name' => 'Leonidas',
            'type' => \App\Enums\TransferChannelType::BANK,
        ]);
        TransferChannel::create([
            'name' => 'Blue Whale E-Wallet',
            'account_number' => '0987654321',
            'owner_name' => 'Ariel',
            'type' => \App\Enums\TransferChannelType::E_WALLET,
        ]);
        TransferChannel::create([
            'name' => 'Ursid Wallet QRIS',
            'account_number' => '1122334455',
            'owner_name' => 'Ursidae',
            'type' => \App\Enums\TransferChannelType::QRIS,
        ]);
    }
}
