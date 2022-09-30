<?php

require "vendor/autoload.php";

use Steevoh\Mpesa\C2B\C2B;
use Steevoh\Mpesa\C2B\Lnmo;
use Steevoh\Mpesa\C2B\DynamicQR;
use Steevoh\Mpesa\C2B\Registerurl;
use Steevoh\Mpesa\C2B\Reversal;
use Steevoh\Mpesa\C2B\TransactionStatus;

$params = [
    "phone" => "07XXXXXXXX", //use 07XXXXXXXX // 01XXXXXXXX // 2547xxxxxxxx // 2541xxxxxxxx // +2547xxxxxxxx // +2541xxxxxxxx
    "amount" => 1, //
    "account_ref" => rand(8854, 545445), // Any combinations of letters and numbers. Maximum of 12 characters.
    "description" => rand(8854, 545445), //Any string between 1 and 13 characters.
    "callback_url" => "https://rotten-donuts-show-102-68-77-133.loca.lt/mpesa/Callback.php"
];

$token = new Lnmo();
echo $token->Stkpush($params);

$params = [
    'CheckoutRequestID' => "ws_CO_300920221340449307XXXXXXXX"
];

$query = new TransactionStatus();
echo $query->getstatus($params);

$params = [
    'TransactionID' => "QIU8XS3WF2",
    'callback_url' => "https://rotten-donuts-show-102-68-77-133.loca.lt/mpesa/Callback.php"
];

$query = new TransactionStatus();
echo $query->query($params);

$params = [
    "TrxCode" => "SM",
    "CPI" => "07XXXXXXXX",
    "MerchantName" => "STEPHEN NGARI",
    "Amount" => 200,
    "RefNo" => "rf384"
];

$options = [
    "width" => 500,
    "height" => 500,
    "alt" => ""
];

$qr = new DynamicQR();
echo $qr->generateQR($params, $options);

$params = [
    "ConfirmationURL"=> "https://rotten-donuts-show-102-68-77-133.loca.lt",
    "ValidationURL" => "https://rotten-donuts-show-102-68-77-133.loca.lt",
    "ResponseType" => 'Completed'
];

$register = new Registerurl();
echo $register->register($params);

$params = [
    'TransactionID' => "QIU8XS3WF2",
    'amount' => 10,
    'callback_url' => "https://rotten-donuts-show-102-68-77-133.loca.lt/mpesa/Callback.php"
];

$reverse = new Reversal();
echo $reverse->reverse($params);

$params = [
    "phone" => "07XXXXXXXX", //use 07XXXXXXXX // 01XXXXXXXX // 2547xxxxxxxx // 2541xxxxxxxx // +2547xxxxxxxx // +2541xxxxxxxx
    "amount" => 1, //
    "account_ref" => rand(8854, 545445), // Any combinations of letters and numbers. Maximum of 12 characters.
];

$c2b = new C2B();
echo $c2b->simulate($params);
