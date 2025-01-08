<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Tina\PesepayApp\PaymentGateway;
use Tina\PesepayApp\Database;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['amount'];
    $currency = $_POST['currency'];

    try {
        $gateway = new PaymentGateway();
        $payment = $gateway->createPayment($amount, $currency);

        // Save the donation record to the database
        $db = new Database();
        $db->insertDonation($amount, $currency, 'Pending', $payment['referenceNumber']);

        // Redirect the user to complete payment
        header("Location: " . $payment['redirectUrl']);
        exit;
    } catch (Exception $e) {
        echo "Error: " . htmlspecialchars($e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Donate Now</title>
  <style>
  /* Your existing CSS */
  </style>
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