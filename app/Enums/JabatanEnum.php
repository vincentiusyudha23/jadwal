<?php

namespace App\Enums;


class JabatanEnum
{

    public static function getJabatan()
    {
        return [];
    }

    public static function getItemJabatan($text)
    {
        $jabatan = self::getJabatan();

        return $jabatan[strtolower($text)] ?? ucfirst($text);
    }

    public static function getValJabatan()
    {
        return array_values(self::getJabatan());
    }

    public static function getKeyJabatan()
    {
        return array_keys(self::getJabatan());
    }
}