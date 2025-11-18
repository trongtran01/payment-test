<?php
require 'vendor/autoload.php';
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

$YOUR_DOMAIN = 'http://localhost:8000';
$orderId = $_GET['token'] ?? '';

if ($orderId) {
    $clientId = getenv('PAYPAL_CLIENT_ID');
    $clientSecret = getenv('PAYPAL_CLIENT_SECRET');
    $env = new SandboxEnvironment($clientId, $clientSecret);
    $client = new PayPalHttpClient($env);

    $request = new OrdersCaptureRequest($orderId);
    $request->prefer('return=representation');

    try {
        $response = $client->execute($request);
        echo "<h1>Thanh toán PayPal thành công!</h1>";
        echo "<a href='index.php'>Quay lại</a>";
    } catch (Exception $e) {
        echo "<h1>Thanh toán thất bại!</h1>";
        echo "<p>" . $e->getMessage() . "</p>";
    }
} else {
    echo "<h1>Không có order ID!</h1>";
}
