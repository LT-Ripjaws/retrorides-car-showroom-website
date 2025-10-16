<?php
namespace App\Models\Customer;

use mysqli;

/**
 * CustomerDashboardModel - Data access layer for customer dashboard
 *
 * Responsibilities:
 * - Retrieves customer profile information
 * - Aggregates customer-specific statistics
 * - Fetches booking data (active and historical)
 * - Provides featured cars for customer browsing
 * - Encapsulates all database queries for customer dashboard
 */
class DashboardModel
{
    public function __construct(private mysqli $conn)
    {
    }

    /**
     * Get customer profile information
     *
     * @param int $customerId Customer's user ID
     * @return array Customer profile data
     */
    public function getCustomerProfile(int $customerId): array
    {
        $stmt = $this->conn->prepare("SELECT id, username, email, created_at, status FROM users WHERE id = ? AND role = 'customer' LIMIT 1");
        
        if (!$stmt) {
            error_log("Failed to prepare getCustomerProfile statement: " . $this->conn->error);
            return [];
        }
        
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        
        return $result ?? [];
    }

    /**
     * Get customer statistics
     * Calculates total bookings, active bookings, completed bookings, and total spent
     *
     * @param int $customerId Customer's user ID
     * @return array Statistics including counts and spending
     */
    public function getCustomerStats(int $customerId): array
    {
        $stats = [
            'total_bookings' => 0,
            'active_bookings' => 0,
            'completed_bookings' => 0,
            'total_spent' => 0.0
        ];

        // Total bookings
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS total FROM bookings WHERE user_id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $customerId);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            $stats['total_bookings'] = (int)($result['total'] ?? 0);
            $stmt->close();
        }

        // Active bookings
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS total FROM bookings WHERE user_id = ? AND status = 'processing'");
        if ($stmt) {
            $stmt->bind_param("i", $customerId);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            $stats['active_bookings'] = (int)($result['total'] ?? 0);
            $stmt->close();
        }

        // Completed bookings (sold)
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS total FROM bookings WHERE user_id = ? AND status = 'sold'");
        if ($stmt) {
            $stmt->bind_param("i", $customerId);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            $stats['completed_bookings'] = (int)($result['total'] ?? 0);
            $stmt->close();
        }

        // Total spent (from sold bookings)
        $stmt = $this->conn->prepare("SELECT SUM(c.price) AS total FROM bookings b JOIN cars c ON b.car_id = c.car_id WHERE b.user_id = ? AND b.status = 'sold'");
        if ($stmt) {
            $stmt->bind_param("i", $customerId);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            $stats['total_spent'] = (float)($result['total'] ?? 0);
            $stmt->close();
        }

        return $stats;
    }

    /**
     * Get customer's active bookings with car details
     *
     * @param int $customerId Customer's user ID
     * @return array List of active bookings with complete car information
     */
    public function getActiveBookings(int $customerId): array
    {
        $stmt = $this->conn->prepare("SELECT b.booking_id, b.booking_date, b.status, c.car_id, c.brand, c.model, c.year, c.price, c.image FROM bookings b JOIN cars c ON b.car_id = c.car_id WHERE b.user_id = ? AND b.status = 'processing' ORDER BY b.booking_date DESC");
        
        if (!$stmt) {
            error_log("Failed to prepare getActiveBookings statement: " . $this->conn->error);
            return [];
        }
        
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        $bookings = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        
        return $bookings;
    }

    /**
     * Get customer's booking history
     * Returns most recent bookings regardless of status
     *
     * @param int $customerId Customer's user ID
     * @param int $limit Number of bookings to retrieve
     * @return array List of bookings with car details
     */
    public function getBookingHistory(int $customerId, int $limit = 10): array
    {
        $stmt = $this->conn->prepare("SELECT b.booking_id, b.booking_date, b.status, c.car_id, c.brand, c.model, c.year, c.price, c.image FROM bookings b JOIN cars c ON b.car_id = c.car_id WHERE b.user_id = ? ORDER BY b.booking_date DESC LIMIT ?");
        
        if (!$stmt) {
            error_log("Failed to prepare getBookingHistory statement: " . $this->conn->error);
            return [];
        }
        
        $stmt->bind_param("ii", $customerId, $limit);
        $stmt->execute();
        $history = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        
        return $history;
    }

    /**
     * Get featured cars available for booking
     * Returns cars that are currently available
     *
     * @param int $limit Number of cars to retrieve
     * @return array List of available cars
     */
    public function getFeaturedCars(int $limit = 6): array
    {
        $stmt = $this->conn->prepare("SELECT car_id, brand, model, year, price, image, description, status FROM cars WHERE status = 'available' ORDER BY created_at DESC LIMIT ?");
        
        if (!$stmt) {
            error_log("Failed to prepare getFeaturedCars statement: " . $this->conn->error);
            return [];
        }
        
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $cars = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        
        return $cars;
    }
}