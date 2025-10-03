<?php
namespace App\Models\Admin;

use mysqli;

/**
 * DashboardModel – Data access layer for admin dashboard statistics
 *
 * Responsibilities:
 * • Aggregates key metrics for dashboard display (counts, revenue, recent entries)
 * • Provides data for charts and quick summaries
 * • Encapsulates all database queries related to dashboard reporting
 */
class DashboardModel
{

    public function __construct(private mysqli $conn)
    {
    }

    /**
     * Get counts of rows in key tables
     *
     * @return array Associative array with table names as keys and counts as values
     */
    public function getCounts(): array
    {
        $tables = ['users', 'employees', 'cars', 'bookings'];
        $counts = [];

        foreach ($tables as $t) {
            
            $sql = "SELECT COUNT(*) AS total FROM `$t`";
            $res = $this->conn->query($sql);

            // If query succeeds, get count; otherwise, default to 0
            $counts[$t] = $res ? (int)$res->fetch_assoc()['total'] : 0;
        }

        return $counts;
    }

    /**
     * Calculate total revenue from sold bookings
     *
     * @return float Total revenue
     */
    public function getRevenue(): float
    {
        $sql = "
            SELECT SUM(c.price) AS total
            FROM bookings b
            JOIN cars c ON b.car_id = c.car_id
            WHERE b.status = 'sold'
        ";
        $res = $this->conn->query($sql);

        // Return 0.0 if query fails or no results
        return $res ? (float)$res->fetch_assoc()['total'] : 0.0;
    }

    /**
     * Get the most recently registered users
     *
     * @param int $limit Number of users to retrieve
     * @return array List of users with id, username, email, and role
     */
    public function getRecentUsers(int $limit = 5): array
    {
        $stmt = $this->conn->prepare("
            SELECT id, username, email, role
            FROM users
            ORDER BY created_at DESC
            LIMIT ?
        ");
        $stmt->bind_param("i", $limit);
        $stmt->execute();

        $users = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $users;
    }

    /**
     * Get the most recently added cars
     *
     * @param int $limit Number of cars to retrieve
     * @return array List of cars with car_id, brand, model, and year
     */
    public function getRecentCars(int $limit = 5): array
    {
        $stmt = $this->conn->prepare("
            SELECT car_id, brand, model, year
            FROM cars
            ORDER BY created_at DESC
            LIMIT ?
        ");
        $stmt->bind_param("i", $limit);
        $stmt->execute();

        $cars = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $cars;
    }

    /**
     * Get sales count per day for the last 30 days
     * Useful for line charts or trend visualization
     *
     * @return array ['labels' => [dates], 'data' => [sales_count]]
     */
    public function getSalesLast30Days(): array
    {
        $labels = [];
        $data = [];

        $sql = "
            SELECT DATE(booking_date) AS day, COUNT(*) AS total
            FROM bookings
            WHERE booking_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
            GROUP BY day
            ORDER BY day ASC
        ";

        $res = $this->conn->query($sql);

        while ($row = $res->fetch_assoc()) {
            $labels[] = $row['day'];
            $data[] = (int)$row['total'];
        }

        return ['labels' => $labels, 'data' => $data];
    }

    /**
     * Get the top-selling brands
     * Useful for bar charts or pie charts
     *
     * @param int $limit Number of brands to retrieve
     * @return array ['labels' => [brands], 'data' => [sales_count]]
     */
    public function getSalesByBrand(int $limit = 5): array
    {
        $labels = [];
        $data = [];

        $stmt = $this->conn->prepare("
            SELECT c.brand, COUNT(b.booking_id) AS total
            FROM bookings b
            JOIN cars c ON b.car_id = c.car_id
            GROUP BY c.brand
            ORDER BY total DESC
            LIMIT ?
        ");
        $stmt->bind_param("i", $limit);
        $stmt->execute();

        $res = $stmt->get_result();

        while ($row = $res->fetch_assoc()) {
            $labels[] = $row['brand'];
            $data[] = (int)$row['total'];
        }

        $stmt->close();

        return ['labels' => $labels, 'data' => $data];
    }
}
