<?php

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\MediaImage;
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
        $hour = Carbon::now()->format('H');
        
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

if(!function_exists('formatBytes')){
    function formatBytes($size,
        $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = ['',
            'KB',
            'MB',
            'GB',
            'TB'];

        return round(1024 ** ($base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
    }
}

if(!function_exists('get_data_image')){
    function get_data_image($id){
        $image = MediaImage::find($id);

        $data = [];
        
        if($image){
            $data = [
                'alt' => $image->title,
                'img_url' => assets('img/'.$image->path)
            ];
        }

        return $data;
    }
}

if(!function_exists('global_assets_path')){
    function global_assets_path($path)
    {
        return str_replace(['core/public/',
                               'core\\public\\'], '', public_path($path));
    }
}

if(!function_exists('generateOtp')){
    function generateOtp()
    {
        $otp = rand(1000, 9999);

        if (env('APP_ENV') == 'local') {
            $otp = 1234;
        }

        return $otp;
    }
}

if (!function_exists('currency')) {
    function currency($amount = 0)
    {
        return 'Rp ' . number_format($amount, 0, ',', '.') . ',-';
    }
}

if (!function_exists('withoutCurrency')) {
    function withoutCurrency($amount = 0)
    {
        if($amount == 0){
            return '-';
        }

        return number_format($amount, 0, ',', '.') . ',-';
    }
}

if(!function_exists('sendEmail')){
    function sendEmail($data= [])
    {
        // if(env('APP_ENV') == 'local'){
        //     return true;
        // }

        if (!isset($data['to']) || !isset($data['subject']) || !isset($data['view'])) {
            return false;
        }

        dispatch(function () use ($data) {
            try {
                $viewData = $data['viewData'] ?? [];
                \Mail::send('emails.'.$data['view'], $viewData, function($message) use ($data) {
                    if(isset($data['viewData']['from_name']) && !is_null($data['viewData']['from_name'])){
                        $message->from(($data['viewData']['from_address'] ?? env('MAIL_FROM_ADDRESS','email@email.com')) , ($data['viewData']['from_name'] ?? env('MAIL_FROM_NAME','WiraGriya')));
                    }
                    $message->to($data['to'])->subject($data['subject']);
                    foreach ($data['attachments'] ?? [] as $attachment) {
                        $message->attach($attachment['path'], [
                            'as' => $attachment['name'],
                        ]);
                    }
                });

                \Log::channel('info')->info('Email sending',[
                    'to' => $data['to'],
                    'subject' => $data['subject'],
                ]);

            } catch (\Exception $e) {
                if(app()->environment('local')){
                    dd($e->getMessage());
                }

                \Log::error('Error while sending email: ' . $e->getMessage());
            }
        });

        return true;
    }
}

if(!function_exists('route_prefix')){
    function route_prefix(){
        return Auth::user()->hasRole('admin') ? 'admin.' : 'karyawan.';
    }
}

if(!function_exists('kalkulasiHariIjin')){
    function kalkulasiHariIjin($data){
        $start = Carbon::parse($data->from_date);
        $end = Carbon::parse($data->to_date);

        $periodStart = $start->copy();
        $periodEnd = $end->copy();

        $daysInMonth = collect(CarbonPeriod::create($periodStart, $periodEnd))
            ->filter(function ($date){
                return !$date->isSunday() && !$date->isSaturday();
            });

        return $daysInMonth->count();
    }
}