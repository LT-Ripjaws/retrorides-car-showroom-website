<?php
namespace App\Controllers\Admin;
use App\Core\BaseController;
use App\Models\Admin\DashboardModel;

/**
 * DashboardController - Admin dashboard overview and analytics
 * 
 * Provides comprehensive admin dashboard functionality including:
 * - Real-time system statistics and KPIs
 * - Revenue analytics and financial metrics
 * - Recent activity feeds (users, cars, bookings)
 * - Sales performance charts and trends
 * - Brand performance analytics
 * - Data visualization for business insights
 * 
 * Dashboard Metrics:
 * - Total users, employees, cars, and bookings count
 * - Revenue tracking and financial summaries
 * - 30-day sales trend analysis
 * - Top-performing brands by sales volume
 * - Recent system activity monitoring
 * 
 * Security Features:
 * - Admin role requirement for dashboard access
 * - Safe data handling and display
 * - Error handling for missing or invalid data
 */
class DashboardController extends BaseController
{
    public function __construct(private DashboardModel $model) 
    {
    }

    /**
     * Display admin dashboard with comprehensive analytics
     */
    public function index(): void
    {
        requireRole('admin');
        
        // Fetch all dashboard data 
        $dashboardData = $this->fetchDashboardData();

        $this->renderView('Admin/Dashboard.php', $dashboardData, 'admin');
    }

    /**
     * Fetch all required dashboard data from model
     * 
     * Collects data from multiple sources:
     * - System counts (users, cars, bookings, employees)
     * - Recent activity (latest users and cars)
     * - Analytics data (sales trends, brand performance)
     * 
     * @return array Comprehensive dashboard data array
     */
    private function fetchDashboardData(): array
    {
        // Fetch core system counts
        $counts = $this->model->getCounts();
        
        // Fetch financial data
        $revenue = $this->model->getRevenue();
        
        // Fetch recent activity data
        $recentUsers = $this->model->getRecentUsers(5);
        $recentCars = $this->model->getRecentCars(5);
        
        // Fetch analytics data for charts
        $salesData = $this->model->getSalesLast30Days();
        $brandsData = $this->model->getSalesByBrand(5);
        
        return [
            // System statistics
            'usersCount' => $counts['users'] ?? 0,
            'employees' => $counts['employees'] ?? 0,
            'carsCount' => $counts['cars'] ?? 0,
            'bookings' => $counts['bookings'] ?? 0,
            
            // Financial metrics
            'revenue' => $revenue,
            
            // Recent activity
            'recentUsers' => $recentUsers,
            'recentCars' => $recentCars,
            
            // Chart data for sales trends
            'dateLabels' => $salesData['labels'] ?? [],
            'salesData' => $salesData['data'] ?? [],
            
            // Chart data for brand performance
            'brandLabels' => $brandsData['labels'] ?? [],
            'brandData' => $brandsData['data'] ?? [],

            'pageCSS' => '/assets/css/Admin/dashboard.css',
            'pageJS' => '/assets/js/Admin/dashboard.js',
            'title' => 'Admin Panel - Dashboard'
        ];
    }
}