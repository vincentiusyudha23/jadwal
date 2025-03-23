<?php

namespace App\Enums;

class BulanEnum
{
    const JANUARI = 1;
    const FEBRUARI = 2;
    const MARET = 3;
    const APRIL = 4;
    const MEI = 5;
    const JUNI = 6;
    const JULI = 7;
    const AGUSTUS = 8;
    const SEPTEMBER = 9;
    const OKTOBER = 10;
    const NOVEMBER = 11;
    const DESEMBER = 12;

    public static function getBulan($bulan)
    {
        $data = [
            'Januari' => self::JANUARI,
            'Februari' => self::FEBRUARI,
            'Maret' => self::MARET,
            'April' => self::APRIL,
            'Mei' => self::MEI,
            'Juni' => self::JUNI,
            'Juli' => self::JULI,
            'Agustus' => self::AGUSTUS,
            'September' => self::SEPTEMBER,
            'Oktober' => self::OKTOBER,
            'November' => self::NOVEMBER,
            'Desember' => self::DESEMBER,
        ];

        return $data[$bulan] ?? '';
    }
}