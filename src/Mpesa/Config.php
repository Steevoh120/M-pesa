<?php

namespace Steevoh\Mpesa;

class Config
{
    public static function load()
    {
        return [
            //C2B CONFIG
              "C2B_SHORTCODE_TYPE" => 1,
              "C2B_BUSINESS_SHORTCODE" => 174379,
              "C2B_TILL_NO" => "",
              "C2B_PASS_KEY" => "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919",
              "C2B_CONSUMER_KEY" => "D480Mh8GZvZN5M55Onb96KVAAEeaCHWP",
              "C2B_CONSUMER_SECRET" => "5w8UvprFLH6SyfZv",
              "C2B_ENVIRONMENT" => "SANDBOX",
              "C2B_INITIATOR_USERNAME" => "testapi",
              "C2B_INITIATOR_PASSWORD" => "Safaricom999!*!"

            //  B2C CONFIG
          ];
    }
}
