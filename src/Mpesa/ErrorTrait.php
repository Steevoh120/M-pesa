<?php

namespace Steevoh\Mpesa;

trait ErrorTrait
{
    protected static array $errorMessages = [
        "invalid" => "Invalid *__*",
    ];

    /**
     * @param $error
     * @param $var
     * @return array|string
     */

    public static function geterrormessage($error, $var): array|string
    {
        $error=  static::$errorMessages[$error];

        return str_replace("*__*", $var, $error);
    }
}
