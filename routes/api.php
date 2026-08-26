<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiHomeController;
use App\Http\Controllers\Api\ApiProductController;
use App\Http\Controllers\Api\ApiCartController;
use App\Http\Controllers\Api\ApiCheckoutController;
use App\Http\Controllers\Api\ApiCustomerController;
use App\Http\Controllers\Api\ApiNotificationController;

/*
|--------------------------------------------------------------------------
| Flutter Mobile App REST API Routes
| Base URL: /api/
|--------------------------------------------------------------------------
*/

/* 1. Public Authentication Routes */
Route::prefix('auth')->group(function () {
    Route::post('/register', [ApiAuthController::class, 'register']);
    Route::post('/login', [ApiAuthController::class, 'login']);
});

/* 2. Public Storefront & Catalog Routes */
Route::get('/home', [ApiHomeController::class, 'index']);
Route::get('/products', [ApiProductController::class, 'index']);
Route::get('/products/{slug}', [ApiProductController::class, 'show']);
Route::post('/products/{id}/check-rental', [ApiProductController::class, 'checkRentalAvailability']);
Route::get('/categories', [ApiProductController::class, 'categories']);
Route::get('/brands', [ApiProductController::class, 'brands']);

/* 3. Public / Guest Cart Routes (X-Session-ID supported) */
Route::prefix('cart')->group(function () {
    Route::get('/', [ApiCartController::class, 'index']);
    Route::post('/add', [ApiCartController::class, 'add']);
    Route::post('/update/{id}', [ApiCartController::class, 'updateQuantity']);
    Route::delete('/remove/{id}', [ApiCartController::class, 'remove']);
    Route::post('/coupon', [ApiCartController::class, 'applyCoupon']);
});

/* 4. Protected Sanctum Routes (Requires Bearer Token) */
Route::middleware('auth:sanctum')->group(function () {
    
    /* Auth Profile & Logout */
    Route::get('/auth/me', [ApiAuthController::class, 'me']);
    Route::post('/auth/logout', [ApiAuthController::class, 'logout']);

    /* Checkout & Order Processing */
    Route::post('/checkout/process', [ApiCheckoutController::class, 'process']);

    /* Customer Portal API */
    Route::prefix('customer')->group(function () {
        Route::get('/dashboard', [ApiCustomerController::class, 'dashboard']);
        Route::get('/orders', [ApiCustomerController::class, 'orders']);
        Route::get('/orders/{orderNumber}', [ApiCustomerController::class, 'orderDetail']);
        
        Route::get('/rentals', [ApiCustomerController::class, 'rentals']);
        Route::post('/rentals/{orderId}/extend', [ApiCustomerController::class, 'extendRental']);
        Route::post('/rentals/{orderId}/return', [ApiCustomerController::class, 'requestReturn']);

        Route::get('/wishlist', [ApiCustomerController::class, 'wishlist']);
        Route::post('/wishlist/toggle/{productId}', [ApiCustomerController::class, 'toggleWishlist']);

        /* Real-Time Notifications */
        Route::get('/notifications', [ApiNotificationController::class, 'getNotifications']);
        Route::post('/notifications/read/{id}', [ApiNotificationController::class, 'markAsRead']);
        Route::post('/notifications/read-all', [ApiNotificationController::class, 'markAllAsRead']);
    });
});
