<?php
return [
    'sandbox' => env('ALIPAY_SANDBOX',false),
    'app_id'  => env('ALIPAY_APPID',''),
    'merchant_private_key' => env('ALIPAY_PRIVATE_KEY',''), //应用私钥
    'alipay_public_key' => env('ALIPAY_PUBLIC_KEY',''), //支付宝公钥
    'charset' => env('ALIPAY_CHARSET','UTF-8'),
    'sign_type' => env('ALIPAY_SIGN_TYPE','RSA'),
];