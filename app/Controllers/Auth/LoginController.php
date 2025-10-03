<?php
namespace App\Controllers\Auth;

use App\Core\BaseController;
use App\Models\Auth\LoginModel;
use mysqli;

/**
 * LoginController - Handles user authentication
 *
 * Responsible for:
 * - Showing login form
 * - Validating credentials & CSRF
 * - Authenticating against employee & customer records
 * - Managing sessions and remember-me cookies
 * - Redirecting based on user roles
 *
 * Security:
 * -  CSRF validation
 * - Input sanitization
 * - Password hashing with password_verify
 * - Session & cookie-based authentication
 */
class LoginController extends BaseController
{
    public function __construct(private mysqli $conn, private LoginModel $model) 
    {
        $this->errorMessages = [
            'invalid_credentials' => 'Invalid email or password.',
            'invalid_email' => 'Please enter a valid email address.',
            'missing_fields' => 'All fields are required.',
            'account_inactive' => 'Your account is inactive. Please contact support.',
        ];
    }

    /* ---------------------------- VIEWS ------------------------------ */

    public function showLoginForm(): void 
    {
        $this->initializeAuthSession($this->conn); //done from the baseController, it will initialize CSRF, auto-login, and redirect if already logged in
        $this->renderLoginPage(); 
    }

    public function processLogin(): void 
    {
        $this->initializeAuthSession($this->conn);
            
         /**
         * Process login request
         * - Ensures CSRF + POST validation
         * - Handles login attempt via callback
         * - Redirects or re-renders page on failure
         */
        $this->handleFormRequest( 
            
            'login',                                        // marker field (submit button name)
            fn() => $this->handleLoginAttempt(),          // actual login logic
            'login',                                      // redirect route on failure
            ['username']                                  // persist entered email
        );

        $this->renderLoginPage();
    }

    /* ---------------------------- ACTIONS ------------------------------ */

    /**
     * Handles the actual login attempt
     * - Validates inputs
     * - If invalid, redirects back with old form data
     * - Otherwise attempts authentication
     */
    private function handleLoginAttempt(): void 
    {
        $credentials = $this->validateCredentials();
        if (!$credentials) {
            $this->redirectWithFormData('login', ['username']); //to keep their old entered email in field
        }

        $this->attemptLogin($credentials['email'], $credentials['password']);
    }


    /**
     * Validate and sanitize login credentials
     * - Ensures valid email format
     * - Ensures password is non-empty
     * Returns null if invalid
     */
    private function validateCredentials(): ?array 
    {
        $email = $this->validateEmail('username');
        $password = $this->validateString('password');

        if (!$email || !$password) {
            return null;
        }

        return ['email' => $email, 'password' => $password];
    }

    /**
     * Attempts login for both employees and customers
     * - Queries DB for both account types
     * - Delegates to role-specific checkers
     * - Redirects back with error if both fail
     */
    private function attemptLogin(string $email, string $password): void 
    {
        $employee = $this->model->findEmployeeByEmail($email);
        $customer = $this->model->findCustomerByEmail($email);

        if ($this->tryEmployeeLogin($employee, $password)) {
            return;
        }

        if ($this->tryCustomerLogin($customer, $password)) {
            return;
        }

        $this->setError('invalid_credentials');
        $this->redirectWithFormData('login', ['username']);
    }

    private function tryEmployeeLogin(?array $employee, string $password): bool 
    {
        if (!$employee) return false;
        if (!password_verify($password, $employee['password'])) return false;
        if ($employee['status'] !== 'active') {
            $this->setError('account_inactive');
            return false;
        }

        $this->loginUser((int)$employee['id'], $employee['name'], $employee['role']);
        return true;
    }

    private function tryCustomerLogin(?array $customer, string $password): bool 
    {
        if (!$customer) return false;
        if (!password_verify($password, $customer['password'])) return false;
        if ($customer['status'] !== 'active') {
            $this->setError('account_inactive');
            return false;
        }

        $this->loginUser((int)$customer['id'], $customer['name'], 'customer');
        return true;
    }


    /**
     * Finalize login process
     * - Sets session
     * - Stores remember-me cookie if requested
     * - Redirects user based on role
     */
    private function loginUser(int $userId, string $name, string $role): void 
    {
        setUserSession($userId, $name, $role);

        if (isset($_POST['remember-me'])) {
            setRememberMe($this->conn, $userId, $role);
        }

        roleRedirection($role);
    }

    /**
     * Prepares and renders the login page
     * - Includes any old input (email field)
     * - Clears flash data after rendering
     */
    private function renderLoginPage(): void 
    {
        $pageData = [
            'title' => 'Login - RetroRides',
            'description' => 'Sign in to your RetroRides account to manage bookings and explore vintage cars.',
            'previousEmail' => $this->old('username'),
            'showRememberMe' => true,
            'registerLink' => getBasePath() . '/register',
            'pageCSS' => '/assets/css/login.css'

        ];

        $this->clearOldInput();
        $this->renderView('Auth/LoginView.php', $pageData, 'main');
    }

    /**
     * Logout user and clear all session data
     */
    public function logout(): void 
    {   
        logout($this->conn);
    }
}
