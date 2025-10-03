<?php
namespace App\Controllers;
use App\Core\BaseController;

/**
 * HomeController - Handles the landing page
 * Main entry point for visitors to RetroRides
 */
class HomeController extends BaseController
{
    /**
     * Display the home page
     */
    public function index(): void 
    {
        $pageData = [
            'title' => 'Home',
            'description' => 'RetroRides – Discover timeless classics, beautifully preserved.',
            
            // Featured cars data
            'featuredCars' => [
                [
                    'name' => '1967 Ford Mustang',
                    'image_url' => $this->getSafeImageUrl('1967 Ford Mustang Fastback.png', 'Home/featured', 'default-car.jpg'),
                    'alt' => '1967 Ford Mustang'
                ],
                [
                    'name' => '1959 Cadillac Eldorado',
                    'image_url' => $this->getSafeImageUrl('1959 cadillac eldorado.png', 'Home/featured', 'default-car.jpg'),
                    'alt' => '1959 Cadillac Eldorado'
                ],
                [
                    'name' => 'Mercedes-Benz 300SL Gullwing',
                    'image_url' => $this->getSafeImageUrl('mercedes-benz 300sl gullwing.png', 'Home/featured', 'default-car.jpg'),
                    'alt' => 'Mercedes-Benz 300SL Gullwing'
                ]
            ],
            
            // Slideshow images
            'slideshowImages' => [
                $this->getSafeImageUrl('Mercedes-Benz 300 SL Gullwing.gif', 'Home/slideshow', 'default-car.gif'),
                $this->getSafeImageUrl('1963 Corvette Split Window Coupe.gif', 'Home/slideshow', 'default-car.gif'),
                $this->getSafeImageUrl('FORD MUSTANG 1967 FASTBACK.gif', 'Home/slideshow', 'default-car.gif'),
                $this->getSafeImageUrl('Rocking Ragtop - 1962 VW Beetle - FRVRBRK.gif', 'Home/slideshow', 'default-car.gif')
            ],
            
            // Testimonials data
            'testimonials' => [
                ['quote' => 'RetroRides is a dream come true for car enthusiasts!', 'author' => 'Alex J.'],
                ['quote' => 'Amazing collection and super easy to browse through.', 'author' => 'Maria K.'],
                ['quote' => 'I found the classic car I always wanted. Highly recommended!', 'author' => 'Sam W.'],
                ['quote' => 'Their platform is smooth, stylish, and trustworthy.', 'author' => 'Liam D.'],
                ['quote' => 'Great experience, I\'ll definitely use RetroRides again.', 'author' => 'Sophie L.']
            ],
            
            // Brand logos
            'brands' => [
                ['name' => 'Ford', 'logo_url' => $this->getSafeImageUrl('ford.png', 'Home/brands', 'default-car.png')],
                ['name' => 'Mercedes-Benz', 'logo_url' => $this->getSafeImageUrl('mercedes.png', 'Home/brands', 'default-car.png')],
                ['name' => 'Cadillac', 'logo_url' => $this->getSafeImageUrl('cadillac.png', 'Home/brands', 'default-car.png')],
                ['name' => 'Volkswagen', 'logo_url' => $this->getSafeImageUrl('vw.png', 'Home/brands', 'default-car.png')],
                ['name' => 'Chevrolet', 'logo_url' => $this->getSafeImageUrl('chevrolet.png', 'Home/brands', 'default-car.png')]
            ],
            
            // Company info
            'companyInfo' => [
                'foundedYear' => 1947,
                'yearsInBusiness' => date('Y') - 1947
            ],

            'pageCSS' => '/assets/css/home.css'

        ];
        
        $this->renderView('HomeView.php', $pageData, 'main');
    }
}