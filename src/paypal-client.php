<?php
require 'vendor/autoload.php';

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;

function getPayPalClient() {
    $clientId = getenv('PAYPAL_CLIENT_ID');      // Thêm biến môi trường
    $clientSecret = getenv('PAYPAL_CLIENT_SECRET');

    $environment = new SandboxEnvironment($clientId, $clientSecret);
    return new PayPalHttpClient($environment);
}
