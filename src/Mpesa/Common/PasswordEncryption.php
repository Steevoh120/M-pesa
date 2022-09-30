<?php

namespace Steevoh\Mpesa\Common;

class PasswordEncryption
{
    public static function encrypt(string $password, $env)
    {
        $crt = __DIR__.'/certs/'.strtolower($env).'.cer';

        $fp = fopen($crt, 'r');
        $cert = fread($fp, 8192);
        openssl_public_encrypt($password, $encrypted, $cert, OPENSSL_PKCS1_PADDING);

        return base64_encode($encrypted);
    }
}
