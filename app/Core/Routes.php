<?php
use App\Controllers\HomeController;
use App\Controllers\Auth\LoginController;
use App\Controllers\Auth\RegisterController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\TeamsController;
use App\Controllers\Admin\CarsController;
use App\Controllers\Admin\BookingsController;
use App\Controllers\Admin\ProfileController;
use App\Controllers\Admin\UsersController;
use App\Controllers\CollectionController;
use App\Controllers\AboutUsController;
use App\Controllers\ContactUsController;
use App\Controllers\Customer\CustomerDashboardController;
use App\Controllers\Customer\CustomerBookingsController;
use App\Controllers\CarBookingController;
use App\Controllers\Customer\CustomerProfileController;



/**
 * ---------------- Public Routes ----------------
 */
$router->get('/', [HomeController::class, 'index']);

$router->get('/login', [LoginController::class, 'showLoginForm']);
$router->post('/login', [LoginController::class, 'processLogin']);

$router->get('/register', [RegisterController::class, 'showRegisterForm']);
$router->post('/register', [RegisterController::class, 'processRegister']);

$router->get('/logout', [LoginController::class,'logout']); 

$router->get('/collection', [CollectionController::class, 'index']);
$router->get('/about', [AboutUsController::class, 'index']);
$router->get('/contact', [ContactUsController::class, 'index']);


$router->get('/booking', [CarBookingController::class, 'showBookingForm']);
$router->post('/booking/create', [CarBookingController::class, 'processBooking']);
$router->get('/booking/confirmation', [CarBookingController::class, 'showConfirmation']);

/**===========================================================
 *  Customer
 *============================================================ */

/**
 * ---------------- Customer Dashboard ----------------
 */
$router->get('/customer/dashboard', [CustomerDashboardController::class, 'index']);

/**
 * ---------------- Customer Bookings ----------------
 */

$router->get('/customer/bookings', [CustomerBookingsController::class, 'index']);
$router->post('/customer/bookings/cancel', [CustomerBookingsController::class, 'cancelBooking']);

/**
 * ---------------- Profile management ----------------
 */
$router->get('/customer/profile', [CustomerProfileController::class, 'showProfile']);
$router->post('/customer/profile/update', [CustomerProfileController::class, 'updateProfile']);
$router->post('/customer/profile/password', [CustomerProfileController::class, 'updatePassword']);


/**===========================================================
 *  Admin
 *============================================================ */

/**
 * ---------------- Admin Dashboard ----------------
 */
$router->get('/admin/dashboard', [DashboardController::class, 'index']);

/**
 * ---------------- Teams Management ----------------
 */
$router->get('/admin/teams', [TeamsController::class, 'index']);
$router->post('/admin/teams/add', [TeamsController::class, 'addEmployee']);
$router->post('/admin/teams/update', [TeamsController::class, 'updateEmployee']);
$router->post('/admin/teams/delete', [TeamsController::class, 'deleteEmployee']);

/**
 * ---------------- Cars Management ----------------
 */
$router->get('/admin/cars', [CarsController::class, 'index']);
$router->post('/admin/cars/add', [CarsController::class, 'addCar']);
$router->post('/admin/cars/update', [CarsController::class, 'updateCar']);
$router->post('/admin/cars/delete', [CarsController::class, 'deleteCar']);

/**
 * ---------------- Bookings Management ----------------
 */
$router->get('/admin/bookings', [BookingsController::class, 'index']);
$router->post('/admin/bookings/cancel', [BookingsController::class, 'cancelBooking']);
$router->post('/admin/bookings/sold', [BookingsController::class, 'markAsSold']);

/**
 * ---------------- Profile Management ----------------
 */
$router->get('/admin/profile', [ProfileController::class, 'index']);
$router->post('/admin/profile/update', [ProfileController::class, 'updateProfile']);
$router->post('/admin/profile/password', [ProfileController::class, 'changePassword']);

/**
 * ---------------- User Management ----------------
 */
$router->get('/admin/users', [UsersController::class, 'index']);
$router->post('/admin/users/update-status', [UsersController::class, 'updateStatus']);
$router->post('/admin/users/update-role', [UsersController::class, 'updateRole']);
$router->post('/admin/users/delete', [UsersController::class, 'deleteUser']);