<?php
/**
 * Database Configuration & Connection (OOP / Singleton)
 *
 * Uses PDO with prepared statements to prevent SQL injection.
 *
 * Primary target: MySQL (see database.sql). For environments where a MySQL
 * server is not available (e.g. a quick local/preview run), the class
 * transparently falls back to a self-initializing SQLite database so the
 * application can run without any external service. Production deployments
 * should always use MySQL.
 */

declare(strict_types=1);

class Database
{
    // --- MySQL settings (production / submission target) ---
    private string $host = 'localhost';
    private string $dbName = 'student_registration';
    private string $username = 'root';
    private string $password = '';
    private string $charset = 'utf8mb4';

    private ?PDO $connection = null;
    private string $driver = 'mysql';
    private static ?Database $instance = null;

    /**
     * Private constructor enforces the singleton pattern.
     */
    private function __construct()
    {
        // Allow forcing SQLite via env (used for the live preview sandbox).
        $forceSqlite = getenv('DB_DRIVER') === 'sqlite';

        if (!$forceSqlite && $this->tryMysql()) {
            return;
        }

        $this->connectSqlite();
    }

    /**
     * Attempt a MySQL connection. Returns true on success.
     */
    private function tryMysql(): bool
    {
        $dsn = "mysql:host={$this->host};dbname={$this->dbName};charset={$this->charset}";

        try {
            $this->connection = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
            $this->driver = 'mysql';

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Connect to a local SQLite file and ensure the schema exists.
     */
    private function connectSqlite(): void
    {
        try {
            $path = __DIR__ . '/../data/students.sqlite';
            if (!is_dir(dirname($path))) {
                mkdir(dirname($path), 0775, true);
            }

            $this->connection = new PDO('sqlite:' . $path, null, null, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            $this->driver = 'sqlite';
            $this->initSqliteSchema();
        } catch (PDOException $e) {
            die('Database connection failed. Please check your configuration.');
        }
    }

    /**
     * Create the students table (and seed sample rows) for SQLite if needed.
     */
    private function initSqliteSchema(): void
    {
        $this->connection->exec(
            'CREATE TABLE IF NOT EXISTS students (
                id            INTEGER PRIMARY KEY AUTOINCREMENT,
                full_name     TEXT    NOT NULL,
                email         TEXT    NOT NULL UNIQUE,
                phone         TEXT    NOT NULL,
                gender        TEXT    NOT NULL,
                dob           TEXT    NOT NULL,
                country       TEXT    NOT NULL,
                skills        TEXT,
                address       TEXT,
                profile_image TEXT,
                created_at    TEXT    DEFAULT CURRENT_TIMESTAMP,
                updated_at    TEXT    DEFAULT CURRENT_TIMESTAMP
            )'
        );

        $count = (int) $this->connection->query('SELECT COUNT(*) FROM students')->fetchColumn();
        if ($count === 0) {
            $this->connection->exec(
                "INSERT INTO students (full_name, email, phone, gender, dob, country, skills, address) VALUES
                    ('John Doe', 'john@example.com', '9876543210', 'Male', '1998-05-12', 'India', 'PHP, MySQL, JavaScript', '123 Main Street, Mumbai'),
                    ('Jane Smith', 'jane@example.com', '9123456780', 'Female', '2000-09-23', 'United States', 'HTML, CSS, React', '45 Oak Avenue, New York')"
            );
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
     * Return the active driver name ('mysql' or 'sqlite').
     */
    public function getDriver(): string
    {
        return $this->driver;
    }

    /**
     * Prevent cloning of the singleton.
     */
    private function __clone() {}
}
