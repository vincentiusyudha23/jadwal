<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Karyawan extends Model
{
    use HasFactory;
    protected $table = 'karyawans';
    protected $fillable = [
        'name',
        'jabatan',
        'divisi',
        'nomor_rekening',
        'id_karyawan'
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id_karyawan', 'id');
    }
}
