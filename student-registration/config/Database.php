<?php
/**
 * Database Configuration & Connection (OOP / Singleton)
 *
 * Uses PDO with prepared statements to prevent SQL injection.
 */

declare(strict_types=1);

class Database
{
    private string $host = 'localhost';
    private string $dbName = 'student_registration';
    private string $username = 'root';
    private string $password = '';
    private string $charset = 'utf8mb4';

    private ?PDO $connection = null;
    private static ?Database $instance = null;

    /**
     * Private constructor enforces the singleton pattern.
     */
    private function __construct()
    {
        $dsn = "mysql:host={$this->host};dbname={$this->dbName};charset={$this->charset}";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->connection = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            // Do not leak credentials/details to the user.
            die('Database connection failed. Please check your configuration.');
        }
    }

    /**
     * Get the single shared Database instance.
     */
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Return the active PDO connection.
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }

    /**
     * Prevent cloning of the singleton.
     */
    private function __clone() {}
}
