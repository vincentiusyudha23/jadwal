<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GajiKaryawan extends Model
{
    use HasFactory;

    protected $table = 'gaji_karyawans';
    protected $fillable = [
        'id_karyawan',
        'total_hari_kerja',
        'ijin',
        'cuti',
        'sakit',
        'alpa',
        'total_absen',
        'gp_bulanan',
        'tj_komunikasi',
        'tj_keahlian',
        'tj_kesehatan',
        'total_upah_tetap',
        'tj_makan',
        'lembur',
        'tj_transport',
        'pll',
        'pp',
        'lbpph21',
        'total_upah_non_tetap',
        'pt_pph21',
        'pt_pp',
        'pt_bpjs_kesehatan',
        'pt_bpjs_kerja',
        'pt_ll',
        'pt_absensi',
        'total_potongan',
        'total_diterima',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_karyawan', 'id');
    }
}
