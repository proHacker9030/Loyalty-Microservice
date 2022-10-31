<?php

declare(strict_types=1);

namespace App\Helpers;

class StringHelper
{
    public static function arrayToXml(array $data, \SimpleXMLElement &$xml): void
    {
        foreach ($data as $key => $value) {
            if (is_numeric($key)) {
                $key = 'item' . $key;
            }
            if (is_array($value)) {
                $subnode = $xml->addChild($key);
                self::arrayToXml($value, $subnode);
            } else {
                $xml->addChild("$key", htmlspecialchars("$value"));
            }
        }
    }

    public static function pregSearchValues(string $needle, array $haystack): array|false
    {
        $input = preg_quote($needle, '~');

        return preg_grep('~' . $input . '~', $haystack);
    }
}
