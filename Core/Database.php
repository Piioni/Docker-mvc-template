<?php

namespace Core;

use PDO;
use PDOException;

class Database
{
    private static ?Database $instance = null;

    private PDO $pdo {
        get {
            return $this->pdo;
        }
    }

    private function __construct()
    {
        $host = getenv('DB_HOST') ?: 'mysql';
        $db = getenv('DB_DATABASE') ?: 'mydb';
        $user = getenv('DB_USER') ?: 'root';
        $pass = getenv('DB_PASSWORD') ?: getenv('MYSQL_ROOT_PASSWORD') ?: 'rootpassword';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}
