<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Tina\PesepayApp\PaymentGateway;

try {
    // Retrieve reference number from the query parameter
    if (isset($_GET['referenceNumber'])) {
        $referenceNumber = $_GET['referenceNumber'];

        // Create an instance of the PaymentGateway class
        $gateway = new PaymentGateway();

        // Check the payment status using the reference number
        $status = $gateway->checkPaymentStatus($referenceNumber);

        // Display transaction details and status
        echo "<h1>Transaction Result</h1>";
        echo "<p>Reference Number: " . htmlspecialchars($referenceNumber) . "</p>";
        echo "<p>Status: " . htmlspecialchars($status) . "</p>";
    } else {
        echo "No reference number provided. Please ensure you were redirected from the payment page.";
    }
} catch (Exception $e) {
    // Display error message if any exception occurs
    echo "<h1>Error</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
}