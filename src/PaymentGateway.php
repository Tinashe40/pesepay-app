<?php

namespace Tina\PesepayApp;

use Codevirtus\Payments\Pesepay;

class PaymentGateway
{
    private $pesepay;

    public function __construct()
    {
        // Load environment variables
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();

        $this->pesepay = new Pesepay(
            $_ENV['PESEPAY_INTEGRATION_KEY'],
            $_ENV['PESEPAY_ENCRYPTION_KEY']
        );

        $this->pesepay->returnUrl = "http://localhost:8000/public/return.php";
        $this->pesepay->resultUrl = $_ENV['RESULT_URL'];
    }

    public function createPayment($amount, $currency)
    {
        $reason = "Donation"; // Fixed reason
        $transaction = $this->pesepay->createTransaction($amount, $currency, $reason);

        $response = $this->pesepay->initiateTransaction($transaction);

        if ($response->success()) {
            return [
                'redirectUrl' => $response->redirectUrl(),
                'referenceNumber' => $response->referenceNumber(),
                'pollUrl' => $response->pollUrl()
            ];
        } else {
            throw new \Exception("Payment initiation failed: " . $response->message());
        }
    }

    public function checkPaymentStatus($referenceNumber)
    {
        $response = $this->pesepay->checkPayment($referenceNumber);

        if ($response->success()) {
            return $response->paid() ? 'Paid' : 'Not Paid';
        } else {
            throw new \Exception("Payment status check failed: " . $response->message());
        }
    }
}