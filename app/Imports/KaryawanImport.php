<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class KaryawanImport implements ToCollection, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    /**
    * @param Collection $collection
    */

    public function collection(Collection $rows)
    {
        foreach ($rows as $karyawan) {

            if(is_null($karyawan['Nama'])){
                continue;
            }
            $username = Str::lower(Str::replace(' ', '', $karyawan['Nama']));
            $password = $username . $karyawan['ID'];

            $user = User::where('username', $username)
                        ->orWhere('id_karyawan', $karyawan['ID'])
                        ->orWhere('email', $karyawan['Email'])
                        ->first();
            
            if($user){
                $user->update([
                    'name' => $karyawan['Nama'],
                    'id_karyawan' => (string) $karyawan['ID'],
                    'username' => $username,
                    'password' => Hash::make($password),
                    'enc_password' => Crypt::encryptString($password),
                    'role' => 'karyawan',
                    'email' => $karyawan['Email']
                ]);
            }else{
                $user = User::create([
                    'name' => $karyawan['Nama'],
                    'id_karyawan' => (string) $karyawan['ID'],
                    'username' => $username,
                    'password' => Hash::make($password),
                    'enc_password' => Crypt::encryptString($password),
                    'role' => 'karyawan',
                    'email' => $karyawan['Email']
                ]);
            }

            $user->karyawan()->updateOrCreate(
                ['id_karyawan' => $user->id],
                [
                    'name' => $karyawan['Nama'],
                    'jabatan' => $karyawan['Jabatan'],
                    'divisi' => $karyawan['Divisi'],
                    'nomor_rekening' => $karyawan['Nomor Rekening'],
                    'gaji' => (float) data_get($karyawan, 'Gaji Pokok', 0)
                ]
            );

            $user->assignRole('karyawan'); 
        }
    }

    public function rules(): array
    {
        return [
            'Nama' => 'required',
            'Jabatan' => 'required',
            'Nomor Rekening' => 'required',
            'Gaji Pokok' => 'required',
            'Divisi' => 'required',
            'ID' => 'required',
            'Email' => 'required'
        ];
    }
}
