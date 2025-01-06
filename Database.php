<?php

namespace Tina\PesepayApp;

use PDO;
use Exception;

class Database
{
    private $pdo;
    
    public function __construct()
    {
        // Get the database URL from environment variables (for Railway)
        $dbUrl = getenv('DATABASE_URL');

        if ($dbUrl) {
            try {
                // Connect to PostgreSQL using PDO
                $this->pdo = new PDO($dbUrl);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (Exception $e) {
                die("Connection failed: " . $e->getMessage());
            }
        } else {
            die("Database URL is not set.");
        }
    }

    public function getConnection()
    {
        return $this->pdo;
    }
}