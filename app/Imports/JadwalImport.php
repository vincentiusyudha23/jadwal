<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Jadwal;
use Illuminate\Support\Collection;
use App\Jobs\NotificationJadwalJob;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class JadwalImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $line) {
            $tanggal = Carbon::parse($line['Tanggal'])->format('Y-m-d');
            $waktu = Carbon::parse($line['Waktu'])->format('H:i');
            
            $user = User::where('name', $line['Nama Karyawan'])
                ->orWhere('id_karyawan', $line['ID Karyawan'])
                ->select('id')
                ->first();

            if($user){
                $jadwal = Jadwal::where([
                    'tanggal' => $tanggal,
                    'waktu' => $waktu
                ])->first();

                if($jadwal){
                    continue;
                }

                $jadwalNew = Jadwal::create([
                    'id_karyawan' => $user->id,
                    'tanggal' => $tanggal,
                    'waktu' => $waktu,
                    'tujuan' => $line['Tujuan'],
                    'tugas' => $line['Tugas'] 
                ]);

                NotificationJadwalJob::dispatch($jadwalNew);
            }
        }
    }
}
