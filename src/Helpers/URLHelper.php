<?php

namespace Koost89\LoginLinks\Helpers;

class URLHelper
{
    public static function classToEncodedString($className): string
    {
        return  base64_encode(
            str_replace('\\', '_', $className)
        );
    }

    public static function encodedStringToClass($encodedString): string
    {
        return str_replace('_', '\\', base64_decode($encodedString));
    }
}
