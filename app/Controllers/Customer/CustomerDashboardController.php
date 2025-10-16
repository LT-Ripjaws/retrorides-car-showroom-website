<?php
namespace App\Controllers\Customer;

use App\Core\BaseController;
use App\Models\Customer\DashboardModel;

/**
 * CustomerDashboardController - Customer dashboard and account overview
 * 
 * Provides customer-specific dashboard functionality including:
 * - Personal booking management and history
 * - Account statistics and spending overview
 * - Active and completed bookings display
 * - Featured cars and recommendations
 * - Quick access to account information
 * 
 * Dashboard Features:
 * - Current active bookings with status tracking
 * - Complete booking history with filters
 * - Personal statistics (total bookings, total spent)
 * - Featured cars available for booking
 * - Quick profile summary
 * 
 * Security Features:
 * - Customer role requirement for dashboard access
 * - User can only view their own data
 * - Safe data handling and display
 */
class CustomerDashboardController extends BaseController
{
    public function __construct(private DashboardModel $model) 
    {
    }

    /**
     * Display customer dashboard with personalized data
     */
    public function index(): void
    {
        requireRole('customer');
        
        $customerId = $_SESSION['user_id'];
        
        // Fetch all customer dashboard data
        $dashboardData = $this->fetchCustomerDashboardData($customerId);

        $this->renderView('Customer/DashboardView.php', $dashboardData, 'customer');
    }

    /**
     * Fetch all required customer dashboard data
     * 
     * Collects personalized data:
     * - Customer profile information
     * - Booking statistics and history
     * - Active bookings with car details
     * - Featured available cars
     */
    private function fetchCustomerDashboardData(int $customerId): array
    {
        // Fetch customer profile
        $profile = $this->model->getCustomerProfile($customerId);
        
        // Fetch booking statistics
        $stats = $this->model->getCustomerStats($customerId);
        
        // Fetch active bookings
        $activeBookings = $this->model->getActiveBookings($customerId);
        
        // Fetch booking history
        $bookingHistory = $this->model->getBookingHistory($customerId, 5);
        
        // Fetch featured cars (available for booking)
        $featuredCars = $this->model->getFeaturedCars(6);
        
        return [
            // Customer information
            'customerName' => $_SESSION['user_name'] ?? $profile['username'] ?? 'Customer',
            'customerEmail' => $profile['email'] ?? '',
            'memberSince' => $profile['created_at'] ?? '',
            
            // Statistics
            'totalBookings' => $stats['total_bookings'] ?? 0,
            'activeBookings' => $stats['active_bookings'] ?? 0,
            'completedBookings' => $stats['completed_bookings'] ?? 0,
            'totalSpent' => $stats['total_spent'] ?? 0,
            
            // Booking data
            'activeBookingsList' => $activeBookings,
            'bookingHistory' => $bookingHistory,
            
            // Available cars
            'featuredCars' => $featuredCars,
            
            // Page metadata
            'pageCSS' => '/assets/css/Customer/dashboard.css',
            'title' => 'My Dashboard - RetroRides',
            'description' => 'Manage your vintage car bookings and explore our collection'
        ];
    }
}