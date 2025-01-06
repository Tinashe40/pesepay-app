<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Tina\PesepayApp\PaymentGateway;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $referenceNumber = $_POST['referenceNumber'];

    try {
        $gateway = new PaymentGateway();
        $status = $gateway->checkPaymentStatus($referenceNumber);
        echo "Payment Status: " . $status;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}