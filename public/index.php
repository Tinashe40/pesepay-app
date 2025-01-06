<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Tina\PesepayApp\PaymentGateway;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['amount'];
    $currency = $_POST['currency'];

    try {
        $gateway = new PaymentGateway();
        $payment = $gateway->createPayment($amount, $currency);

        // Redirect the user to complete payment
        header("Location: " . $payment['redirectUrl']);
        exit;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Pesepay Payment</title>
</head>

<body>
  <h1>Make a Donation</h1>
  <form method="POST" action="">
    <label for="amount">Amount:</label>
    <input type="number" name="amount" required>
    <label for="currency">Currency:</label>
    <input type="text" name="currency" required>
    <button type="submit">Donate Now</button>
  </form>
</body>

</html>