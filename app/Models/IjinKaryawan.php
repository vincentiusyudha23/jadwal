<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IjinKaryawan extends Model
{
    use HasFactory;

    protected $table = 'ijin_karyawans';
    protected $fillable = ['id_karyawan', 'from_date', 'to_date', 'keterangan', 'surat', 'type'];
    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_karyawan', 'id');
    }
}
