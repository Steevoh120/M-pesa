<?php

namespace Steevoh\Mpesa\C2B;

use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Steevoh\Mpesa\Helpers;
use libphonenumber\NumberParseException;

class Lnmo extends AuthToken
{
    protected string $phone;

    protected int $amount;

    protected string $reference;

    protected string $description;

    protected string $account;

    protected string $command;

    protected string $callback;

    private string $timestamp;

    public function __construct()
    {
        parent::__construct();
        $this->timestamp =  Carbon::now()->format('YmdHis');
    }

    /**
     * @throws GuzzleException
     * @throws NumberParseException
     */

    public function Stkpush(array $params): bool|string
    {
        $phone = $params["phone"];
        $amount = intval(ceil($params["amount"]));
        $this->account = $params["account_ref"];
        $this->description = $params["description"];

        if (is_int(Helpers::parsephonenumber($phone))) {
            $this->phone= Helpers::parsephonenumber($phone);
        } else {
            return Helpers::parsephonenumber($phone)["error"];
        }

        if (is_int(Helpers::parseamount($amount))) {
            $this->amount = Helpers::parseamount($amount);
        } else {
            return Helpers::parseamount($amount)["error"];
        }

        $password = base64_encode($this->Business_shortcode . $this->lipa_na_mpesa_key . $this->timestamp);

        $payload = Helpers::encode([
            'BusinessShortCode' => $this->Business_shortcode,
            'Password' => $password,
            'Timestamp' => $this->timestamp,
            'TransactionType' => $this->command,
            'Amount' => $this->amount,
            'PartyA' => $this->phone,
            'PartyB' => $this->Business_shortcode,
            'PhoneNumber' => $this->phone,
            'CallBackURL' => $params["callback_url"],
            'AccountReference' => $this->account,
            'TransactionDesc' => $this->description,
        ]);

        $url = $this->base_url . 'mpesa/stkpush/v1/processrequest';
        $response = parent::submit_request($url, $payload);

        $result = Helpers::decode($response);

        if (json_last_error() === JSON_ERROR_NONE) {
            $ResponseCode = $result->ResponseCode ?? null; // 0

            if ($ResponseCode == 0) {
                //STKPUSH was Successful

                $CustomerMessage = $result->CustomerMessage; // Success. Request accepted for processing
                $MerchantRequestID = $result->MerchantRequestID; // 43066-171063189-1 -> You may save it to your DB
                $CheckoutRequestID = $result->CheckoutRequestID; // ws_CO_12042022203402395745882321 -> Can be used to Query A transaction. You may save it to your DB and UPDATE this record on stkpush callback
                $ResponseDescription = $result->ResponseDescription; // Success. Request accepted for processing

                //
            } else {
                //STKPUSH FAILED
                $requestId = $result->requestId;
                $errorCode = $result->errorCode;
                $errorMessage = $result->errorMessage;
            }
        }

        return $response;
    }
}
