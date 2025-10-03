<?php
namespace App\Models\Admin;

use mysqli;

/**
 * ProfileModel – Data access layer for admin profile management
 *
 * Responsibilities:
 * • Retrieve admin/employee profile information
 * • Update profile details and passwords
 * • Check email uniqueness for updates
 *
 * Security & Practices:
 * • Uses prepared statements for all queries to prevent SQL injection
 * • Returns null for missing records to safely handle non-existent IDs
 */
class ProfileModel 
{

    /**
     * Constructor
     */
    public function __construct(private mysqli $conn) 
    {
    }

    /**
     * Fetch admin profile data by ID
     *
     * @param int $adminId Admin ID
     * @return array|null Associative array of profile data, or null if not found
     */
    public function getAdminProfile(int $adminId): ?array 
    {
        $stmt = $this->conn->prepare("
            SELECT employee_id, name, email, phone, role, department, joined, status, created_at, updated_at 
            FROM employees 
            WHERE employee_id = ?
        ");
        $stmt->bind_param("i", $adminId);
        $stmt->execute();

        // Fetch result as associative array
        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();

        $stmt->close();

        // Return null if employee not found
        return $admin ?: null;
    }

    /**
     * Get the current hashed password of an admin
     *
     * @param int $adminId admin ID
     * @return string|null Hashed password or null if not found
     */
    public function getCurrentPassword(int $adminId): ?string 
    {
        $stmt = $this->conn->prepare("SELECT password FROM employees WHERE employee_id = ?");
        $stmt->bind_param("i", $adminId);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $stmt->close();

        return $row['password'] ?? null;
    }

    /**
     * Update basic profile information (name, email, phone)
     *
     * @param int $adminId admin ID
     * @param array $data Associative array with keys 'name', 'email', 'phone'
     * @return bool True on success, false on failure
     */
    public function updateProfile(int $adminId, array $data): bool 
    {
        $stmt = $this->conn->prepare("
            UPDATE employees 
            SET name = ?, email = ?, phone = ?, updated_at = NOW() 
            WHERE employee_id = ?
        ");
        $stmt->bind_param(
            "sssi", 
            $data['name'], 
            $data['email'], 
            $data['phone'], 
            $adminId
        );

        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    /**
     * Update password for an admin
     *
     * @param int $adminId admin ID
     * @param string $hashedPassword New password (already hashed)
     * @return bool True on success, false on failure
     */
    public function updatePassword(int $adminId, string $hashedPassword): bool 
    {
        $stmt = $this->conn->prepare("
            UPDATE employees 
            SET password = ?, updated_at = NOW() 
            WHERE employee_id = ?
        ");
        $stmt->bind_param("si", $hashedPassword, $adminId);

        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    /**
     * Check if an email is already used by another employee (for uniqueness validation)
     *
     * @param string $email Email to check
     * @param int $excludeId Employee ID to exclude from check (current user)
     * @return bool True if email exists for another user, false otherwise
     */
    public function emailExistsForOtherUser(string $email, int $excludeId): bool 
    {
        $stmt = $this->conn->prepare("
            SELECT employee_id 
            FROM employees 
            WHERE email = ? AND employee_id != ?
        ");
        $stmt->bind_param("si", $email, $excludeId);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;

        $stmt->close();

        return $exists;
    }
}
