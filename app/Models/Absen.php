<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Absen extends Model
{
    use HasFactory;

    protected $table = 'absens';
    protected $fillable = ['waktu', 'tanggal', 'lokasi', 'type', 'image'];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_karyawan', 'id');
    }

    public function getWaktuFormatAttribute()
    {
        return \Carbon\Carbon::createFromFormat('H:i:s', $this->waktu)->format('H:i');
    }
}
