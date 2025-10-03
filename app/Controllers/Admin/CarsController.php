<?php
namespace App\Controllers\Admin;
use App\Core\BaseController;
use App\Models\Admin\CarsModel;


/**
 * CarsController - Admin car inventory management
 *
 * Provides complete management for cars listed in the system:
 * - Display and filtering of all cars within the admin dashboard
 * - Secure creation of new car records with image upload
 * - Updating existing car details while preserving or replacing images
 * - Deletion of cars including cleanup of associated image files
 * - Validation and sanitization of all input data
 *
 * Business Rules i applied:
 * - VIN, brand, and model are mandatory
 * - Year must be >=1900 and <= next year
 * - Price must be >=0
 * - Image upload restricted to jpg, jpeg, png, gif
 * - Old image deleted only when new one uploaded successfully otherwise preserved while editing
 */
class CarsController extends BaseController
{   
    public function __construct(private CarsModel $model)
    {
        /** Controller-specific error messages */
        $this->errorMessages = [
            'invalid_id'     => 'Invalid car ID.',
            'missing_fields' => 'VIN, brand, and model are required.',
            'invalid_year'   => 'Invalid car year provided.',
            'invalid_price'  => 'Price must be a positive number.',
            'invalid_image'  => 'Unsupported image type. Allowed: jpg, jpeg, png, gif.',
            'upload_failed'  => 'Failed to upload image.',
            'insert_failed'  => 'Failed to add new car. Please try again.',
            'update_failed'  => 'Failed to update car details. Please try again.',
            'delete_failed'  => 'Failed to delete car. Please try again.',
            'not_found'      => 'Car not found.'
        ];

        /** Controller-specific success messages */
        $this->successMessages = [
            'added'   => 'Car added successfully!',
            'updated' => 'Car updated successfully!',
            'deleted' => 'Car deleted successfully!',
        ];
    }

    /**
     * Initialize the page
     * Displays the Cars list
     */
    public function index(): void
    {
        requireRole('admin');
        generateCSRFToken();

        $cars = $this->model->getAllCars();

        $this->renderView('Admin/CarsView.php', [
            'cars' => $cars,
            'title' => 'Car Management',
            'pageCSS' => '/assets/css/Admin/cars.css'
        ], 'admin');
    }

    /* ---------------------------- ACTIONS ------------------------------ */

    /**
     * function to add a new car
     */
    public function addCar(): void
    {
        requireRole('admin');

        $this->handleCrudOperation(
            fn() => $this->processAddCar(),
            'admin/cars'
        );
    }

    /**
     * Update existing car
     */
    public function updateCar(): void
    {
        requireRole('admin');

        if (!$this->isValidPostRequest(['car_id'])) {
            $this->redirectWithStatus('admin/cars');
            return;
        }

        $this->handleCrudOperation(
            fn() => $this->processUpdateCar(),
            'admin/cars'
        );
    }

    /**
     * Delete car
     */
    public function deleteCar(): void
    {
        requireRole('admin');

        if (!$this->isValidPostRequest(['car_id'])) {
            $this->redirectWithStatus('admin/cars');
            return;
        }

        $this->handleCrudOperation(
            fn() => $this->processDeleteCar(),
            'admin/cars'
        );
    }


    /* ---------------------------- Methods for those actions ------------------------------ */

    /**
     * Handle the process of adding a new car
     */
    private function processAddCar(): void
    {
        $carData = $this->validateCarData(); // Validates and returns sanitized car data or null on failure, and sets error messages
        if (!$carData) {
            return;
        }

        $carData['image'] = $this->handleImageUpload();

        if ($this->model->createCar($carData)) {
            $this->setSuccess('added');
        } else {
            $this->setError('insert_failed');
        }
    }

    /**
     * Handle the process of updating an existing car
     */

    private function processUpdateCar(): void
    {
        $carId = $this->validateId('car_id');
        if (!$carId) {
            return;
        }

        $carData = $this->validateCarData();
        if (!$carData) {
            return;
        }

        $currentCar = $this->model->getCarById($carId);
        if (!$currentCar) {
            $this->setError('not_found');
            return;
        }

        $carData['image'] = $this->handleImageUpload($currentCar['image']);

        if ($this->model->updateCar($carId, $carData)) {
            $this->setSuccess('updated');
        } else {
            $this->setError('update_failed');
        }
    }

    /**
     * Handle the process of deleting a car
     */

    private function processDeleteCar(): void
    {
        $carId = $this->validateId('car_id');
        if (!$carId) {
            return;
        }

        $car = $this->model->getCarById($carId);

        if ($this->model->deleteCar($carId)) {
            if ($car && !empty($car['image'])) {
                $this->deleteImageFile($car['image']);
            }
            $this->setSuccess('deleted');
        } else {
            $this->setError('delete_failed');
        }
    }

    /* ---------------- Validation Helpers ---------------- */

    private function validateCarData(): ?array
    {
        $vin = $this->validateString('vin');
        $brand = $this->validateString('brand');
        $model = $this->validateString('model');
        $year = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT);
        $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
        $status = $this->validateString('status', 'POST', false) ?? 'available';
        $description = $this->validateString('desc', 'POST', false);

        if (!$vin || !$brand || !$model) {
            $this->setError('missing_fields');
            return null;
        }

        if ($year && ($year < 1900 || $year > intval(date("Y")) + 1)) {
            $this->setError('invalid_year');
            return null;
        }

        if ($price !== null && $price < 0) {
            $this->setError('invalid_price');
            return null;
        }

        return [
            'vin' => $vin,
            'brand' => $brand,
            'model' => $model,
            'year' => $year,
            'price' => $price,
            'status' => $status,
            'description' => $description
        ];
    }

    private function handleImageUpload(?string $currentImage = null): ?string
    {
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            return $currentImage;
        }

        $uploadDir = BASE_PATH . '/public/assets/images/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowedTypes = ["jpg", "jpeg", "png", "gif"];

        if (!in_array($ext, $allowedTypes, true)) {
            $this->setError('invalid_image');
            return $currentImage;
        }

        $imageName = uniqid("car_", true) . "." . $ext;
        $targetPath = $uploadDir . $imageName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            if ($currentImage && file_exists($uploadDir . $currentImage)) {
                unlink($uploadDir . $currentImage);
            }
            return $imageName;
        }

        $this->setError('upload_failed');
        return $currentImage;
    }

    private function deleteImageFile(string $imageName): void
    {
        $imagePath = BASE_PATH . '/public/assets/images/uploads/' . $imageName;
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }
}
