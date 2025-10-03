<?php
namespace App\Models;
use mysqli;

/**
 * CollectionModel - Car collection data management
 * 
 * Handles all car collection operations including:
 * - Retrieving available cars with various filters
 * - Car search functionality across multiple fields
 * - Brand and price range filtering
 * - Data validation and sanitization
 * - Safe database operations with prepared statements
 */
class CollectionModel 
{
    public function __construct(private mysqli $conn) 
    {
    }

    /**
     * Get all available brands
     * Returns unique brands from available cars only
     * 
     * @return array Array of brand names
     */
    public function getAvailableBrands(): array 
    {
        $stmt = $this->conn->prepare("
            SELECT DISTINCT brand 
            FROM cars 
            WHERE status = 'available'
            ORDER BY brand ASC
        ");
        
        $stmt->execute();
        $result = $stmt->get_result();
        $brands = [];
        
        while ($row = $result->fetch_assoc()) {
            $brands[] = $row['brand'];
        }
        
        $stmt->close();
        return $brands;
    }

    /**
     * Get the price range of available cars
     * Returns minimum and maximum prices for filtering
     * 
     * @return array Array with 'min' and 'max' price values
     */
    public function getPriceRange(): array 
    {
        $stmt = $this->conn->prepare("
            SELECT MIN(price) as min_price, MAX(price) as max_price 
            FROM cars 
            WHERE status = 'available'
        ");
        
        $stmt->execute();
        $result = $stmt->get_result();
        $priceRange = $result->fetch_assoc();
        $stmt->close();
        
        return [
            'min' => (float)($priceRange['min_price'] ?? 0),
            'max' => (float)($priceRange['max_price'] ?? 0)
        ];
    }

    /**
     * Get cars with advanced filtering options
     * Combines multiple filters for comprehensive car search
     * 
     * @param array $filters Array of filter options
     * @return array Filtered cars array
     */
    public function getCarsWithFilters(array $filters = []): array
    {
    
        $query = "SELECT car_id, brand, model, year, description, price, image, status FROM cars WHERE status = 'available'";
        $params = [];
        $types = '';

        // Add brand filter
        if (!empty($filters['brand'])) {
            $query .= " AND brand = ?";
            $params[] = $filters['brand'];
            $types .= 's';
        }

        // Add price range filter
        if (isset($filters['min_price']) && isset($filters['max_price'])) {
            $minPrice = (float)$filters['min_price'];
            $maxPrice = (float)$filters['max_price'];
            
            $query .= " AND price BETWEEN ? AND ?";
            $params[] = $minPrice;
            $params[] = $maxPrice;
            $types .= 'dd';
        }

        // Add search term filter
        if (!empty($filters['search'])) {
            $searchPattern = '%' . $filters['search'] . '%';
            $query .= " AND (brand LIKE ? OR model LIKE ? OR description LIKE ?)";
            $params[] = $searchPattern;
            $params[] = $searchPattern;
            $params[] = $searchPattern;
            $types .= 'sss';
        }

        // sorting
        $sortOrder = $filters['sort'] ?? 'year_asc';
        switch ($sortOrder) {
            case 'price_asc':
                $query .= " ORDER BY price ASC";
                break;
            case 'price_desc':
                $query .= " ORDER BY price DESC";
                break;
            case 'year_desc':
                $query .= " ORDER BY year DESC";
                break;
            case 'brand':
                $query .= " ORDER BY brand ASC, model ASC";
                break;
            default:
                $query .= " ORDER BY year ASC";
        }

        $stmt = $this->conn->prepare($query);
        
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $cars = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        
        return $cars;
    }
}