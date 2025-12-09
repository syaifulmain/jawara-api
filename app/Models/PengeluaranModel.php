<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengeluaranModel extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran';

    protected $fillable = [
        'nama_pengeluaran',
        'tanggal',
        'kategori',
        'nominal',
        'verifikator',
        'bukti_pengeluaran',
    ];

    protected $casts = [
        'id' => 'integer',
        'tanggal' => 'date',
    ];
}
