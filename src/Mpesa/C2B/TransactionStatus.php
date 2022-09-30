<?php

namespace Steevoh\Mpesa\C2B;

use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Steevoh\Mpesa\Helpers;
use Steevoh\Mpesa\Common\PasswordEncryption;

class TransactionStatus extends AuthToken
{
    private string $timestamp;
    private string $password;
    private int $IdentifierType;

    public function __construct()
    {
        parent::__construct();
        $this->timestamp =  Carbon::now()->format('YmdHis');
        $this->password = base64_encode($this->Business_shortcode . $this->lipa_na_mpesa_key . $this->timestamp);
        $this->IdentifierType = 4;
        if ($this->ShortcodeType === 2) {
            $this->IdentifierType = 2;
        }
    }

    /**
     * @throws GuzzleException
     */
    public function getstatus(array $params): bool|string
    {
        $payload = Helpers::encode([
            'BusinessShortCode' => $this->Business_shortcode,
            'Password' => $this->password,
            'Timestamp' => $this->timestamp,
            'CheckoutRequestID' => $params["CheckoutRequestID"],
            ]);

        $url = $this->base_url. "mpesa/stkpushquery/v1/query";

        return parent::submit_request($url, $payload);
    }

    /**
     * @throws GuzzleException
     */
    public function query(array $params): bool|string
    {
        $initiator_cred = PasswordEncryption::encrypt($this->Operator_Password, $this->environment);

        $payload = Helpers::encode([
            'CommandID' => 'TransactionStatusQuery',
            'PartyA' => $this->Business_shortcode,
            'IdentifierType' => $this->IdentifierType,
            'Remarks' => 'Transaction Query',
            'Initiator' => $this->Operator,
            'SecurityCredential' => $initiator_cred,
            'QueueTimeOutURL' => $params["callback_url"],
            'ResultURL' => $params["callback_url"],
            'TransactionID' => $params["TransactionID"],
            'Occasion' => $this->timestamp,
            ]);

        $url = $this->base_url . 'mpesa/transactionstatus/v1/query';

        return parent::submit_request($url, $payload);
    }
}
