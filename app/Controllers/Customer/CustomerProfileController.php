<?php 
namespace App\Controllers\Customer;
use App\Models\Customer\CustomerProfileModel;
use App\Core\BaseController;

class CustomerProfileController extends BaseController
{
    public function __construct(private CustomerProfileModel $model) 
    {
         $this->errorMessages = [
            'profile_update_failed' => 'Failed to update profile. Please try again.',
            'email_taken' => 'This email address is already in use by another account.',
            'current_password_wrong' => 'Current password is incorrect.',
            'password_too_short' => 'New password must be at least 8 characters long.',
            'passwords_mismatch' => 'New password and confirmation do not match.',
            'password_update_failed' => 'Failed to update password. Please try again.',
            'same_password' => 'New password must be different from current password.',
        ];

    
        $this->successMessages = [
            'profile_updated' => 'Profile updated successfully!',
            'password_updated' => 'Password changed successfully!',
        ];
    }


    public function showProfile(): void
    {
        requireLogin();
        requireRole('customer');
        generateCSRFToken();
        
        $userId = $_SESSION['user_id'];

        $userProfile = $this->model->getUserProfile($userId);
        if (!$userProfile) {
            $this->setError("not_found");
            $this->redirectWithStatus('');
            return;
        }

        $formattedUser = [
            'id' => $userProfile['id'],
            'username' => htmlspecialchars($userProfile['username'], ENT_QUOTES, 'UTF-8'),
            'email' => htmlspecialchars($userProfile['email'], ENT_QUOTES, 'UTF-8'),
            'role' => $userProfile['role'],
            'status' => $userProfile['status'],
            'member_since' => date('F j, Y', strtotime($userProfile['created_at'])),
        ];

        $viewData = [
            'user' => $formattedUser,
            'title' => 'My Profile',
            'pageCSS' => '/assets/css/Customer/profile.css',
            'pageJS' => '/assets/js/Customer/profile.js'
        ];

        $this->renderView('Customer/ProfileView.php', $viewData, 'customer');
    }


    public function updateProfile(): void
    {
        requireLogin();
        requireRole('customer');

        $this->handleCrudOperation(function () { $userId = $_SESSION['user_id'];
            $this->validateProfile($userId);},'customer/profile');
    }

    private function validateProfile($userId)
    {
        $username = $this->validateString('username');
        $email = $this->validateEmail('email');

        if (!$username || !$email) {
            return;
        }

        if(strlen($username) < 2){
            $this->setError('missing_fields');
            return;
        }

        if ($this->model->isEmailTaken($email, $userId)) {
            $this->setError('email_taken');
            return;
        }

        $this->processProfileUpdate($userId, $username, $email);

    }

    private function processProfileUpdate($userId, $username, $email): void
    {
        $updateData = [
            'user_id' => $userId,
            'username' => $username,
            'email' => $email
        ];

        if ($this->model->updateProfile($updateData)) {
            $_SESSION['user_name'] = $username; // Update session username
            $this->setSuccess('profile_updated');
        } else {
            $this->setError('profile_update_failed');
        }
    }

    public function updatePassword(): void
    {
        requireLogin();
        requireRole('customer');

        
        $this->handleCrudOperation(function () {$userId = $_SESSION['user_id'];
            $this->validatePassword($userId);},'customer/profile');
    }

    private function validatePassword($userId)
    {
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $this->setError('missing_fields');
            return;
        }

        if (!$this->model->checkCurrentPassword($userId, $currentPassword)) {
            $this->setError('current_password_wrong');
            return;
        }

        if (strlen($newPassword) < 8) {
            $this->setError('password_too_short');
            return;
        }

        if ($currentPassword === $newPassword) {
                    $this->setError('same_password');
                    return;
                }

        if ($newPassword !== $confirmPassword) {
            $this->setError('passwords_mismatch');
            return;
        }

        $success = $this->model->updatePassword($userId, $newPassword);
        
        if($success){
            $this->setSuccess('password_updated');
        } else {
            $this->setError('password_update_failed');
        }

    }

}