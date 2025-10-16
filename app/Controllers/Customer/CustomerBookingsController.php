<?php
namespace App\Controllers\Customer;

use App\Core\BaseController;
use App\Models\Customer\BookingsModel;

/**
 * CustomerBookingsController - Manage customer bookings and reservations
 * 
 * Provides comprehensive booking management functionality:
 * - View all bookings with filtering by status
 * - Detailed booking information display
 * - Cancel pending bookings
 * - Track booking history and timeline
 * - Search and sort bookings
 * 
 * Features:
 * - Status-based filtering (All, Processing, Sold, Cancelled)
 * - Booking cancellation with confirmation
 * - Detailed car information for each booking
 * - Booking date and status tracking
 * - Responsive booking cards layout
 * 
 * Security:
 * - Customer role requirement
 * - Users can only manage their own bookings
 * - CSRF protection for booking actions
 */
class CustomerBookingsController extends BaseController
{
    public function __construct(private BookingsModel $model) 
    {
        $this->errorMessages = [
            'cancel_failed' => 'Failed to cancel booking. Please try again.',
            'not_found' => 'Booking not found.',
            'already_processed' => 'This booking has already been processed and cannot be cancelled.',
            'invalid_booking' => 'Invalid booking ID provided.'
        ];

        $this->successMessages = [
            'cancelled' => 'Booking cancelled successfully.'
        ];
    }

    /**
     * Display all customer bookings with filtering
     */
    public function index(): void
    {
        requireRole('customer');
        
        $customerId = $_SESSION['user_id'];
        $statusFilter = $_GET['status'] ?? 'all';
        
        // Fetch bookings data
        $bookingsData = $this->fetchBookingsData($customerId, $statusFilter);

        $this->renderView('Customer/BookingsView.php', $bookingsData, 'customer');
    }

    /**
     * Cancel a booking
     */
    public function cancelBooking(): void
    {
        requireRole('customer');

        $this->handleCrudOperation(
            fn() => $this->processCancelBooking(),
            'customer/bookings'
        );
    }

    /**
     * Fetch all bookings data for the customer
     */
    private function fetchBookingsData(int $customerId, string $statusFilter): array
    {
        // Get all bookings
        $allBookings = $this->model->getAllBookings($customerId);
        
        // Filter bookings by status
        $filteredBookings = $this->filterBookingsByStatus($allBookings, $statusFilter);
        
        // Get booking statistics
        $stats = $this->model->getBookingStatistics($customerId);
        
        return [
            'bookings' => $filteredBookings,
            'allBookings' => $allBookings,
            'currentFilter' => $statusFilter,
            'totalCount' => count($allBookings),
            'pendingCount' => $stats['processing'] ?? 0,
            'soldCount' => $stats['sold'] ?? 0,
            'cancelledCount' => $stats['cancelled'] ?? 0,
            'csrfToken' => $_SESSION['csrf_token'] ?? '',
            'pageCSS' => '/assets/css/Customer/bookings.css',
            'pageJS' => '/assets/js/Customer/bookings.js',
            'title' => 'My Bookings - RetroRides',
            'description' => 'View and manage your vintage car bookings'
        ];
    }

    /**
     * Filter bookings by status
     */
    private function filterBookingsByStatus(array $bookings, string $status): array
    {
        if ($status === 'all') {
            return $bookings;
        }

        return array_filter($bookings, function($booking) use ($status) {
            return strtolower($booking['status']) === strtolower($status);
        });
    }

    /**
     * Process booking cancellation
     */
    private function processCancelBooking(): void
    {
        $bookingId = $this->validateId('booking_id', 'POST');
        
        if (!$bookingId) {
            $this->setError('invalid_booking');
            return;
        }

        $customerId = $_SESSION['user_id'];
        
        // Verify booking belongs to customer
        $booking = $this->model->getBookingById($bookingId, $customerId);
        
        if (!$booking) {
            $this->setError('not_found');
            return;
        }

        // Check if booking can be cancelled
        if ($booking['status'] !== 'processing') {
            $this->setError('already_processed');
            return;
        }

        // Cancel the booking
        $result = $this->model->cancelBooking($bookingId, $customerId);
        
        if ($result) {
            $this->setSuccess('cancelled');
        } else {
            $this->setError('cancel_failed');
        }
    }
}