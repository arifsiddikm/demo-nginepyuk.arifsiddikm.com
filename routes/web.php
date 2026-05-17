<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin;

// --- PUBLIC ---
Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Explore
Route::get('/jelajahi',        [ExploreController::class, 'index'])->name('explore.index');
Route::get('/jelajahi/{slug}', [ExploreController::class, 'show'])->name('explore.show');

// Booking (checkout page & store – auth not required for guest checkout)
Route::get('/booking/{slug}/checkout', [BookingController::class, 'checkout'])->name('booking.checkout');
Route::post('/booking/store',          [BookingController::class, 'store'])->name('booking.store');
Route::get('/booking/{code}',          [BookingController::class, 'show'])->name('booking.show');
Route::post('/booking/{code}/transfer',[BookingController::class, 'uploadTransfer'])->name('booking.transfer');
Route::get('/booking/{code}/tiket',    [BookingController::class, 'downloadTicket'])->name('booking.ticket');

// Payment
// Static routes HARUS di atas wildcard {code}
Route::get('/payment/finish',          [PaymentController::class, 'midtransFinish'])->name('payment.finish');
Route::post('/payment/snap-token',     [PaymentController::class, 'getSnapToken'])->name('payment.snaptoken');
Route::post('/payment/midtrans/notification', [PaymentController::class, 'midtransNotification'])->name('payment.notification')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
// Wildcard {code} di bawah
Route::get('/payment/{code}',          [PaymentController::class, 'show'])->name('payment.show');

// --- USER DASHBOARD ---
Route::middleware('auth')->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/',              [DashboardController::class, 'index'])->name('index');
    Route::get('/pesanan',       [DashboardController::class, 'bookings'])->name('bookings');
    Route::get('/profil',        [DashboardController::class, 'profile'])->name('profile');
    Route::post('/profil',       [DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::post('/password',     [DashboardController::class, 'updatePassword'])->name('password.update');
    Route::post('/testimoni/{code}', [DashboardController::class, 'submitTestimonial'])->name('testimonial.store');
});

// --- ADMIN PANEL ---
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Properties
    Route::resource('properties', Admin\PropertyController::class);

    // Bookings
    Route::get('/bookings',                     [Admin\BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}',           [Admin\BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/confirm',  [Admin\BookingController::class, 'confirm'])->name('bookings.confirm');
    Route::post('/bookings/{booking}/complete', [Admin\BookingController::class, 'complete'])->name('bookings.complete');
    Route::post('/bookings/{booking}/cancel',   [Admin\BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('/bookings/{booking}/status',      [Admin\BookingController::class, 'updateStatus'])->name('bookings.status');
    Route::post('/bookings/{booking}/upload-proof',[Admin\BookingController::class, 'uploadProof'])->name('bookings.upload-proof');
    Route::get('/bookings/export/pdf',          [Admin\BookingController::class, 'exportPdf'])->name('bookings.export.pdf');
    Route::get('/bookings/export/excel',        [Admin\BookingController::class, 'exportExcel'])->name('bookings.export.excel');

    // Users
    Route::resource('users', Admin\UserController::class);

    // Testimonials
    Route::get('/testimonials',                      [Admin\TestimonialController::class, 'index'])->name('testimonials.index');
    Route::post('/testimonials/{testimonial}/approve',[Admin\TestimonialController::class, 'approve'])->name('testimonials.approve');
    Route::post('/testimonials/{testimonial}/reject', [Admin\TestimonialController::class, 'reject'])->name('testimonials.reject');
    Route::delete('/testimonials/{testimonial}',      [Admin\TestimonialController::class, 'destroy'])->name('testimonials.destroy');
});
