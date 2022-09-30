<?php

namespace Steevoh\Mpesa\C2B;

use GuzzleHttp\Exception\GuzzleException;
use libphonenumber\NumberParseException;
use Steevoh\Mpesa\Helpers;

class C2B extends AuthToken
{

    /**
     * @throws GuzzleException
     * @throws NumberParseException
     */
    public function simulate(array $params)
    {
        $phone = $params["phone"];
        $amount = intval(ceil($params["amount"]));
        $account = $params["account_ref"];

        if (is_int(Helpers::parsephonenumber($phone))) {
            $phone1 = Helpers::parsephonenumber($phone);
        } else {
            return Helpers::parsephonenumber($phone)["error"];
        }

        if (is_int(Helpers::parseamount($amount))) {
            $amount1 = Helpers::parseamount($amount);
        } else {
            return Helpers::parseamount($amount)["error"];
        }

        $payload = Helpers::encode([
            "ShortCode"=> 600996,
            "CommandID"=> "CustomerPayBillOnline",
            "Amount"=> $amount1,
            "Msisdn"=> $phone1,
            "BillRefNumber"=> $account
        ]);

        $url = $this->base_url . 'mpesa/c2b/v1/simulate';
        return parent::submit_request($url, $payload);
    }
}
