<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Jadwal;
use Illuminate\Support\Collection;
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
            $user = User::where('name', $line['Nama Karyawan'])
                ->orWhere('id_karyawan', $line['ID Karyawan'])
                ->select('id')
                ->first();

            if($user){
                $jadwal = Jadwal::where([
                    'tanggal' => $line['Tanggal'],
                    'waktu' => $line['Waktu']
                ])->first();

                if($jadwal){
                    continue;
                }

                Jadwal::create([
                    'id_karyawan' => $user->id,
                    'tanggal' => $line['Tanggal'],
                    'waktu' => $line['Waktu'],
                    'tujuan' => $line['Tujuan'],
                    'tugas' => $line['Tugas'] 
                ]);
            }
        }
    }
}
