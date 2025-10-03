<?php
namespace App\Controllers\Admin;

use App\Core\BaseController;
use App\Models\Admin\TeamsModel;

/**
 * TeamsController - Admin employee & team management
 *
 * Provides administrative oversight for team members:
 * - Display and filtering of all employees in the admin dashboard
 * - Creation of new employee records with role/department validation
 * - Secure updating of employee details with email uniqueness checks
 * - Deletion of employees with safeguards against self-removal (imagine losing admin access lol cuz yo stoopid)
 * - Centralized CSRF protection for all state-changing requests
 * - Consistent success/error feedback for user interface display
 *
 * Business Rules Enforced:
 * - All employee fields (name, email, phone, role, department, status) are required
 * - Email must be unique across employees; updates ignore current employee's own email
 * - Phone numbers must follow a basic numeric/international format (7–15 digits)
 * - Role, department, and status must match predefined valid lists from the model
 * - Admins cannot delete their own account to preserve system access
 *
 * Security Features:
 * - Admin role verification for every endpoint
 * - Unique CSRF token generation and strict validation on POST requests
 * - Input sanitization and validation to prevent injection or malformed data
 * - Safe redirects with encoded query parameters for status reporting
 */
class TeamsController extends BaseController
{
    public function __construct(private TeamsModel $model)
    {
        /**
         * Controller-specific error messages
         * Merged with BaseController's common messages
         */
        $this->errorMessages = [
        'email_exists' => 'Email address is already in use.',
        'invalid_value' => 'Invalid role, department, or status selection.',
        ];

        /**
         * Controller-specific success messages
         * Maps URL parameters to display messages
         */
        $this->successMessages = [
        'added' => 'Employee added successfully!',
        'updated' => 'Employee updated successfully!',
        'deleted' => 'Employee deleted successfully!',
        ];
    }

    /**
     * Teams management page
     * Displays all employees for admin users
     */
    public function index(): void
    {
        requireRole('admin');
        generateCSRFToken();

        $employees = $this->model->getAllEmployees();
        $this->renderView('Admin/TeamsView.php', [
        'employees' => $employees,
        'title' => 'Manage ur team',
        'pageCSS' => '/assets/css/Admin/team.css'
    ], 'admin');
    }

    /* ---------------------------- ACTIONS ------------------------------ */

    /**
     * Add a new employee
     * Validates data before adding tho
     */
    public function addEmployee(): void
    {
        requireRole('admin');

        $this->handleCrudOperation(
            fn() => $this->processAddEmployee(),
            'admin/teams'
        );
    }

    /**
     * Update an existing employee
     */
    public function updateEmployee(): void
    {
        requireRole('admin');

        if (!$this->isValidPostRequest(['employee_id'])) {
            $this->redirectWithStatus('admin/teams');
            return;
        }

        $this->handleCrudOperation(
            fn() => $this->processUpdateEmployee(),
            'admin/teams'
        );
    }

    /**
     * Delete an employee
     * Prevents admin from deleting themselves
     */
    public function deleteEmployee(): void
    {
        requireRole('admin');

        if (!$this->isValidPostRequest(['employee_id'])) {
            $this->redirectWithStatus('admin/teams');
            return;
        }

        $this->handleCrudOperation(
            fn() => $this->processDeleteEmployee(),
            'admin/teams'
        );
    }


    /* ---------------------------- Methods for those actions ------------------------------ */

    /**
     * Process adding a new employee
     * Validates ID, data, and email uniqueness (excluding current employee)
     */
    private function processAddEmployee(): void
    {
        $employeeData = $this->validateEmployeeData();
        if (!$employeeData) {
            return; // Error already set
        }

        // Business rule: email must be unique
        if ($this->model->emailExists($employeeData['email'])) {
            $this->setError('email_exists');
            return;
        }

        // Attempt to create employee
        if ($this->model->addEmployee($employeeData)) {
            $this->setSuccess('added');
        } else {
            $this->setError('insert_failed');
        }
    }

    /**
     * Process updating an existing employee
     * Validates ID and ensures email uniqueness for other employees
     */
    private function processUpdateEmployee(): void
    {
        $employeeId = $this->validateId('employee_id');
        if (!$employeeId) {
            return; // Error already set by validateId
        }

        $employeeData = $this->validateEmployeeData();
        if (!$employeeData) {
            return; // Error already set
        }

        // Business rule: email must be unique
        if ($this->model->emailExists($employeeData['email'], $employeeId)) {
            $this->setError('email_exists');
            return;
        }

        // Attempt to update employee
        if ($this->model->updateEmployee($employeeId, $employeeData)) {
            $this->setSuccess('updated');
        } else {
            $this->setError('update_failed');
        }
    }

    /**
     * Process deleting an employee
     * Prevents self-deletion to maintain admin access
     */
    private function processDeleteEmployee(): void
    {
        $employeeId = $this->validateId('employee_id');
        if (!$employeeId) {
            return; // Error already set by validateId
        }

        // Business rule: prevent admin from deleting themselves
        if ($this->isSelfModification($employeeId)) {
            $this->setError('cannot_delete_self');
            return;
        }

        // Attempt to delete employee
        if ($this->model->deleteEmployee($employeeId)) {
            $this->setSuccess('deleted');
        } else {
            $this->setError('delete_failed');
        }
    }

    /**
     * Validate and sanitize employee form data
     * Combines BaseController validation with business-specific rules
     */
    private function validateEmployeeData(): ?array
    {
        //BaseController methods for basic validation
        $name = $this->validateString('name');
        $email = $this->validateEmail('email');
        $phone = $this->validateString('phone');
        $role = $this->validateString('role');
        $department = $this->validateString('department');
        $status = $this->validateString('status');

        // Check if any basic validation failed
        if (!$name || !$email || !$phone || !$role || !$department || !$status) {
            return null; // Error already set by validation methods
        }

        // Business rule: validate phone format
        if (!preg_match('/^[0-9+\-\s]{7,15}$/', $phone)) {
            $this->setError('invalid_phone');
            return null;
        }

        // Business rule: validate against allowed values from model
        $validRoles = $this->model->getValidRoles();
        $validDepartments = $this->model->getValidDepartments();
        $validStatuses = $this->model->getValidStatuses();

        if (!in_array($role, $validRoles, true) ||
            !in_array($department, $validDepartments, true) ||
            !in_array($status, $validStatuses, true)) {
            $this->setError('invalid_value');
            return null;
        }

        return [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'role' => $role,
            'department' => $department,
            'status' => $status
        ];
    }

    
}
