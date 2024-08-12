<?php

use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

if (!function_exists('assets')) {
    function assets($param) {
        // Logika helper Anda
        return asset('assets/'.$param);
    }
}

if(!function_exists('getCurrentTimeOfDay')){
    function getCurrentTimeOfDay($user = 'admin')
    {
        $hour = Carbon\Carbon::now()->format('H');
        
        if ($hour >= 5 && $hour < 12) {
            return 'Selamat Pagi, '.$user;
        } elseif ($hour >= 12 && $hour < 16) {
            return 'Selamat Siang, '.$user;
        } elseif ($hour >= 16 && $hour < 19) {
            return 'Selamat Sore, '.$user;
        } else {
            return 'Selamat Malam, '.$user;
        }
    }
}

if(!function_exists('decryptPassword')){
    function decryptPassword($password = '')
    {
        try {
            $password = Crypt::decryptString($password);

            return $password;
        } catch (DecryptException $e) {
            return \Log::error($e->getMessage());
        }
    }
}

if(!function_exists('statusJadwal')){
    function statusJadwal($status = ''){
        switch ($status) {
            case 0:
                return 'Belum Selesai';
                break;
            case 1:
                return 'Selesai';
                break;
            default:
                return $status;
                break;
        }
    }
}