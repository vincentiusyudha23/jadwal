<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwals';
    protected $fillable = ['id_karyawan','tanggal', 'tujuan', 'tugas', 'status', 'keterangan', 'image', 'waktu', 'note', 'work_report'];
    protected $casts = [ 'tanggal' => 'datetime'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_karyawan', 'id');
    }

    public function getWaktuFormatAttribute()
    {
        return Carbon::createFromFormat('H:i:s', $this->waktu)->format('H:i');
    }
}
