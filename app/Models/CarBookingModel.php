<?php 
namespace App\Models;
use mysqli;
/**
 * BookingModel - Handles car purchase booking data operations
 * 
 * This model manages the booking process for car purchases:
 * - Fetches car details for booking form
 * - Checks car availability (prevents double-booking)
 * - Creates new purchase bookings
 * 
 * Business Rules:
 * - Only 'available' cars can be booked
 * - Only ONE 'processing' booking allowed per car
 * - New bookings always start with status 'processing'
 * - Admin later updates status to 'sold' or 'cancelled'
 */
class CarBookingModel{
    public function __construct(private mysqli $conn)
    {
    }

    /* Fetch Car details by ID for the booking form
    * Returns associative array of car details or null if not found
    */
    public function getCarById(int $carId):?array
    {
        $stmt = $this->conn->prepare("SELECT car_id, vin, brand, model, year, price, status, description, image from cars where car_id = ? LIMIT 1");

        if(!$stmt){
            error_log("BookingModel::getCarById - Prepare failed: " . $this->conn->error);
            return null;
        }

        $stmt->bind_param("i",$carId);
        $stmt->execute();
        $result = $stmt->get_result();
        $car = $result->fetch_assoc();
        $stmt->close();
        return $car ?: null;
    }
 

    /* Check if a car is available for booking
    * Returns true if car is 'available' and has no 'processing' bookings 
    * Returns false if car is not available or already booked
    */
    public function isCarAvailable(int $carId):bool
    {
        $car = $this->getCarById($carId);
        
        if(!$car || $car['status'] !== 'available'){
            return false;
        }

        $stmt = $this->conn->prepare("SELECT COUNT(*) as booking_count from bookings where car_id = ? AND status = 'processing'");
        
        if(!$stmt){
            error_log("BookingModel::isCarAvailable - Prepare failed: " .$this->conn->error);
            return false;
        }

        $stmt->bind_param("i",$carId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        return $row['booking_count'] == 0;
    }


    /* Create a new car purchase booking
    * Returns booking ID on success, false on failure
    */
    public function createBooking(array $data): int|false{

        $stmt = $this->conn->prepare("INSERT INTO bookings (user_id, car_id, customer_name, customer_email, booking_date, status) 
        VALUES (?,?,?,?,NOW(),'processing')");

        if(!$stmt){
            error_log("BookingModel::createBooking - Prepare failed: " .$this->conn->error);
            return false;
        }

        $stmt->bind_param("iiss",
        $data['user_id'],
        $data['car_id'],
        $data['customer_name'],
        $data['customer_email']
        );

        $success = $stmt->execute();
        $bookingId = $success ? $stmt->insert_id : false;
        $stmt->close();
        return $bookingId;
    }
}