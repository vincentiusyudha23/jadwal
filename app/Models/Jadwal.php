<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwals';
    protected $fillable = ['id_karyawan','tanggal', 'tujuan', 'tugas', 'status', 'keterangan', 'image'];
    protected $casts = [ 'tanggal' => 'datetime'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_karyawan', 'id');
    }
}
