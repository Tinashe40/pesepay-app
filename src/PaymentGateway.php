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

        // Initialize the Pesepay instance
        $this->pesepay = new Pesepay(
            $_ENV['PESEPAY_INTEGRATION_KEY'],
            $_ENV['PESEPAY_ENCRYPTION_KEY']
        );

        // Set return and result URLs
        $this->pesepay->returnUrl = $_ENV['RETURN_URL'];
        $this->pesepay->resultUrl = $_ENV['RESULT_URL'];
    }

    /**
     * Create a payment transaction and get the redirect URL.
     *
     * @param float $amount The amount to be paid.
     * @param string $currency The currency code (e.g., USD, ZWL).
     * @param string $reason The reason for the payment (default: 'Donation').
     * @return array Contains redirectUrl, referenceNumber, and pollUrl.
     * @throws \Exception If the payment initiation fails.
     */
    public function createPayment(float $amount, string $currency, string $reason = "Donation"): array
    {
        try {
            // Create the transaction
            $transaction = $this->pesepay->createTransaction($amount, $currency, $reason);

            // Initiate the transaction
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
        } catch (\Exception $e) {
            throw new \Exception("Error creating payment: " . $e->getMessage());
        }
    }

    /**
     * Check the status of a payment by reference number.
     *
     * @param string $referenceNumber The reference number of the transaction.
     * @return string Payment status ('Paid' or 'Not Paid').
     * @throws \Exception If the payment status check fails.
     */
    public function checkPaymentStatus(string $referenceNumber): string
    {
        try {
            $response = $this->pesepay->checkPayment($referenceNumber);

            if ($response->success()) {
                return $response->paid() ? 'Paid' : 'Not Paid';
            } else {
                throw new \Exception("Payment status check failed: " . $response->message());
            }
        } catch (\Exception $e) {
            throw new \Exception("Error checking payment status for reference $referenceNumber: " . $e->getMessage());
        }
    }
}