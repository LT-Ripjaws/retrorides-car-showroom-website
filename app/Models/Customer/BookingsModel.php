<?php
namespace App\Models\Customer;
use mysqli;

/**
 * CustomerBookingsModel - Data access layer for customer bookings
 *
 * Responsibilities:
 * - Retrieve all bookings for a customer
 * - Get detailed booking information
 * - Calculate booking statistics
 * - Cancel bookings
 * - Filter bookings by various criteria
 */
class BookingsModel
{
    public function __construct(private mysqli $conn)
    {
    }

    /**
     * Get all bookings for a customer with complete car details
     *
     * @param int $customerId Customer's user ID
     * @return array List of all bookings with car information
     */
    public function getAllBookings(int $customerId): array
    {
        $stmt = $this->conn->prepare("
            SELECT 
                b.booking_id,
                b.car_id,
                b.customer_name,
                b.customer_email,
                b.booking_date,
                b.status,
                c.brand,
                c.model,
                c.year,
                c.price,
                c.image,
                c.vin,
                c.description
            FROM bookings b
            JOIN cars c ON b.car_id = c.car_id
            WHERE b.user_id = ?
            ORDER BY b.booking_date DESC
        ");
        
        if (!$stmt) {
            error_log("Failed to prepare getAllBookings statement: " . $this->conn->error);
            return [];
        }
        
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        $bookings = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        
        return $bookings;
    }

    /**
     * Get a specific booking by ID
     * Ensures the booking belongs to the customer
     *
     * @param int $bookingId Booking ID
     * @param int $customerId Customer's user ID
     * @return array|null Booking data or null if not found
     */
    public function getBookingById(int $bookingId, int $customerId): ?array
    {
        $stmt = $this->conn->prepare("
            SELECT 
                b.booking_id,
                b.car_id,
                b.customer_name,
                b.customer_email,
                b.booking_date,
                b.status,
                c.brand,
                c.model,
                c.year,
                c.price
            FROM bookings b
            JOIN cars c ON b.car_id = c.car_id
            WHERE b.booking_id = ? 
            AND b.user_id = ?
            LIMIT 1
        ");
        
        if (!$stmt) {
            error_log("Failed to prepare getBookingById statement: " . $this->conn->error);
            return null;
        }
        
        $stmt->bind_param("ii", $bookingId, $customerId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        
        return $result;
    }

    /**
     * Get booking statistics by status
     *
     * @param int $customerId Customer's user ID
     * @return array Statistics with counts per status
     */
    public function getBookingStatistics(int $customerId): array
    {
        $stats = [
            'processing' => 0,
            'sold' => 0,
            'cancelled' => 0
        ];

        $stmt = $this->conn->prepare("
            SELECT 
                b.status,
                COUNT(*) as count
            FROM bookings b
            WHERE b.user_id = ?
            GROUP BY b.status
        ");
        
        if (!$stmt) {
            error_log("Failed to prepare getBookingStatistics statement: " . $this->conn->error);
            return $stats;
        }
        
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $status = strtolower($row['status']);
            if (isset($stats[$status])) {
                $stats[$status] = (int)$row['count'];
            }
        }
        
        $stmt->close();
        
        return $stats;
    }

    /**
     * Cancel a booking
     * Only pending/processing bookings can be cancelled
     *
     * @param int $bookingId Booking ID
     * @param int $customerId Customer's user ID for verification
     * @return bool Success status
     */
    public function cancelBooking(int $bookingId, int $customerId): bool
    {
        $stmt = $this->conn->prepare("
            UPDATE bookings 
            SET status = 'cancelled'
            WHERE booking_id = ? 
            AND user_id = ?
            AND status IN ('processing')
        ");
        
        if (!$stmt) {
            error_log("Failed to prepare cancelBooking statement: " . $this->conn->error);
            return false;
        }
        
        $stmt->bind_param("ii", $bookingId, $customerId);
        $success = $stmt->execute();
        $affectedRows = $stmt->affected_rows;
        $stmt->close();
        
        return $success && $affectedRows > 0;
    }



   
}