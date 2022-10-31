<?php

declare(strict_types=1);

namespace App\Enum;

use ReflectionClass;

abstract class AbstractEnum
{
    public static function getConsts(): array
    {
        $refl = new ReflectionClass(get_called_class());

        return $refl->getConstants();
    }

    abstract public static function getLabels(): array;
}
