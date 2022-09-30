<?php

namespace Steevoh\Mpesa\C2B;

use Carbon\Carbon;
use Steevoh\Mpesa\Helpers;
use Steevoh\Mpesa\Common\PasswordEncryption;

class Reversal extends AuthToken
{
    public function __construct()
    {
        parent::__construct();
        $this->timestamp =  Carbon::now()->format('YmdHis');
        $this->IdentifierType = 4;
        if ($this->ShortcodeType === 2) {
            $this->IdentifierType = 2;
        }
    }

    public function reverse(array $params)
    {
        $initiator_cred = PasswordEncryption::encrypt($this->Operator_Password, $this->environment);

        $payload = Helpers::encode([
            "Initiator" => $this->Operator,
            "SecurityCredential" => $initiator_cred,
            "CommandID" =>"TransactionReversal",
            "TransactionID" => $params["TransactionID"],
            "ReceiverParty" => $this->Business_shortcode,
            'Amount' => $params["amount"],
            "RecieverIdentifierType" => "11",
            "QueueTimeOutURL" => $params["callback_url"],
            "ResultURL" => $params["callback_url"],
            "Remarks" => "Failed Payment",
            "Occasion" => "Failed Payment"
        ]);

        $url = $this->base_url . 'mpesa/reversal/v1/request';
        $response = parent::submit_request($url, $payload);
        return $response;
    }
}
