<?php
namespace App\Controllers;

use App\Core\BaseController;
use App\Models\CarBookingModel;

class CarBookingController extends BaseController
{
    public function __construct(private CarBookingModel $model)
    {
        $this->errorMessages = [
            'car_not_found' => 'The selected car could not be found.',
            'car_unavailable' => 'Sorry, this car is no longer available for purchase.',
            'booking_failed' => 'Failed to create booking. Please try again.',
            'missing_car_id' => 'Please select a car to book.',
        ];

        $this->successMessages = [
            'booking_created' => 'Booking created successfully! We will contact you shortly.',
        ];
    }

    // Show the car booking form
    public function showBookingForm(): void
    {
        requireLogin();
        requireRole('customer');
        generateCSRFToken();


        $carId = $this->validateId('car_id', 'POST')
                ?? $this->validateId('car_id', 'GET');

        if (!$carId || !is_numeric($carId)) {
            $this->setError('missing_car_id');
            $this->redirectwithStatus('collection');
            return;
        }

        $car = $this->model->getCarById($carId);

        if (!$car) {
            $this->setError('car_not_found');
            $this->redirectwithStatus('collection');
            return;
        }
        if($car['status'] !== 'available'){
            $this->setError('car_unavailable');
            $this->redirectwithStatus('collection');
            return;
        }

        if(!$this->model->isCarAvailable($carId)){
            $this->setError('car_unavailable');
            $this->redirectwithStatus('collection');
            return;
        }

        $carData = [
            'car_id' => (int)$car['car_id'],
            'title' => $car['year'] . ' ' . $car['brand'] . ' ' . $car['model'],
            'brand' => htmlspecialchars($car['brand'], ENT_QUOTES, 'UTF-8'),
            'model' => htmlspecialchars($car['model'], ENT_QUOTES, 'UTF-8'),
            'year' => (int)$car['year'],
            'price' => '$' . number_format($car['price'],0),
            'price_raw' => (float)$car['price'],
            'image_url' => $this->getSafeImageUrl($car['image'], 'uploads', 'default-car.jpg'),
            'description' => htmlspecialchars($car['description'], ENT_QUOTES, 'UTF-8'),
            'vin' => htmlspecialchars($car['vin'], ENT_QUOTES, 'UTF-8'),
        ];

        $userData = [
            'user_id' => $_SESSION['user_id'],
            'name' => htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8'),
        ];

        $viewData = [
            'title' => 'Book Car - ' . $carData['title'],
            'car' => $carData,
            'user' => $userData,
            'pageCSS' => '/assets/css/carbooking.css',
            'pageJS' => '/assets/js/carbooking.js',
        ];
        $this->renderView('CarBookingView.php', $viewData, 'main');
        
    }


    public function processBooking()
{
    requireLogin();
    requireRole('customer');

    $carId = $_POST['car_id'] ?? null;

    if (!$carId || !is_numeric($carId)) {
        $this->setError('missing_car_id');
        $this->redirectWithStatus('collection');
        return;
    }

   
    if (!validateCSRF()) {
        $this->setError('csrf');
        $this->redirectWithStatus('booking?car_id=' . $carId);
        return;
    }

    
    $this->processValidation();

  
    if ($this->success) {
        $this->redirectWithStatus('booking/confirmation');
    } else {
        $this->redirectWithStatus('booking?car_id=' . $carId);
    }
}




    public function processValidation()
{
    $carId = $this->validateId('car_id');
    $customerName = $this->validateString('name');
    $customerEmail = $this->validateEmail('email');

    if(!$carId || !$customerName || !$customerEmail){
        $this->success = false; 
        return;
    }

    if(!$this->model->isCarAvailable($carId)){
        $this->setError('car_unavailable');
        $this->success = false; 
        return;
    }

    $bookingData = [
        'user_id' => $_SESSION['user_id'],
        'car_id' => $carId,
        'customer_name' => $customerName,
        'customer_email' => $customerEmail
    ];

    $bookingId = $this->model->createBooking($bookingData);

    if($bookingId)
    {
        $_SESSION['last_booking_id'] = $bookingId;
        $this->setSuccess('booking_created');
        $this->success = true; 
    }
    else
    {
        $this->setError('booking_failed');
        $this->success = false; 
    }
}

    public function showConfirmation()
    {
         requireLogin();

        
        $bookingId = $_SESSION['last_booking_id'] ?? null;

        if (!$bookingId) {
            $this->redirectWithStatus('collection');
            return;
        }

        unset($_SESSION['last_booking_id']);

        $viewData = [
            'title' => 'Booking Confirmed',
            'bookingId' => $bookingId
            
        ];

        $this->renderView('BookingConfirmationView.php', $viewData, 'main');
    }
    

}