<?php

declare(strict_types=1);

namespace App\Helpers;

class ClassHelper
{
    /**
     * @throws \ReflectionException
     */
    public static function usesTrait(string $trait, string $class): bool
    {
        return in_array(
            $trait,
            array_keys((new \ReflectionClass($class))->getTraits())
        );
    }
}
