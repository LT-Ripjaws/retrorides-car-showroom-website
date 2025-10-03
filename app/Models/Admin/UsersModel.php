<?php
namespace App\Models\Admin;
use mysqli;

/**
 * UsersModel – Data access layer for managing users or customers who have registered
 *
 * Responsibilities:
 * • Retrieve all users
 * • Change their status (active/inactive)
 * • Update roles (customer, premium, VIP)
 */

class UsersModel
{
    public function __construct(private mysqli $conn)
    {
    }

    /**
     * Get all users with basic info for admin display
     * Orders by creation date, newest first
     */
    public function getAllUsers(): array
    {
        $stmt = $this->conn->prepare("SELECT id, username, email, role, status, created_at, updated_at 
                                      FROM users 
                                      ORDER BY created_at DESC");
        $stmt->execute();
        $result = $stmt->get_result();
        
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        
        $stmt->close();
        return $users;
    }

    /**
     * Get single user by ID
     */
    public function getUserById(int $userId): ?array
    {
        $stmt = $this->conn->prepare("SELECT id, username, email, role, status, created_at, updated_at 
                                      FROM users 
                                      WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        
        return $user ?: null;
    } 
    
    /**
     * Update user role only
     * Used for quick role changes by admin
     */
    public function updateUserRole(int $userId, string $role): bool
    {
        $stmt = $this->conn->prepare("UPDATE users SET role = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("si", $role, $userId);
        $success = $stmt->execute();
        $stmt->close();
        
        return $success;
    }

    /**
     * Get valid user roles
     */
    public function getValidRoles(): array
    {
        return ['customer', 'premium', 'vip'];
    }

    /**
     * Update only user status (active/inactive)
     * Used for quick toggle functionality
     */
    public function updateUserStatus(int $userId, string $status): bool
    {
        $stmt = $this->conn->prepare("UPDATE users SET status = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("si", $status, $userId);
        $success = $stmt->execute();
        $stmt->close();
        
        return $success;
    }

    /**
     * Soft delete user by setting status to inactive
     * Preserves data for booking history  (ik the update user status and this kinda does the same thing since we aint really fully deleting users
     * guess we can merge them later if needed)
     */
    public function deleteUser(int $userId): bool
    {
        $stmt = $this->conn->prepare("UPDATE users SET status = 'inactive', updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $success = $stmt->execute();
        $stmt->close();
        
        return $success;
    }

}