<?php

namespace App\Enums;


class DivisiEnum
{

    public static function getDivisi()
    {
        return [
            'petfood' => 'PETFOOD',
            'hoist_crane' => 'HOIST & CRANE',
            'coating_spray' => 'COATING SPRAY',
            'acc_tax' => 'ACC & TAX',
            'security' => 'SECURITY',
            'hr_ga' => 'HR & GA',
            'mesin' => 'MESIN',
            'finance' => 'FINANCE',
            'purchasing' => 'PURCHASING',
            'mixue' => 'MIXUE'
        ];

        // return [
        //     'PETFOOD',
        //     'HOIST & CRANE',
        //     'COATING SPRAY',
        //     'ACC & TAX',
        //     'SECURITY',
        //     'HR & GA',
        //     'MESIN',
        //     'FINANCE',
        //     'PURCHASING',
        //     'MIXUE'
        // ];
    }

    public static function getItemDivisi($text)
    {
        $divisi = self::getDivisi();

        return $divisi[strtolower($text)] ?? strtoupper($text);
    }

    public static function getValDivisi()
    {
        return array_values(self::getDivisi());
    }

    public static function getKeyDivisi()
    {
        return array_keys(self::getDivisi());
    }
}