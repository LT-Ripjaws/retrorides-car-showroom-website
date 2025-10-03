<?php
namespace App\Core;
use mysqli;

/**
 * BaseController - Foundation class for all application controllers
 *
 * Provides common functionality and patterns used across all controllers:
 * - Error and success message handling
 * - Request validation utilities
 * - Redirect management with status messages
 * - Input sanitization and validation
 * - View rendering helpers
 *
 * All controllers should extend this class to inherit shared functionality
 * and maintain consistent patterns across the application.
 */
abstract class BaseController
{
    protected string $error = "";
    protected string $success = "";
    protected array $errorMessages = [];
    protected array $successMessages = [];


    /**
     * Validate that current request is POST method
     */
    protected function isPostRequest(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Validate that current request is POST with specific required fields
     */
    protected function isValidPostRequest(array $requiredFields = []): bool
    {
        if (!$this->isPostRequest()) {
            return false;
        }

        foreach ($requiredFields as $field) {
            if (!isset($_POST[$field])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Validate and sanitize an integer ID from POST/GET data
     */
    protected function validateId(string $field, string $method = 'POST'): ?int
    {
        $inputType = $method === 'GET' ? INPUT_GET : INPUT_POST;
        $id = filter_input($inputType, $field, FILTER_VALIDATE_INT);
        
        if (!$id || $id <= 0) {
            $this->error = "invalid_id";
            return null;
        }

        return $id;
    }

    /**
     * Validate and sanitize string input
     */
    protected function validateString(string $field, string $method = 'POST', bool $required = true): ?string
    {
        $value = $method === 'GET' ? ($_GET[$field] ?? '') : ($_POST[$field] ?? '');
        $cleanValue = cleanInput($value);
        
        if ($required && empty($cleanValue)) {
            $this->error = "missing_fields";
            return null;
        }
        
        return $cleanValue;
    }

    /**
     * Validate email address from input
     */
    protected function validateEmail(string $field, string $method = 'POST'): ?string
    {
        $inputType = $method === 'GET' ? INPUT_GET : INPUT_POST;
        $email = filter_input($inputType, $field, FILTER_VALIDATE_EMAIL);
        
        if (!$email) {
            $this->error = "invalid_email";
            return null;
        }
        
        return $email;
    }

    /**
     * Check if user is trying to modify their own account
     * Useful for preventing self-deletion/deactivation
     */
    protected function isSelfModification(int $targetUserId): bool
    {
        return $targetUserId === ($_SESSION['user_id'] ?? 0);
    }

    /**
     * Set error message by code
     */
    protected function setError(string $errorCode): void
    {
        $this->error = $errorCode;
    }

    /**
     * Set success message by code
     */
    protected function setSuccess(string $successCode): void
    {
        $this->success = $successCode;
    }

    /**
     * Get error message from URL parameters or current error
     */
    protected function getErrorMessage(?string $errorCode = null): string
    {
        $code = $errorCode ?? ($_GET['error'] ?? '');
        
        // Common error messages across all controllers
        $commonMessages = [
            'csrf' => 'Invalid request. Please try again.',
            'invalid_id' => 'Invalid ID provided.',
            'missing_fields' => 'All required fields must be filled.',
            'invalid_email' => 'Please enter a valid email address.',
            'invalid_phone' => 'Phone number format is invalid.',
            'cannot_delete_self' => 'You cannot delete your own account.',
            'cannot_deactivate_self' => 'You cannot deactivate your own account.',
            'update_failed' => 'Failed to update record. Please try again.',
            'delete_failed' => 'Failed to delete record. Please try again.',
            'insert_failed' => 'Failed to create record. Please try again.',
            'not_found' => 'The requested item was not found.'
        ];

        // Merge with controller-specific messages
        $allMessages = array_merge($commonMessages, $this->errorMessages);
        
        return $allMessages[$code] ?? '';
    }

    /**
     * Get success message from URL parameters or current success
     */
    protected function getSuccessMessage(): string
    {
        // Check URL parameters for success indicators
        foreach ($this->successMessages as $param => $message) {
            if (isset($_GET[$param])) {
                return $message;
            }
        }
        
        return '';
    }

    /**
     * Redirect to a specific route with status messages
     */
    protected function redirectWithStatus(string $route, array $formData = []): void
    {
        $params = [];

        if (!empty($this->error)) {
            $params['error'] = $this->error;
        }

        if (!empty($this->success)) {
            $params[$this->success] = '1';
        }

        // Save form data temporarily (flash), only if provided
        if (!empty($formData)) {
            $_SESSION['old_input'] = $formData;
        }

        $baseUrl = getBasePath();
        $queryString = $params ? '?' . http_build_query($params) : '';
        header("Location: {$baseUrl}/{$route}{$queryString}");
        exit();
    }


    /**
     * Prepare common view data for rendering
     */
    protected function getViewData(): array
    {
        return [
            'error' => $this->getErrorMessage(),
            'success' => $this->getSuccessMessage(),
            'csrfToken' => $_SESSION['csrf_token'] ?? '',
            'baseUrl' => getBasePath(),
            'currentUser' => [
                'id' => $_SESSION['user_id'] ?? null,
                'name' => $_SESSION['user_name'] ?? '',
                'role' => $_SESSION['role'] ?? ''
            ]
        ];
    }

    /**
     * Render a view with data
     */
   protected function renderView(string $viewPath, array $data = [], ?string $layout = 'main'): void
    {
        // Merge common view data with specific data
        $viewData = array_merge($this->getViewData(), $data);
        
        // Extract variables for view
        extract($viewData);
        
        // If no layout, render view directly
        if ($layout === null) {
            require BASE_PATH . '/app/Views/' . $viewPath;
            return;
        }
        
        // With layout, capture view content first
        ob_start();
        require BASE_PATH . '/app/Views/' . $viewPath;
        $content = ob_get_clean();
        
        // Now render layout with captured content
        require BASE_PATH . '/app/Views/Layouts/' . $layout . '.php';
    }

    /**
     * Handle standard CRUD operation flow (can be used for create, update, delete)
     * Template method that child controllers can use
     */
    protected function handleCrudOperation(callable $operation, string $redirectRoute): void
    {
        if (!validateCSRF()) { // Check CSRF token (coming from auth.php)
            $this->setError('csrf');
            $this->redirectWithStatus($redirectRoute);
            return;
        }

        // Execute the operation
        $operation();
        
        // Redirect with results
        $this->redirectWithStatus($redirectRoute);
    }


    /* -------------------------- AUTH SPECIFIC -------------------------- */

    /**
     * Initialize CSRF, auto-login, and redirect if already logged in
     */
    protected function initializeAuthSession(mysqli $conn): void
    {
        generateCSRFToken();
        autoLogin($conn);

        if (isset($_SESSION['user_id'], $_SESSION['role'])) {
            roleRedirection($_SESSION['role']);
        }
    }

    /**
     * Handle a form submission lifecycle:
     * - Check if request is POST and contains a marker field (eg. 'login' or 'register')
     * - Validate CSRF
     * - Run the provided callback
     * - Redirect with old input if validation fails
     */
    protected function handleFormRequest(
        string $markerField,
        callable $onValid,
        string $redirectRoute,
        array $persistFields = []
    ): void {
        if ($this->isValidPostRequest([$markerField])) {
            if (!validateCSRF()) {
                $this->redirectWithStatus($redirectRoute, $this->collectOldInput($persistFields));
                return;
            }
            $onValid();
            return;
        }
    }
    
    protected function old(string $key, string $default = ''): string
    {
        if (!isset($_SESSION['old_input'])) {
            return $default;
        }
        $value = $_SESSION['old_input'][$key] ?? $default;
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    protected function clearOldInput(): void
    {
        unset($_SESSION['old_input']);
    }

    /**
     * Collect specified fields from $_POST for persistence
     */
    protected function collectOldInput(array $fields): array
    {
        $data = [];
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $data[$field] = $_POST[$field];
            }
        }
        return $data;
    }

    /**
     * Shortcut for redirecting with old input
     */
    protected function redirectWithFormData(string $route, array $fields): void
    {
        $this->redirectWithStatus($route, $this->collectOldInput($fields));
    }

    /* -------------------------- END AUTH SPECIFIC -------------------------- */


    /**
     * Safely generate image URL with fallback and traversal protection (gotta avoid them ../../ trickz)
     */
    protected function getSafeImageUrl( string $image, string $subDir = 'uploads', string $defaultImage = 'default-car.jpg'): string 
    {
        $baseUrl = getBasePath();
        
        // Prevent directory traversal
        $image = basename($image);

        // Default fallback if empty
        if (empty($image)) {
            return "{$baseUrl}/assets/images/{$defaultImage}";
        }

        // Build upload path and check existence
        $uploadPath = BASE_PATH . "/public/assets/images/{$subDir}/{$image}";
        if (file_exists($uploadPath)) {
            return "{$baseUrl}/assets/images/{$subDir}/{$image}";
        }

        // Fallback if missing
        return "{$baseUrl}/assets/images/{$defaultImage}";
    }
}


