<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CmsController;

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminFleetController;
use App\Http\Controllers\Admin\AdminMaintenanceController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\Admin\AdminCmsController;
use App\Http\Controllers\Admin\AdminPromotionController;
use App\Http\Controllers\Admin\AdminReviewController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/newsletter/subscribe', [HomeController::class, 'subscribeNewsletter'])->name('newsletter.subscribe');

Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::post('/product/{id}/check-rental', [ProductController::class, 'checkRentalAvailability'])->name('products.check_rental');
Route::post('/product/{id}/review', [ProductController::class, 'submitReview'])->name('products.review');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/cart/mini', [CartController::class, 'getMiniCart'])->name('cart.mini');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'updateQuantity'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/confirmation/{orderNumber}', [CheckoutController::class, 'confirmation'])->name('checkout.confirmation');

Route::get('/faqs', [CmsController::class, 'faqs'])->name('cms.faqs');
Route::get('/contact', [CmsController::class, 'contact'])->name('cms.contact');
Route::post('/contact', [CmsController::class, 'submitContact'])->name('cms.contact.submit');
Route::get('/page/{slug}', [CmsController::class, 'showPage'])->name('cms.page');

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Customer Portal Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('customer')->as('customer.')->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders', [CustomerDashboardController::class, 'orders'])->name('orders');
    Route::get('/orders/{orderNumber}', [CustomerDashboardController::class, 'orderDetail'])->name('order_detail');
    Route::get('/rentals', [CustomerDashboardController::class, 'rentals'])->name('rentals');
    Route::post('/rentals/{orderId}/extend', [CustomerDashboardController::class, 'extendRental'])->name('rentals.extend');
    Route::post('/rentals/{orderId}/return', [CustomerDashboardController::class, 'requestReturn'])->name('rentals.return');
    Route::get('/wishlist', [CustomerDashboardController::class, 'wishlist'])->name('wishlist');
    Route::post('/wishlist/toggle/{productId}', [CustomerDashboardController::class, 'toggleWishlist'])->name('wishlist.toggle');
    Route::get('/profile', [CustomerDashboardController::class, 'profile'])->name('profile');
    Route::post('/profile', [CustomerDashboardController::class, 'updateProfile'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| Admin Suite Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->as('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/analytics', [AdminDashboardController::class, 'analyticsData'])->name('analytics');

    Route::resource('products', AdminProductController::class);
    
    Route::get('/fleet', [AdminFleetController::class, 'index'])->name('fleet.index');
    Route::post('/fleet', [AdminFleetController::class, 'store'])->name('fleet.store');
    Route::post('/fleet/{id}/status', [AdminFleetController::class, 'updateStatus'])->name('fleet.status');

    Route::get('/maintenance', [AdminMaintenanceController::class, 'index'])->name('maintenance.index');
    Route::post('/maintenance', [AdminMaintenanceController::class, 'store'])->name('maintenance.store');
    Route::put('/maintenance/{id}', [AdminMaintenanceController::class, 'update'])->name('maintenance.update');

    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::post('/orders/item/{orderItemId}/assign-unit', [AdminOrderController::class, 'assignUnit'])->name('orders.assign_unit');

    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [AdminSettingsController::class, 'update'])->name('settings.update');

    Route::get('/cms/banners', [AdminCmsController::class, 'banners'])->name('cms.banners');
    Route::post('/cms/banners', [AdminCmsController::class, 'storeBanner'])->name('cms.banners.store');
    Route::get('/cms/pages', [AdminCmsController::class, 'pages'])->name('cms.pages');
    Route::get('/cms/pages/{id}/edit', [AdminCmsController::class, 'editPage'])->name('cms.pages.edit');
    Route::put('/cms/pages/{id}', [AdminCmsController::class, 'updatePage'])->name('cms.pages.update');
    Route::get('/cms/faqs', [AdminCmsController::class, 'faqs'])->name('cms.faqs');
    Route::post('/cms/faqs', [AdminCmsController::class, 'storeFaq'])->name('cms.faqs.store');

    Route::get('/promotions', [AdminPromotionController::class, 'index'])->name('promotions.index');
    Route::post('/promotions', [AdminPromotionController::class, 'store'])->name('promotions.store');
    Route::post('/promotions/{id}/toggle', [AdminPromotionController::class, 'toggle'])->name('promotions.toggle');

    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/{id}/status', [AdminReviewController::class, 'updateStatus'])->name('reviews.status');
    Route::delete('/reviews/{id}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
});
