<?php

namespace Steevoh\Mpesa\C2B;

use GuzzleHttp\Exception\GuzzleException;
use Steevoh\Mpesa\Helpers;

class Registerurl extends AuthToken
{

    /**
     * @throws GuzzleException
     */
    public function register(array $params)
    {
        if (!isset(Helpers::validateURI($params["ConfirmationURL"], "ConfirmationURL")["error"])) {
            $ConfirmationURL =  Helpers::validateURI($params["ConfirmationURL"], "ConfirmationURL");
        } else {
            return  Helpers::validateURI($params["ConfirmationURL"], "ConfirmationURL")["error"];
        }

        if (!isset(Helpers::validateURI($params["ValidationURL"], "ValidationURL")["error"])) {
            $ValidationURL =  Helpers::validateURI($params["ValidationURL"], "ValidationURL");
        } else {
            return Helpers::validateURI($params["ValidationURL"], "ValidationURL")["error"];
        }

        $ResponseType = $params["ResponseType"];

        $payload = Helpers::encode([
            'ShortCode' => $this->Business_shortcode,
            'ResponseType' => $ResponseType,
            'ConfirmationURL' => $ConfirmationURL,
            'ValidationURL' => $ValidationURL
            ]);

        $url = $this->base_url. "mpesa/c2b/v1/registerurl";

        return parent::submit_request($url, $payload);
    }
}
