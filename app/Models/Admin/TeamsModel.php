<?php
namespace App\Models\Admin;
use mysqli;

/**
 * TeamsModel – Data access layer for managing employees/teams
 *
 * Responsibilities:
 * • Retrieve all employees
 * • Add, update, and delete employees
 * • Validate unique emails
 * • Provide lists of valid roles, departments, and statuses
 *
 * Security & Practices:
 * • All queries use prepared statements to prevent SQL injection
 * • Passwords are hashed before storing in database
 */
class TeamsModel 
{

    public function __construct(private mysqli $conn) 
    {

    }

    /**
     * Fetch all employees with basic info
     *
     * @return array List of employees as associative arrays
     */
    public function getAllEmployees(): array 
    {
        $stmt = $this->conn->prepare("
            SELECT employee_id, name, email, phone, role, department, status, joined 
            FROM employees 
            ORDER BY joined DESC
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        $employees = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $employees;
    }

    /**
     * Check if an email already exists
     * Can exclude a specific employee ID when updating
     *
     * @param string $email Email to check
     * @param int|null $excludeId Employee ID to exclude from check
     * @return bool True if email exists, false otherwise
     */
    public function emailExists(string $email, ?int $excludeId = null): bool 
    {
        if ($excludeId) {
            $stmt = $this->conn->prepare("
                SELECT employee_id 
                FROM employees 
                WHERE email = ? AND employee_id != ?
            ");
            $stmt->bind_param("si", $email, $excludeId);
        } else {
            $stmt = $this->conn->prepare("
                SELECT employee_id 
                FROM employees 
                WHERE email = ?
            ");
            $stmt->bind_param("s", $email);
        }

        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();

        return $exists;
    }

    /**
     * Add a new employee with default password
     *
     * @param array $data Associative array with keys: name, email, phone, role, department, status
     * @return bool True on success, false on failure
     */
    public function addEmployee(array $data): bool 
    {
        // Default password for new employees ( can be changed as needed depending on mood ig )
        $defaultPassword = "default123";
        $hashPassword = password_hash($defaultPassword, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare("
            INSERT INTO employees (name, email, password, phone, role, department, joined, status)
            VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)
        ");

        $stmt->bind_param(
            "sssssss", 
            $data['name'], 
            $data['email'], 
            $hashPassword, 
            $data['phone'], 
            $data['role'], 
            $data['department'], 
            $data['status']
        );

        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    /**
     * Update employee information
     *
     * @param int $id Employee ID
     * @param array $data Associative array with keys: name, email, phone, role, department, status
     * @return bool True on success, false on failure
     */
    public function updateEmployee(int $id, array $data): bool 
    {
        $stmt = $this->conn->prepare("
            UPDATE employees
            SET name = ?, email = ?, phone = ?, role = ?, department = ?, status = ?
            WHERE employee_id = ?
        ");

        $stmt->bind_param(
            "ssssssi", 
            $data['name'], 
            $data['email'], 
            $data['phone'], 
            $data['role'], 
            $data['department'], 
            $data['status'],
            $id
        );

        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    /**
     * Delete an employee by ID
     *
     * @param int $id Employee ID
     * @return bool True on success, false on failure
     */
    public function deleteEmployee(int $id): bool 
    {
        $stmt = $this->conn->prepare("DELETE FROM employees WHERE employee_id = ?");
        $stmt->bind_param("i", $id);

        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    /**
     * Get list of valid employee roles
     *
     * @return array Valid roles
     */
    public function getValidRoles(): array 
    {
        return ['sales', 'mechanic', 'support', 'admin'];
    }

    /**
     * Get list of valid departments
     *
     * @return array Valid departments
     */
    public function getValidDepartments(): array 
    {
        return ['sales', 'mechanic', 'finance', 'admin'];
    }

    /**
     * Get list of valid employee statuses
     *
     * @return array Valid statuses
     */
    public function getValidStatuses(): array 
    {
        return ['active', 'inactive', 'leave'];
    }
}
