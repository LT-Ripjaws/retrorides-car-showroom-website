<?php
namespace App\Models\Auth;
use mysqli;

/**
 * LoginModel – Data access layer for user authentication
 *
 * Responsibilities:
 * • Fetch employee or customer record by email
 * • Return associative arrays with necessary authentication fields
 *
 * Security & Practices:
 * • Used prepared statements to prevent SQL injection
 */
class LoginModel {

    public function __construct(private mysqli $conn) {}

    /**
     * Find an employee by email
     *
     * @param string $email Employee email to search
     * @return array|null Associative array with employee data or null if not found
     */
    public function findEmployeeByEmail(string $email): ?array {
        
        $stmt = $this->conn->prepare(
            "SELECT employee_id AS id, name, email, password, role, status 
             FROM employees WHERE email = ? LIMIT 1"
        );

        if (!$stmt) return null; // Return null if preparation fails

    
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        $stmt->close();

        return $row ?: null; // Return null if no record found
    }

    /**
     * Find a customer by email
     *
     * @param string $email Customer email to search
     * @return array|null Associative array with customer data or null if not found
     */
    public function findCustomerByEmail(string $email): ?array {
    
        $stmt = $this->conn->prepare(
            "SELECT id, username AS name, email, password, status 
             FROM users WHERE email = ? LIMIT 1"
        );

        if (!$stmt) return null; 

    
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        $stmt->close();

        return $row ?: null; 
    }
}
