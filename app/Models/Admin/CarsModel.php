<?php
namespace App\Models\Admin;
use mysqli;

/**
 * CarsModel – Data access layer for car inventory
 *
 * Responsibilities:
 * • Provides all database interactions related to cars
 * • Handles CRUD operations (Create, Read, Update, Delete)
 *
 * Security & Practices:
 * • Uses prepared statements and bound parameters to prevent SQL injection
 * • Returns clean associative arrays for controller and view usage
 */
class CarsModel
{
    public function __construct(private mysqli $conn)
    {
        
    }
    public function getAllCars(): array
    {
        $stmt = $this->conn->prepare("
            SELECT car_id, vin, brand, model, year, price, status, description, image
            FROM cars
            ORDER BY year ASC
        ");

        $stmt->execute();
        $result = $stmt->get_result();
        $cars = [];
        while ($row = $result->fetch_assoc()) {
            $cars[] = $row;
        }

        $stmt->close();
        return $cars;
    }

    /**
     * Retrieve a single car record by ID
     *
     * @param  int $carId Unique ID of the car
     * @return array|null Car record or null if not found
     */
    public function getCarById(int $carId): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM cars WHERE car_id = ? LIMIT 1");
        $stmt->bind_param("i", $carId);
        $stmt->execute();

        $result = $stmt->get_result();
        $car    = $result->fetch_assoc();

        $stmt->close();
        return $car ?: null;
    }

    /**
     * Insert a new car into the database
     *
     * @param  array $carData Validated data including vin, brand, model, etc.
     * @return bool  True on success, false on failure
     */
    public function createCar(array $carData): bool
    {
        $stmt = $this->conn->prepare("
            INSERT INTO cars (vin, brand, model, year, price, status, description, image, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");

        $stmt->bind_param(
            "sssidsss",
            $carData['vin'],
            $carData['brand'],
            $carData['model'],
            $carData['year'],
            $carData['price'],
            $carData['status'],
            $carData['description'],
            $carData['image']
        );

        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    /**
     * Update an existing car record by ID
     *
     * @param  int   $carId   Car ID to update
     * @param  array $carData Validated updated data
     * @return bool  True on success, false on failure
     */
    public function updateCar(int $carId, array $carData): bool
    {
        $stmt = $this->conn->prepare("
            UPDATE cars
            SET vin = ?, brand = ?, model = ?, year = ?, price = ?, status = ?, description = ?, image = ?
            WHERE car_id = ?
        ");

        $stmt->bind_param(
            "sssidsssi",
            $carData['vin'],
            $carData['brand'],
            $carData['model'],
            $carData['year'],
            $carData['price'],
            $carData['status'],
            $carData['description'],
            $carData['image'],
            $carId
        );

        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    /**
     * Delete a car record by ID
     *
     * @param  int  $carId Car ID to delete
     * @return bool True on success, false otherwise
     */
    public function deleteCar(int $carId): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM cars WHERE car_id = ?");
        $stmt->bind_param("i", $carId);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }
}
