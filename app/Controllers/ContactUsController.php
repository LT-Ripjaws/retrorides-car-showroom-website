<?php
namespace App\Controllers;
use App\Core\BaseController;

/**
 * ContactController - Handles the Contact Us page
 */
class ContactUsController extends BaseController
{
    /**
     * Display the Contact Us page
     */
    public function index(): void 
    {
        // Page data
        $pageData = [
            'title' => 'Contact US',
            'description' => 'Get in touch with RetroRides for vintage car sales, restoration services, or general inquiries.',
            'contactInfo' => [
                'phone' => '+88 xxx-xxx-xxxx',
                'email' => 'info@retrorides.com',
                'address' => [
                    'street' => '1247 Classic Car Lane',
                    'city' => 'Vintage City',
                    'state' => 'Dummy State',
                    'zip' => '190210'
                ]
            ],
            'businessHours' => [
                'monday' => '9:00 AM - 6:00 PM',
                'tuesday' => '9:00 AM - 6:00 PM',
                'wednesday' => '9:00 AM - 6:00 PM',
                'thursday' => '9:00 AM - 6:00 PM',
                'friday' => '9:00 AM - 6:00 PM',
                'saturday' => '9:00 AM - 4:00 PM',
                'sunday' => 'Closed'
            ],
            'departments' => [
                'sales' => [
                    'name' => 'Sales Department',
                    'email' => 'sales@retrorides.com',
                    'phone' => '+88 xxx-xxx-xxxx',
                    'description' => 'Questions about our vintage car collection and purchasing'
                ],
                'restoration' => [
                    'name' => 'Restoration Services',
                    'email' => 'restoration@retrorides.com',
                    'phone' => '+88 xxx-xxx-xxxx',
                    'description' => 'Inquiries about car restoration and maintenance services'
                ],
                'parts' => [
                    'name' => 'Parts & Accessories',
                    'email' => 'parts@retrorides.com',
                    'phone' => '+88 xxx-xxx-xxxx',
                    'description' => 'Vintage car parts, accessories, and special orders'
                ]
            ],
            'pageCSS' => '/assets/css/contact.css'
        ];
        
        $this->renderView('ContactUsView.php', $pageData, 'main');
    }
}