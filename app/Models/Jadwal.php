<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwals';
    protected $fillable = ['id_karyawan', 'hari', 'tanggal', 'tujuan', 'tugas'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
