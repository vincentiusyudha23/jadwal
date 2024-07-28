<?php

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