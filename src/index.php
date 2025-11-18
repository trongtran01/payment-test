<?php
// Tắt warning deprecated (PHP 8.2+)
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
ob_start();
require 'vendor/autoload.php';

$stripeSecret = getenv('STRIPE_SECRET_KEY');
\Stripe\Stripe::setApiKey($stripeSecret);

$YOUR_DOMAIN = 'http://localhost:8000';

// Xử lý Stripe nếu form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_method'])) {
    $method = $_POST['payment_method'];

    if ($method === 'stripe') {
        // Stripe Checkout như trước
        try {
            $checkout_session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => ['name' => 'Test Product'],
                        'unit_amount' => 1000,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => $YOUR_DOMAIN . '/success.php',
                'cancel_url' => $YOUR_DOMAIN . '/cancel.php',
            ]);
            header('Location: ' . $checkout_session->url);
            exit;

        } catch (\Stripe\Exception\ApiErrorException $e) {
            $error = $e->getMessage();
        }

    } elseif ($method === 'cod') {
        // COD redirect thẳng sang success
        header('Location: ' . $YOUR_DOMAIN . '/success.php');
        exit;

    } elseif ($method === 'paypal') {
        require 'paypal-client.php';

        $client = getPayPalClient();
        $request = new \PayPalCheckoutSdk\Orders\OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "amount" => ["value" => "10.00","currency_code" => "USD"]
            ]],
            "application_context" => [
                "cancel_url" => $YOUR_DOMAIN . "/cancel.php",
                "return_url" => $YOUR_DOMAIN . "/paypal-success.php"
            ]
        ];

        try {
            $response = $client->execute($request);
            foreach ($response->result->links as $link) {
                if ($link->rel === 'approve') {
                    header('Location: ' . $link->href);
                    exit;
                }
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        } 
    } else {
        $message = "Phương thức {$method} đang phát triển, sẽ cập nhật sau.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Test Payment</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .payment-method { margin: 10px 0; }
        button { padding: 10px 20px; font-size: 16px; cursor: pointer; }
        .message { color: red; margin-top: 20px; }
    </style>
</head>
<body>
    <h1>Chọn phương thức thanh toán</h1>

    <form method="post">
        <div class="payment-method">
            <button type="submit" name="payment_method" value="cod">COD (Test)</button>
        </div>
        <div class="payment-method">
            <button type="submit" name="payment_method" value="stripe">Stripe (Test)</button>
        </div>
        <div class="payment-method">
            <button type="submit" name="payment_method" value="paypal">PayPal (Test)</button>
        </div>
        <div class="payment-method">
            <button type="submit" name="payment_method" value="vnpay" disabled>VN Pay (Coming soon)</button>
        </div>
        <div class="payment-method">
            <button type="submit" name="payment_method" value="momo" disabled>Momo (Coming soon)</button>
        </div>
    </form>

    <?php if (isset($error)) : ?>
        <div class="message">Lỗi Stripe: <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (isset($message)) : ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
</body>
</html>
