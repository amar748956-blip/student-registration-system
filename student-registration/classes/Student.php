<?php
/**
 * Student Model — encapsulates all DB operations (CRUD + search).
 *
 * Demonstrates OOP: encapsulation, single-responsibility, prepared statements.
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/Database.php';

class Student
{
    private PDO $db;
    private string $table = 'students';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Create a new student record.
     *
     * @param array<string,mixed> $data
     */
    public function create(array $data): bool
    {
        $sql = "INSERT INTO {$this->table}
                    (full_name, email, phone, gender, dob, country, skills, address, profile_image)
                VALUES
                    (:full_name, :email, :phone, :gender, :dob, :country, :skills, :address, :profile_image)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':full_name'     => $data['full_name'],
            ':email'         => $data['email'],
            ':phone'         => $data['phone'],
            ':gender'        => $data['gender'],
            ':dob'           => $data['dob'],
            ':country'       => $data['country'],
            ':skills'        => $data['skills'],
            ':address'       => $data['address'],
            ':profile_image' => $data['profile_image'],
        ]);
    }

    /**
     * Return all students, optionally filtered by a search term.
     *
     * @return array<int,array<string,mixed>>
     */
    public function getAll(string $search = ''): array
    {
        if ($search !== '') {
            $sql = "SELECT * FROM {$this->table}
                    WHERE full_name LIKE :s
                       OR email   LIKE :s
                       OR phone   LIKE :s
                       OR country LIKE :s
                    ORDER BY id DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':s' => '%' . $search . '%']);
        } else {
            $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY id DESC");
        }

        return $stmt->fetchAll();
    }

    /**
     * Find a single student by ID.
     *
     * @return array<string,mixed>|null
     */
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        return $row !== false ? $row : null;
    }

    /**
     * Update an existing student record.
     *
     * @param array<string,mixed> $data
     */
    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE {$this->table} SET
                    full_name     = :full_name,
                    email         = :email,
                    phone         = :phone,
                    gender        = :gender,
                    dob           = :dob,
                    country       = :country,
                    skills        = :skills,
                    address       = :address,
                    profile_image = :profile_image
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':full_name'     => $data['full_name'],
            ':email'         => $data['email'],
            ':phone'         => $data['phone'],
            ':gender'        => $data['gender'],
            ':dob'           => $data['dob'],
            ':country'       => $data['country'],
            ':skills'        => $data['skills'],
            ':address'       => $data['address'],
            ':profile_image' => $data['profile_image'],
            ':id'            => $id,
        ]);
    }

    /**
     * Delete a student record by ID.
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");

        return $stmt->execute([':id' => $id]);
    }

    /**
     * Check whether an email already exists (optionally excluding one ID).
     */
    public function emailExists(string $email, int $excludeId = 0): bool
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE email = :email AND id != :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email, ':id' => $excludeId]);

        return (int) $stmt->fetchColumn() > 0;
    }
}
