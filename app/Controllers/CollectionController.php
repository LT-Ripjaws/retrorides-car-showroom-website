<?php
namespace App\Controllers;

use App\Core\BaseController;
use App\Models\CollectionModel;

/**
 * CollectionController - Public car collection display and filtering
 * 
 * Handles public-facing car collection operations including:
 * - Display of available cars with filtering options
 * - Advanced search functionality across multiple fields
 * - Brand-based filtering and price range filtering
 * - Car data formatting and presentation
 * - Responsive pagination and sorting options
 * 
 * Features:
 * - Multi-criteria filtering (brand, price, search terms)
 * - Safe data handling and XSS protection
 * - Responsive image handling with fallbacks
 * - Clean URL structure for better UX
 */
class CollectionController extends BaseController
{
    public function __construct(private CollectionModel $model) 
    {
    }

    /**
     * Display main collection page with filtering capabilities
     */
    public function index(): void 
    {
        
        // Parse and validate filters from request
        $filters = $this->parseFilters($_GET);
        
        // Fetch filtered cars
        $cars = $this->model->getCarsWithFilters($filters);
        
        // Get supporting data for filters
        $brands = $this->model->getAvailableBrands();
        $priceRange = $this->model->getPriceRange();
        
        // Format cars for display
        $formattedCars = $this->formatCarData($cars);
        
        // page data taht we pass into the view
        $pageData = [
            'title' => $this->generatePageTitle($filters),
            'description' => $this->generateMetaDescription($formattedCars, $filters),
            'cars' => $formattedCars,
            'brands' => $brands,
            'priceRange' => $priceRange,
            'filters' => $filters,
            'carCount' => count($formattedCars),
            'hasFilters' => (
                !empty($filters['brand']) ||
                !empty($filters['search']) ||
                isset($filters['min_price']) ||
                isset($filters['max_price']) ||
                ($filters['sort'] ?? 'year_asc') !== 'year_asc'
            ),
            'pageCSS' => '/assets/css/collection.css'
        ];
        
        
        $this->renderView('CollectionView.php', $pageData, 'main');
    }

    /**
     * Parse and validate filter parameters from request
     * 
     * Handles multiple filter types with proper validation:
     */
    private function parseFilters(array $requestData): array 
    {
        $filters = [];

        // Brand filter
        $brand = cleanInput($requestData['brand'] ?? '');
        if (!empty($brand)) {
            $filters['brand'] = $brand;
        }

        // Price range filters - handling null value
        $minPrice = filter_var($requestData['min_price'] ?? null, FILTER_VALIDATE_FLOAT);
        $maxPrice = filter_var($requestData['max_price'] ?? null, FILTER_VALIDATE_FLOAT);
        
        if ($minPrice !== false && $minPrice >= 0) {
            $filters['min_price'] = $minPrice;
        }
        if ($maxPrice !== false && $maxPrice >= 0) {
            $filters['max_price'] = $maxPrice;
        }

        // Search term filter
        $search = cleanInput($requestData['search'] ?? '');
        if (!empty($search)) {
            $filters['search'] = $search;
        }

        // Sort option validation
        $validSortOptions = ['year_asc', 'year_desc', 'price_asc', 'price_desc', 'brand'];
        $sort = $requestData['sort'] ?? 'year_asc';
        if (in_array($sort, $validSortOptions, true)) {
            $filters['sort'] = $sort;
        } else {
            $filters['sort'] = 'year_asc';
        }

        return $filters;
    }

    /**
     * Format car data for safe display in views
     * 
     * Applies consistent formatting and security measures:
     * - HTML escaping for XSS protection
     * - Price formatting with currency symbols
     * - Image URL generation with fallbacks
     * - Description truncation for card layouts
     * - Type casting for numeric values
     */
    private function formatCarData(array $cars): array 
    {
        $baseUrl = getBasePath();
        
        return array_map(function($car) use ($baseUrl) {
            return [
                'car_id' => (int)$car['car_id'],
                'brand' => htmlspecialchars($car['brand'], ENT_QUOTES, 'UTF-8'),
                'model' => htmlspecialchars($car['model'], ENT_QUOTES, 'UTF-8'),
                'year' => (int)$car['year'],
                'description' => htmlspecialchars($car['description'], ENT_QUOTES, 'UTF-8'),
                'short_description' => $this->truncateDescription($car['description'], 150),
                'price' => (float)$car['price'],
                'formatted_price' => '$' . number_format($car['price'], 0),
                'image_url' =>  $this->getSafeImageUrl($car['image'], 'uploads', 'default-car.jpg'),
                'title' => htmlspecialchars(
                    $car['year'] . ' ' . $car['brand'] . ' ' . $car['model'], 
                    ENT_QUOTES, 
                    'UTF-8'
                ),
                'status' => $car['status']
            ];
        }, $cars);
    }

    /**
     * Truncates at word boundaries for better readability
     */
    private function truncateDescription(string $description, int $maxLength): string 
    {
        $description = strip_tags($description);
        
        if (strlen($description) <= $maxLength) {
            return htmlspecialchars($description, ENT_QUOTES, 'UTF-8');
        }
        
        $truncated = substr($description, 0, $maxLength);
        $lastSpace = strrpos($truncated, ' ');
        
        if ($lastSpace !== false) {
            $truncated = substr($truncated, 0, $lastSpace);
        }
        
        return htmlspecialchars($truncated . '...', ENT_QUOTES, 'UTF-8');
    }


    /**
     * dynamic page title based on active filters
     * 
     */
    private function generatePageTitle(array $filters): string 
    {
        $title = 'Car Collection';
        
        if (!empty($filters['brand'])) {
            $title = $filters['brand'] . ' Cars';
        }
        
        if (!empty($filters['search'])) {
            $title = 'Search: ' . $filters['search'];
        }
        
        return $title . ' - RetroRides';
    }

    /**
     * dynamic meta description
     */
    private function generateMetaDescription(array $cars, array $filters): string 
    {
        $count = count($cars);
        $base = "Browse our collection of {$count} quality vintage cars.";
        
        if (!empty($filters['brand'])) {
            $base = "Discover {$count} {$filters['brand']} vehicles in our collection.";
        }
        
        return $base . ' Find your perfect classic car with RetroRides.';
    }
}