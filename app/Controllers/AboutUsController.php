<?php
namespace App\Controllers;
use App\Core\BaseController;

/**
 * AboutController - Handles the About Us page :D
 */
class AboutUsController extends BaseController
{
    /**
     * Display the About Us page
     */
    public function index(): void 
    {
        // Page data
        $pageData = [
            'title' => 'About RetroRides',
            'description' => 'Learn about our passion for vintage automobiles and our commitment to preserving automotive history.',
            'foundedYear' => 1947,
            'yearsInBusiness' => date('Y') - 1947,
            'carsRestored' => 2500,
            'happyCustomers' => 1200,
            'heroImage' => $this->getSafeImageUrl('vintage-garage.jpg', 'about', 'default-car.jpg'),
            'teamMembers' => [
                [
                    'name' => 'Person1',
                    'position' => 'Founder & CEO',
                    'bio' => 'With over 30 years of experience in vintage car restoration, Person1 founded RetroRides with a vision to preserve automotive history.',
                    'image' => $this->getSafeImageUrl('person1.jpg', 'about/team', 'default-person.jpg')
                ],
                [
                    'name' => 'Person3',
                    'position' => 'Head of Restoration',
                    'bio' => 'Person3 brings 15 years of mechanical expertise and an eye for authentic restoration details.',
                    'image' => $this->getSafeImageUrl('person3.jpg', 'about/team', 'default-person.jpg')
                ],
                [
                    'name' => 'Person2',
                    'position' => 'Sales Manager',
                    'bio' => 'Person2 helps customers find their perfect vintage ride with his extensive knowledge of classic automobiles.',
                    'image' => $this->getSafeImageUrl('person2.jpg', 'about/team', 'default-person.jpg')
                ]
            ],
            'pageCSS' => '/assets/css/about.css'
        ];
        
        $this->renderView('AboutUsView.php', $pageData, 'main');
    }
}