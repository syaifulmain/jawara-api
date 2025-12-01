<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran';

    protected $fillable = [
        'jenis_pengeluaran',
        'nama_pengeluaran',
        'kategori',
        'tanggal',
        'nominal',
        'verifikator',
    ];
}
