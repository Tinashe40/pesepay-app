<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Tina\PesepayApp\PaymentGateway;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $input = json_decode(file_get_contents('php://input'), true);

        $amount = $input['amount'];
        $currency = $input['currency'];
        $reason = $input['reason'];
        $resultUrl = $input['resultUrl'];
        $returnUrl = $input['returnUrl'];
        $merchantReference = $input['merchantReference'];
        $customer = $input['customer'];

        $gateway = new PaymentGateway();
        $response = $gateway->makePayment(
            $amount,
            $currency,
            $reason,
            $resultUrl,
            $returnUrl,
            $merchantReference,
            $customer
        );

        echo json_encode([
            "success" => true,
            "data" => $response
        ]);
    } catch (Exception $e) {
        echo json_encode([
            "success" => false,
            "message" => $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
    ]);
}