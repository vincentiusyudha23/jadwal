<?php

namespace App\Enums;

enum IjinEnum: int
{
    const IJIN = 1;
    const SAKIT = 2;
    const CUTI = 3;

    public static function getLabel(int $value): string
    {
        return match ($value) {
            self::IJIN => 'Izin',
            self::SAKIT => 'Sakit',
            self::CUTI => 'Cuti',
            default => 'Unknown',
        };
    }

    public static function getValue(string $value): int
    {
        return match ($value) {
            '1' => self::IJIN,
            '2' => self::SAKIT,
            '3' => self::CUTI,
            default => 0,
        };
    }
}