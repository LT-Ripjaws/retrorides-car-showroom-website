<?php
namespace App\Controllers\Admin;
use App\Core\BaseController;
use App\Models\Admin\ProfileModel;

/**
 * ProfileController - Admin profile management
 *
 * Used for viewing and updating admin profile information,
 * Admin can update their name, email, phone number and change password.
 * CSRF protection and input validation are enforced.
 */
class ProfileController extends BaseController
{
    public function __construct(private ProfileModel $model)
    {
        /**
         * Controller-specific error messages merged with BaseController's common messages.
         */
        $this->errorMessages = [
        'email_exists' => 'This email address is already in use.',
        'user_not_found' => 'User profile not found.',
        'wrong_old_password' => 'Current password is incorrect.',
        'password_mismatch' => 'New passwords do not match.',
        'weak_password' => 'Password must be at least 8 characters long.',
        'same_password' => 'New password must be different from current password.'
        ];

        /**
         * Controller-specific success messages mapped to URL params.
         */
        $this->successMessages = [
        'profile_updated' => 'Profile updated successfully!',
        'password_changed' => 'Password changed successfully!'
        ];
    }

    /**
     * Display admin profile page
     */
    public function index(): void
    {
        requireRole('admin');
        generateCSRFToken();

        $adminId = $_SESSION['user_id'] ?? null;
        $admin = $this->model->getAdminProfile($adminId);

        if (!$admin) {
            $this->setError('user_not_found');
            $this->redirectWithStatus('login');
        }

        // Render the profile view
        $this->renderView('Admin/ProfileView.php', [
            'admin' => $admin,
            'title' => 'Manage your profile',
            'pageCSS' => '/assets/css/Admin/profile.css'
        ], 'admin');
    }



    /* ---------------------------- ACTIONS ------------------------------ */
    /**
     * Update admin profile information
     */
    public function updateProfile(): void
    {
        requireRole('admin');

        // Ensure basic required fields are present before processing
        if (!$this->isValidPostRequest(['name', 'email'])) {
            $this->redirectWithStatus('admin/profile');
            return;
        }

        $this->handleCrudOperation(
            fn() => $this->processUpdateProfile(),
            'admin/profile'
        );
    }

    /**
     * Change admin password
     */
    public function changePassword(): void
    {
        requireRole('admin');

        // Require old, new and confirm password
        if (!$this->isValidPostRequest(['old_password', 'new_password', 'confirm_password'])) {
            $this->redirectWithStatus('admin/profile');
            return;
        }

        $this->handleCrudOperation(
            fn() => $this->processChangePassword(),
            'admin/profile'
        );
    }


    
    /* ---------------------------- Methods for those actions ------------------------------ */
    /**
     * Process updating profile fields
     */
    private function processUpdateProfile(): void
    {
        // We will use BaseController validators here
        $name = $this->validateString('name');
        $email = $this->validateEmail('email');
        // phone is optional (super admin probably doesn't need it)
        $phone = $this->validateString('phone', 'POST', false);

        if (!$name || !$email) {
            return; // error already set by validators
        }

        // Optional phone validation
        if (!empty($phone) && !preg_match('/^[0-9+\-\s]{7,15}$/', $phone)) {
            $this->setError('invalid_phone');
            return;
        }

        $adminId = $_SESSION['user_id'] ?? null;

        // Ensure email uniqueness excluding current user (we don't want someone else's email)
        if ($this->model->emailExistsForOtherUser($email, $adminId)) {
            $this->setError('email_exists');
            return;
        }

        // Attempt update
        $updateData = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone
        ];

        if ($this->model->updateProfile($adminId, $updateData)) {
            $this->setSuccess('profile_updated');
        } else {
            $this->setError('update_failed');
        }
    }

    /**
     * Process password change
     */
    private function processChangePassword(): void
    {
        $passwordData = $this->validatePasswordData();
        if (!$passwordData) {
            return; // errors set inside validator
        }

        $adminId = $_SESSION['user_id'] ?? null;
        $currentPasswordHash = $this->model->getCurrentPassword($adminId);

        if (!$currentPasswordHash) {
            $this->setError('user_not_found');
            return;
        }

        if (!password_verify($passwordData['old_password'], $currentPasswordHash)) {
            $this->setError('wrong_old_password');
            return;
        }

        $hashed = password_hash($passwordData['new_password'], PASSWORD_DEFAULT);

        if ($this->model->updatePassword($adminId, $hashed)) {
            $this->setSuccess('password_changed');
        } else {
            $this->setError('update_failed');
        }
    }

    /**
     * Validate password-related fields
     */
    private function validatePasswordData(): ?array
    {
        $oldPassword = $_POST['old_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
            $this->setError('missing_fields');
            return null;
        }

        if ($newPassword !== $confirmPassword) {
            $this->setError('password_mismatch');
            return null;
        }

        if (strlen($newPassword) < 8) {
            $this->setError('weak_password');
            return null;
        }

        if ($oldPassword === $newPassword) {
            $this->setError('same_password');
            return null;
        }

        return [
            'old_password' => $oldPassword,
            'new_password' => $newPassword
        ];
    }
}
