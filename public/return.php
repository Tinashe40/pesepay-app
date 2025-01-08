<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Tina\PesepayApp\PaymentGateway;
use Tina\PesepayApp\Database;

try {
    if (isset($_GET['referenceNumber'])) {
        $referenceNumber = $_GET['referenceNumber'];

        $gateway = new PaymentGateway();
        $status = $gateway->checkPaymentStatus($referenceNumber);

        // Update the database with the payment status
        $db = new Database();
        $donation = $db->getDonationByReference($referenceNumber);

        if ($donation) {
            $db->updateDonationStatus($referenceNumber, $status);
        }

        echo "<h1>Transaction Result</h1>";
        echo "<p>Reference Number: " . htmlspecialchars($referenceNumber) . "</p>";
        echo "<p>Status: " . htmlspecialchars($status) . "</p>";
    } else {
        echo "No reference number provided. Please ensure you were redirected from the payment page.";
    }
} catch (Exception $e) {
    echo "<h1>Error</h1>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
}
?>