<?php
namespace App\Models\Auth;
use mysqli;

/**
 * RegisterModel – Handles user registration data access
 *
 * Responsibilities:
 * • Check if a user email already exists
 * • Insert a new user into the database
 *
 * Security & Practices:
 * • Uses prepared statements to prevent SQL injection
 * • Hashes passwords before storing
 */
class RegisterModel {

    public function __construct(private mysqli $conn) {}

    /**
     * Check if an email is already registered
     *
     * @param string $email Email address to check
     * @return bool True if email exists, false otherwise
     */
    public function emailExists(string $email): bool {
        $stmt = $this->conn->prepare("
            SELECT id FROM users WHERE email = ? 
            UNION 
            SELECT employee_id FROM employees WHERE email = ?
            LIMIT 1
        ");
        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param("ss", $email, $email);
        $stmt->execute();
        $exists = $stmt->get_result()->num_rows > 0;
        $stmt->close();

        return $exists;
    }

    /**
     * Register a new user
     *
     * @param string $username Username of the new user
     * @param string $email User email
     * @param string $password  password
     * @return bool True if insertion succeeded, false otherwise
     */
    public function registerUser(string $username, string $email, string $password): bool {
        // Hash the password for secure storage
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare(
            "INSERT INTO users (username, email, password) VALUES (?, ?, ?)"
        );
        $stmt->bind_param("sss", $username, $email, $hashed);
        $success = $stmt->execute();

        $stmt->close();

        return $success;
    }
}
