<?php
namespace App\Controllers\Admin;

use App\Core\BaseController;
use App\Models\Admin\UsersModel;

/**
 * UsersController – Administrative User Management
 *
 * Controller for all user(customer)-related administration tasks.  
 *
 * Features & Operations:
 * - **User Listing:** Retrieve and display all registered users for monitoring in the admin dashboard.
 * - **Status Toggle:** Activate or deactivate a user account while preserving their data history.
 * - **Role Updates:** Change user roles between customer, premium, and vip levels.
 * - **Soft Deletion:** Mark an account as inactive (instead of permanent removal) for audit and booking records.
 *  (if you really wanna delete someone just do it from the db as well as their bookings)
 *
 * Business Rules Enforced:
 * - Only users with the **admin** role can access any method in this controller.
 *
 * Security Measures:
 * - **CSRF Protection:** All state-changing operations validate CSRF tokens.
 * - **Input Validation:** User IDs are filtered and validated as integers before database interaction.
 */
class UsersController extends BaseController
{
    public function __construct(private UsersModel $model)
    {
        /**
         * Controller-specific error messages
         * Merged with BaseController's common messages
         */
        $this->errorMessages = [
        'user_not_found' => 'The requested user could not be found.',
        'cannot_deactivate_self' => 'You cannot deactivate your own account.',
        'invalid_role' => 'Invalid role selected. Please choose a valid role.',
        ];

        /**
         * Controller-specific success messages
         */
        $this->successMessages = [
        'activated' => 'User activated successfully!',
        'deactivated' => 'User deactivated successfully!',
        'role_updated' => 'User role updated successfully!',
        'deleted' => 'User removed successfully!',
        ];
    }

    /**
     * User management page
     * Displays all users for monitoring
     */
    public function index(): void
    {
        requireRole('admin');
        generateCSRFToken();

        $users = $this->model->getAllUsers();
        $this->renderView('Admin/UsersView.php', [
            'users' => $users,
            'validRoles' => $this->model->getValidRoles(),
            'title' => 'User Management',
            'pageCSS' => '/assets/css/Admin/team.css'
        ], 'admin');
    }

    /* ---------------------------- ACTIONS ------------------------------ */

    
    /**
     * Update user status (active/inactive)
     * Toggles between active and inactive states
     */
    public function updateStatus(): void
    {
        requireRole('admin');

        if (!$this->isValidPostRequest(['user_id'])) {
            $this->redirectWithStatus('admin/users');
            return;
        }

        $this->handleCrudOperation(
            fn() => $this->processUpdateStatus(),
            'admin/users'
        );
    }

    /**
     * Update user role (customer, premium, vip)
     */
    public function updateRole(): void
    {
        requireRole('admin');

        if (!$this->isValidPostRequest(['user_id', 'role'])) {
            $this->redirectWithStatus('admin/users');
            return;
        }

        $this->handleCrudOperation(
            fn() => $this->processUpdateRole(),
            'admin/users'
        );
    }

    /**
     * Delete a user (soft delete)
     * Prevents self-deletion (if someone decided to make themselves admin and then delete themselves) and maintains data integrity
     */
    public function deleteUser(): void
    {
        requireRole('admin');

        if (!$this->isValidPostRequest(['user_id'])) {
            $this->redirectWithStatus('admin/users');
            return;
        }

        $this->handleCrudOperation(
            fn() => $this->processDeleteUser(),
            'admin/users'
        );
    }


    /* ---------------------------- Methods for those actions ------------------------------ */


    /**
     * Process updating user status
     * Toggles between active and inactive with self-modification protection
     */
    private function processUpdateStatus(): void
    {
        $userId = $this->validateId('user_id');
        if (!$userId) {
            return; // Error already set by validateId
        }

        // Get current user status
        $user = $this->model->getUserById($userId);
        if (!$user) {
            $this->setError('user_not_found');
            return;
        }

        if ($this->isSelfModification($userId)) {
            $this->setError('cannot_deactivate_self');
            return;
        }

        // Toggle status between active and inactive
        $newStatus = $user['status'] === 'active' ? 'inactive' : 'active';
        
        if ($this->model->updateUserStatus($userId, $newStatus)) {
            $this->setSuccess($newStatus === 'active' ? 'activated' : 'deactivated');
        } else {
            $this->setError('update_failed');
        }
    }

    /**
     * Process updating user role
     * Validates role against allowed options
     */
    private function processUpdateRole(): void
    {
        $userId = $this->validateId('user_id');
        if (!$userId) {
            return; 
        }

        $newRole = $this->validateString('role');
        if (!$newRole) {
            return; 
        }

        // Business rule: validate role against allowed values
        $validRoles = $this->model->getValidRoles();
        if (!in_array($newRole, $validRoles, true)) {
            $this->setError('invalid_role');
            return;
        }

        // Check if user exists
        $user = $this->model->getUserById($userId);
        if (!$user) {
            $this->setError('user_not_found');
            return;
        }

        // Update user role
        if ($this->model->updateUserRole($userId, $newRole)) {
            $this->setSuccess('role_updated');
        } else {
            $this->setError('update_failed');
        }
    }

    /**
     * Process deleting a user
     */
    private function processDeleteUser(): void
    {
        $userId = $this->validateId('user_id');
        if (!$userId) {
            return; 
        }

        // Check if user exists
        $user = $this->model->getUserById($userId);
        if (!$user) {
            $this->setError('user_not_found');
            return;
        }

        if ($this->isSelfModification($userId)) {
            $this->setError('cannot_delete_self');
            return;
        }

        // Perform soft delete
        if ($this->model->deleteUser($userId)) {
            $this->setSuccess('deleted');
        } else {
            $this->setError('delete_failed');
        }
    }

}