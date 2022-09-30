<?php

namespace Steevoh\Mpesa;

use libphonenumber\PhoneNumberUtil;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberToCarrierMapper;

class Helpers
{
    use ErrorTrait;
    /**
     * @throws NumberParseException
     */

    public static function parsephonenumber($phone): array|int
    {
        $errors = [];

        if (!ctype_digit($phone)) $errors[] = $phone." - Unknown Phone Number format" ;

        if (strlen($phone) < 10 || strlen($phone) > 13) {
            $errors[] = $phone." - Minimum or Maximum Length for Phone Number Exceeded";
        }

        if (strlen($phone) == 10) {
            if (str_starts_with($phone, "0")) {
                $phone = "254" . substr($phone, 1, 9);
            } elseif (str_starts_with($phone, "+")) {
                $phone = substr($phone, 1, 12);
            } else {
                $errors[] = $phone." - Phone Number Could not be parsed";
            }
        }

        $phoneUtil = PhoneNumberUtil::getInstance();
        $swissNumberProto = $phoneUtil->parse($phone, 'KE');

        $carrierMapper = PhoneNumberToCarrierMapper::getInstance();
        $network = $carrierMapper->getNameForNumber($swissNumberProto, 'en');

        if ($network !== "Safaricom") {
            $errors[] = $phone." - Please use A valid Safaricom Phone Number ";
        }

        if (count($errors) > 0) {
            foreach ($errors as $error) {
                return [
                    "error" => $error,
                    "phone" => null,
                ];
            }
        }

        return $phone;
    }

    public static function parseamount($amount): array|int
    {
        $errors = [];

        if (!is_int($amount)) {
            $errors[] = $amount." - Amount must be an integer";
        }

        if ($amount < 1) {
            $errors[] = $amount." - Amount must be greater than KES 1";
        }

        if (count($errors) > 0) {
            foreach ($errors as $error) {
                return [
                    "error" => $error,
                    "amount" => null,
                ];
            }
        }

        return $amount;
    }

    public static function validateURI(string $uri, string $name = ""): array|string
    {
        if (filter_var($uri, FILTER_VALIDATE_URL)) {
            return $uri;
        }

        return ['error' => $uri ." - ". static::geterrormessage('invalid', $name)];
    }

    public static function encode($array): bool|string
    {
        return json_encode($array);
    }

    public static function decode(string $array)
    {
        return json_decode($array);
    }
}
