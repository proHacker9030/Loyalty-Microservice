<?php

declare(strict_types=1);

namespace App\Enum;

class LoyaltySystems extends AbstractEnum
{
    public const CITY_CARD = 'cityCard';
    public const DLS = 'dls';
    public const MANZANA = 'manzana';
    public const MINDBOX = 'mindBox';
    public const MOCK = 'mock';

    public static function getLabels(): array
    {
        return [
            self::CITY_CARD => 'Гор. карта',
            self::DLS => 'DLS',
            self::MINDBOX => 'MindBox',
            self::MANZANA => 'Manzana',
        ];
    }
}
