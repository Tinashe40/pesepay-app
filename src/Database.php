<?php

namespace Tina\PesepayApp;

use PDO;
use PDOException;

class Database
{
    private $connection;

    public function __construct()
    {
        $this->connect();
    }

    private function connect()
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();

        $host = $_ENV['DB_HOST'];
        $port = $_ENV['DB_PORT'];
        $dbname = $_ENV['DB_DATABASE'];
        $username = $_ENV['DB_USERNAME'];
        $password = $_ENV['DB_PASSWORD'];

        try {
            $this->connection = new PDO(
                "pgsql:host=$host;port=$port;dbname=$dbname",
                $username,
                $password
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function insertDonation($amount, $currencyCode, $paymentStatus, $referenceNumber)
    {
        $sql = "INSERT INTO donations (amount, currency_code, payment_status, reference_number) 
                VALUES (:amount, :currencyCode, :paymentStatus, :referenceNumber)";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            ':amount' => $amount,
            ':currencyCode' => $currencyCode,
            ':paymentStatus' => $paymentStatus,
            ':referenceNumber' => $referenceNumber
        ]);
    }

    public function getDonationByReference($referenceNumber)
    {
        $sql = "SELECT * FROM donations WHERE reference_number = :referenceNumber";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':referenceNumber' => $referenceNumber]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateDonationStatus($referenceNumber, $status)
    {
        $sql = "UPDATE donations SET payment_status = :status WHERE reference_number = :referenceNumber";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            ':status' => $status,
            ':referenceNumber' => $referenceNumber
        ]);
    }
}