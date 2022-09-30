<?php

namespace Steevoh\Mpesa\C2B;

use Exception;
use GuzzleHttp\Client;
use Steevoh\Mpesa\Config;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

class AuthToken
{
    /**
     * @var array
     */
    protected array $config;

    protected string $base_url;

    /**
     * @var string|mixed
     */
    protected string $Business_shortcode;

    /**
     * @var string|mixed
     */
    protected string $lipa_na_mpesa_key;

    /**
     * @var string|mixed
     */
    protected string $PartyB;

    /**
     * @var mixed
     */
    protected mixed $ShortcodeType;

    /**
     * @var string
     */
    protected string $command;

    /**
     * @var mixed
     */
    protected mixed $environment;

    protected const SANDBOX_URI = "https://sandbox.safaricom.co.ke/";

    protected const PRODUCTION_URI = "https://api.safaricom.co.ke/";

    protected const CUSTOMER_BUYGOODS_ONLINE = 'CustomerBuyGoodsOnline';

    protected const CUSTOMER_PAYBILL_ONLINE = 'CustomerPayBillOnline';

    /**
     * @var mixed
     */
    protected mixed $Operator;

    /**
     * @var mixed
     */
    protected mixed $Operator_Password;


    public function __construct()
    {
        $this->config = Config::load();

        $this->environment = $this->config["C2B_ENVIRONMENT"];

        $this->ShortcodeType = $this->config["C2B_SHORTCODE_TYPE"];

        $this->Business_shortcode = $this->config["C2B_BUSINESS_SHORTCODE"];

        $this->lipa_na_mpesa_key = $this->config["C2B_PASS_KEY"];

        $this->Operator = $this->config["C2B_INITIATOR_USERNAME"];

        $this->Operator_Password = $this->config["C2B_INITIATOR_PASSWORD"];

        $this->base_url = self::PRODUCTION_URI;

        $this->PartyB = $this->Business_shortcode;

        $this->command = self::CUSTOMER_PAYBILL_ONLINE;

        if ($this->environment == "SANDBOX") {
            $this->base_url = self::SANDBOX_URI;
        }

        if ($this->ShortcodeType === 2) {
            $this->PartyB = $this->config["C2B_TILL_NO"];
            $this->command = self::CUSTOMER_BUYGOODS_ONLINE;
        }
    }

    /**
     * @throws GuzzleException
     */

    private function generateauthtoken()
    {
        try {
            $client = new Client();
            $cred_url = $this->base_url . 'oauth/v1/generate?grant_type=client_credentials';
            $credentials = base64_encode($this->config["C2B_CONSUMER_KEY"] . ':' . $this->config["C2B_CONSUMER_SECRET"]);

            $request = $client->get($cred_url, [
                'headers' => ['content-type' => 'application/json', 'Authorization' => 'Basic ' . $credentials],
            ]);

            if ($body = $request->getBody()->getContents()) {
                $response = json_decode($body);
            }
        } catch (Exception $e) {
            $response = $e->getMessage();
        }
        /** @var  $response */
        return $response;
    }

    /**
     * @throws GuzzleException
     */

    public function submit_request($url, $payload): bool|string
    {
        if (empty($this->generateauthtoken()->access_token)) {
            return $this->generateauthtoken();
        } else {
            $client = new Client();
            try {
                $request = $client->post($url, [
                    'headers' => [
                        'content-type' => 'application/json',
                        'Authorization' => 'Bearer ' . $this->generateauthtoken()->access_token,
                    ],
                    'body' => $payload,
                ]);
                if ($body = $request->getBody()) {
                    $response = $body->getContents();
                } else {
                    return false;
                }
            } catch (RequestException $e) {
                if ($e->hasResponse()) {
                    $response = $e->getResponse()->getBody()->getContents();
                } else {
                    return false;
                }
            }
            return $response;
        }
    }
}
