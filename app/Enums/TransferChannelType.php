<?php

namespace App\Enums;

enum TransferChannelType: string{
    case BANK = 'BANK';
    case E_WALLET = 'E_WALLET';
    case QRIS = 'QRIS';
}
