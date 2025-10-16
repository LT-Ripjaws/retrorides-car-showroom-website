<?php 
namespace App\Models\Customer;
use mysqli;

/**
 * CustomerProfileModel - Handles customer profile data operations
 * 
 * This model manages customer profile functionality:
 * - Fetching user profile information
 * - Updating profile details (username, email)
 * - Changing password with validation
 * - Email uniqueness verification
 * 
 * Security Features:
 * - Password verification before changes
 * - Secure password hashing (default algorithm)
 * - Email uniqueness validation
 * - No password exposure in queries
 */

class CustomerProfileModel{

    public function __construct(private mysqli $conn)
    {

    }

    // Through this function we get the user profile details.
    public function getUserProfile(int $userId):?array
    {
        $stmt = $this->conn->prepare("SELECT id, username, email, role, status, created_at FROM 
        users WHERE id = ? LIMIT 1");

        if(!$stmt){
            error_log("CustomerProfileModel::getUserProfile - Prepare failed: " . $this->conn->error);
            return null;
        }

        $stmt->bind_param("i",$userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user ?: null;
    }

    // Check if email is taken by another user (excluding the current user)
    public function isEmailTaken(string $email, int $excludeUserId): bool
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM users WHERE email = ? AND id != ?");
        if(!$stmt){
            error_log("CustomerProfileModel::isEmailTaken - Prepare failed: " . $this->conn->error);
            return true;
        }

        $stmt->bind_param("si", $email, $excludeUserId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['count'] > 0;
    }


    // Update user profile details (username, email)
    public function updateProfile(array $data): bool
    {
        $stmt = $this->conn->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        if(!$stmt){
            error_log("CustomerProfileModel::updateProfile - Prepare failed: " . $this->conn->error);
            return false;
        }

        $stmt->bind_param("ssi", $data['username'], $data['email'], $data['user_id']);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

// Verify current password before allowing password change
    public function checkCurrentPassword(int $userId, string $currentPassword): bool
    {
        $stmt = $this->conn->prepare("SELECT password FROM users WHERE id = ? LIMIT 1");
        if(!$stmt){
            error_log("CustomerProfileModel::checkCurrentPassword - Prepare failed: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        if(!$user){
            return false;
        }
        return password_verify($currentPassword, $user['password']);
    }

// Update user password
    public function updatePassword(int $userId, string $newPassword): bool{
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        if(!$stmt){
            error_log("CustomerProfileModel::updatePassword - Prepare failed: " . $this->conn->error);
            return false;
        }

        $stmt->bind_param("si", $hashedPassword, $userId);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
}