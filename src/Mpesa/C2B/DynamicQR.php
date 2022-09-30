<?php

declare(strict_types=1);

namespace Steevoh\Mpesa\C2B;

use GuzzleHttp\Exception\GuzzleException;
use Steevoh\Mpesa\Helpers;

class DynamicQR extends AuthToken
{
    /**
     * @throws GuzzleException
     */
    public function generateQR(array $params, array $options = []): bool|string
    {
        $payload = Helpers::encode([
            "TrxCode" =>$params["TrxCode"],
            "CPI" => $params["CPI"],
            "MerchantName" => $params["MerchantName"],
            "Amount" => intval($params["Amount"]),
            "RefNo" => $params["RefNo"]
        ]);

        $url = $this->base_url . 'mpesa/qrcode/v1/generate';
        $response = json_decode(parent::submit_request($url, $payload));

        if (isset($response->ResponseCode) && $response->ResponseCode == "00") {
            $text = $response->QRCode;
            return "<img src='data:image/png;base64,".$text."' alt='".$options["alt"]."' width='".$options["width"]."' height='".$options["height"]."'>";
        }

        return Helpers::encode($response);
    }
}
