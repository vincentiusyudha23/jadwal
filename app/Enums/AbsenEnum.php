<?php

namespace App\Enums;

class AbsenEnum
{
    const MASUK = 1;
    const PULANG = 2;

    public static function getType($type)
    {
        switch ($type) {
            case self::MASUK:
                return '<span class="badge text-bg-success">Masuk</span>';
                break;
            case self::PULANG:
                return '<span class="badge text-bg-warning">Pulang</span>';
                break;
            default:
                return '';
                break;
        }
    }

    public static function getText($type)
    {
        switch ($type) {
            case self::MASUK :
                return 'Absen Masuk';
                break;

            case self::PULANG :
                return 'Absen Pulang';
                break;
            
            default:
                return '';
                break;
        }
    }
}