<?php
namespace App\Models\Admin;
use mysqli;

/**
 * BookingsModel – Data access layer for car booking records
 *
 * Encapsulates all database interactions related to customer bookings:
 * - Retrieves booking details (with joined car information)
 * - Provides convenience methods to cancel or mark bookings as sold
 *
 * Security & Practices:
 * - Uses prepared statements with bound parameters to prevent SQL injection
 * - Validates booking status against an internal whitelist before updates
 * - Returns clean associative arrays for controller consumption
 */
class BookingsModel
{
    
    public function __construct(private mysqli $conn)
    {
    
    }

    /**
     * Retrieve all bookings with related car details
     *
     * @return array List of all bookings as associative arrays
     */
    public function getAllBookings(): array
    {
        $stmt = $this->conn->prepare("
            SELECT 
                b.booking_id, b.customer_name, b.customer_email, b.booking_date, b.status,
                c.brand, c.model, c.year
            FROM bookings b
            JOIN cars c ON b.car_id = c.car_id
            ORDER BY b.booking_date DESC
        ");
        $stmt->execute();
        $result   = $stmt->get_result();
        $bookings = $result->fetch_all(MYSQLI_ASSOC); // Fetch all rows as associative arrays
        $stmt->close();
        return $bookings;
    }

    /**
     * Retrieve a single booking by its unique ID
     *
     * @param  int $bookingId ID of the booking to fetch
     * @return array|null Associative array of booking data or null if not found
     */
    public function getBookingById(int $bookingId): ?array
    {
        $stmt = $this->conn->prepare("
            SELECT 
                b.booking_id, b.customer_name, b.customer_email, b.booking_date, b.status, b.car_id,
                c.brand, c.model, c.year
            FROM bookings b
            JOIN cars c ON b.car_id = c.car_id
            WHERE b.booking_id = ?
        ");

        $stmt->bind_param("i", $bookingId);
        $stmt->execute();
        $result  = $stmt->get_result();
        $booking = $result->fetch_assoc();
        $stmt->close();
        return $booking ?: null;
    }

    /**
     * Update the status of a booking after validating it against allowed statuses
     *
     * @param  int    $bookingId Unique ID of the booking to update
     * @param  string $status    New status value (must be in valid list)
     * @return bool   True if update succeeded, false if invalid status or DB error
     */
    public function updateBookingStatus(int $bookingId, string $status): bool
    {
        // Retrieve valid statuses (processing, cancelled, sold)
        $validStatuses = $this->getValidStatuses();

        // Reject if provided status is not allowed
        if (!in_array($status, $validStatuses, true)) {
            return false;
        }

        $stmt = $this->conn->prepare("UPDATE bookings SET status = ? WHERE booking_id = ?");
        $stmt->bind_param("si", $status, $bookingId);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    /**
     * Convenience method to cancel a booking
     *
     * @param  int  $bookingId ID of the booking to cancel
     * @return bool True on successful update, false otherwise
     */
    public function cancelBooking(int $bookingId): bool
    {
        return $this->updateBookingStatus($bookingId, 'cancelled');
    }

    /**
     * Convenience method to mark a booking as sold
     *
     * @param  int  $bookingId ID of the booking to mark as sold
     * @return bool True on successful update, false otherwise
     */
    public function markBookingAsSold(int $bookingId): bool
    {
        return $this->updateBookingStatus($bookingId, 'sold');
    }

    /**
     * Retrieve the whitelist of valid booking status values
     *
     * @return array Array of strings representing allowed statuses
     */
    public function getValidStatuses(): array
    {
        return ['processing', 'cancelled', 'sold'];
    }
}
