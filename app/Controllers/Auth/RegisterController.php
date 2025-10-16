<?php
namespace App\Controllers\Auth;

use App\Models\Auth\RegisterModel;
use App\Core\BaseController;
use mysqli;
/**
 * RegisterController – Handles user sign-up workflow
 *
 * Responsibilities:
 * - Display the registration form (while blocking logged-in users)
 * - Validate inputs (username, email, password + confirm password)
 * - Enforce security rules (lengths, uniqueness, format, CSRF protection)
 * - Pass validated data to the RegisterModel for database insertion
 * - Provide user-friendly feedback on success or failure
 *
 * Security Features:
 * - CSRF protection 
 * - Input sanitization through BaseController helpers (cleanInput, validateString, etc.)
 * - Strong password rules (min length, confirmation)
 * - Prevents duplicate account creation by checking if email already exists
 */
class RegisterController extends BaseController
{
    public function __construct(private mysqli $conn, private RegisterModel $model) 
    {
        $this->errorMessages = [
            'username_too_short' => 'Username must be at least 3 characters long.',
            'email_exists' => 'This email is already registered. Try logging in.',
            'password_too_short' => 'Password must be at least 8 characters long.',
            'password_mismatch' => 'Passwords do not match!',
            'registration_failed' => 'Registration failed. Please try again later.'
        ];

        $this->successMessages = [
            'registered' => 'Registration successful! You can now log in.'
        ];
    }

    /* ---------------------------- VIEWS ------------------------------ */

    /**
     * Show registration form
     * - Initializes session with CSRF token
     * - Redirects if user is already logged in (handled in BaseController)
     * - Renders the registration page
     */
    public function showRegisterForm(): void 
    {
        $this->initializeAuthSession($this->conn);
        $this->renderRegisterPage();
    }

    /**
     * Process registration form submission
     * - Ensures POST + CSRF validation
     * - Runs registration logic via callback
     * - Redirects with old inputs if validation fails
     * - Re-renders page in all cases
     */

    public function processRegister(): void 
    {
        $this->initializeAuthSession($this->conn);

         $this->handleFormRequest(
            'register',                               // marker field (submit button name)
            fn() => $this->handleRegistration(),    // registration callback
            'register',                             // redirect route if validation fails
            ['username', 'email']                   // fields to persist in case of error
        );

        $this->renderRegisterPage();
    }

    /* ---------------------------- ACTIONS ------------------------------ */

    /**
     * Handles the actual registration logic
     * - Validates input fields
     * - Redirects with errors if invalid
     * - Attempts to create user if valid
     */
    private function handleRegistration(): void 
    {
        $userData = $this->getValidatedUserData();
        if (!$userData) {
            $this->redirectWithFormData('register', ['username', 'email']);
        }

        $this->attemptRegistration($userData);
    }

    /**
     * Validate and sanitize registration fields
     * Rules:
     * - Username: required, min 3 characters
     * - Email: required, valid format, not already in use
     * - Password: required, min 8 characters, matches confirmation
     *
     * Returns: array of cleaned inputs if valid, null if any validation fails
     */
    private function getValidatedUserData(): ?array 
    {
        $username = $this->validateString('username');
        if (!$username) return null;
        if (strlen($username) < 3) {
            $this->setError('username_too_short');
            return null;
        }

        $email = $this->validateEmail('email');
        if (!$email) return null;
        if ($this->model->emailExists($email)) {
            $this->setError('email_exists');
            return null;
        }

        $password = $this->validateString('password');
        $confirmPassword = $this->validateString('confirm_password');
        if (!$password || !$confirmPassword) return null;
        if (strlen($password) < 8) {
            $this->setError('password_too_short');
            return null;
        }
        if ($password !== $confirmPassword) {
            $this->setError('password_mismatch');
            return null;
        }

        return ['username' => $username, 'email' => $email, 'password' => $password];
    }

    /**
     * Attempt to register a new user in the database
     * - Delegates actual DB work + password hashing to the RegisterModel
     * - Sets success or failure message
     * - Redirects with old form data (for persistence of username/email)
     */
    private function attemptRegistration(array $userData): void 
    {
        $success = $this->model->registerUser(
            $userData['username'], 
            $userData['email'], 
            $userData['password']
        );

        if ($success) {
            $this->setSuccess('registered');
            $this->redirectWithFormData('register', ['username', 'email']);
        } else {
            $this->setError('registration_failed');
            $this->redirectWithFormData('register', ['username', 'email']);
        }
    }

    /**
     * Prepares and renders the registration page
     * - Restores previously entered username/email if form failed
     * - Clears old input after use (so it doesn’t persist indefinitely)
     */
    private function renderRegisterPage(): void 
    {
        $pageData = [
            'title' => 'Create Account',
            'description' => 'Register for RetroRides to start your vintage car journey',
            'previousName' => $this->old('username'),
            'previousEmail' => $this->old('email'),
            'pageCSS' => '/assets/css/register.css',
            'pageJS' => '/assets/js/register.js'
        ];

        $this->clearOldInput();
        $this->renderView('Auth/RegisterView.php', $pageData, 'main');
    }
}
