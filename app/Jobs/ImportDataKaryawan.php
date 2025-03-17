<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ImportDataKaryawan implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;

    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        ini_set('max_execution_time', 0);

        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach($this->data ?? [] as $karyawan){
            $username = Str::lower(Str::replace(' ', '', $karyawan['Nama']));
            $password = $username . $karyawan['ID'];

            $user = User::create([
                'name' => $karyawan['Nama'],
                'id_karyawan' => (int) $karyawan['ID'],
                'username' => $username,
                'password' => Hash::make($password),
                'enc_password' => Crypt::encryptString($password),
                'role' => 'karyawan',
                'email' => $karyawan['Email']
            ]);

            $user->karyawan()->create([
                'name' => $karyawan['Nama'],
                'jabatan' => $karyawan['Jabatan'],
                'divisi' => $karyawan['Divisi'],
                'nomor_rekening' => $karyawan['Nomor Rekening'],
                'gaji' => (float) $karyawan['Gaji']
            ]);

            $user->assignRole('karyawan');
        }
    }
}
