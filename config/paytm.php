<?php

return [
    'merchant_id' => env('PAYTM_MERCHANT_ID'),
    'merchant_key' => env('PAYTM_MERCHANT_KEY'),
    'website' => env('PAYTM_MERCHANT_WEBSITE', 'WEBSTAGING'),
    'industry_type' => env('PAYTM_INDUSTRY_TYPE', 'Retail'),
    'channel_id' => env('PAYTM_CHANNEL_ID', 'WEB'),
    'environment' => env('PAYTM_ENVIRONMENT', 'TEST'),
    'transaction_url' => 'https://securegw-stage.paytm.in/theia/processTransaction',
];
