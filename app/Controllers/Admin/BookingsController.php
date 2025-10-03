<?php
namespace App\Controllers\Admin;
use App\Core\BaseController;
use App\Models\Admin\BookingsModel;

class BookingsController extends BaseController
{
    

    public function __construct(private BookingsModel $model)
    {
        // Controller-specific error messages
        $this->errorMessages = [
            'booking_not_found' => 'Booking not found.',
            'already_cancelled' => 'This booking is already cancelled.',
            'cannot_cancel' => 'Cannot cancel completed or sold bookings.',
            'cancel_failed' => 'Failed to cancel booking.',
            'cancelled_cannot_sell' => 'Cannot mark cancelled bookings as sold.',
            'already_sold' => 'This booking is already marked as sold.',
            'mark_sold_failed' => 'Failed to mark booking as sold.'
        ];

        // Controller-specific success messages
        $this->successMessages = [
            'cancelled' => 'Booking cancelled successfully!',
            'sold' => 'Booking marked as sold successfully!'
        ];
    }

    /**
     * Initialize the page
     * Display all bookings
     */
    public function index(): void
    {
        requireRole('admin');
        generateCSRFToken();

        $bookings = $this->model->getAllBookings();

        $this->renderView('Admin/BookingsView.php', [
            'bookings' => $bookings,
            'pageCSS' => '/assets/css/Admin/cars.css',
            'title' => 'Bookings Management'
        ], 'admin');
    }

    /* ---------------------------- ACTIONS ------------------------------ */
    /**
     * Cancel a booking
     */
    public function cancelBooking(): void
    {
        requireRole('admin');

        if ($this->isValidPostRequest(['booking_id'])) {
            $this->handleCrudOperation(function () {
                $this->handleCancelBooking();
            }, 'admin/bookings');
        } else {
            $this->redirectWithStatus('admin/bookings');
        }
    }

    /**
     * Mark booking as sold
     */
    public function markAsSold(): void
    {
        requireRole('admin');

        if ($this->isValidPostRequest(['booking_id'])) {
            $this->handleCrudOperation(function () {
                $this->handleMarkAsSold();
            }, 'admin/bookings');
        } else {
            $this->redirectWithStatus('admin/bookings');
        }
    }

    /* ---------------------------- Methods for those actions ------------------------------ */

    /**
     * Handle booking cancellation
     */
    private function handleCancelBooking(): void
    {
        $bookingId = $this->validateId('booking_id', 'POST');
        if (!$bookingId) return;

        $booking = $this->model->getBookingById($bookingId);
        if (!$booking) {
            $this->setError('booking_not_found');
            return;
        }

        if ($booking['status'] === 'cancelled') {
            $this->setError('already_cancelled');
            return;
        }

        if ($booking['status'] === 'sold') {
            $this->setError('cannot_cancel');
            return;
        }

        if ($this->model->cancelBooking($bookingId)) {
            $this->setSuccess('cancelled');
        } else {
            $this->setError('cancel_failed');
        }
    }

    /**
     * Handle marking booking as sold
     */
    private function handleMarkAsSold(): void
    {
        $bookingId = $this->validateId('booking_id', 'POST');
        if (!$bookingId) return;

        $booking = $this->model->getBookingById($bookingId);
        if (!$booking) {
            $this->setError('booking_not_found');
            return;
        }

        if ($booking['status'] === 'cancelled') {
            $this->setError('cancelled_cannot_sell');
            return;
        }

        if ($booking['status'] === 'sold') {
            $this->setError('already_sold');
            return;
        }

        if ($this->model->markBookingAsSold($bookingId)) {
            $this->setSuccess('sold');
        } else {
            $this->setError('mark_sold_failed');
        }
    }
}
