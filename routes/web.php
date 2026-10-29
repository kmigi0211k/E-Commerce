<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\VerificationCodeController;
use App\Http\Controllers\Auth\FirebaseAuthController;

Route::get('/', [ProductController::class, 'index'])->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/comunidad', [ProductController::class, 'comunidad'])->name('products.comunidad');

Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');

Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');

Route::get('/verify-code', [VerificationCodeController::class, 'notice'])->name('verification.code.notice');
Route::post('/verify-code', [VerificationCodeController::class, 'verify'])->middleware('throttle:6,1')->name('verification.code.verify');
Route::post('/verify-code/resend', [VerificationCodeController::class, 'resend'])->middleware('throttle:3,1')->name('verification.code.resend');

Route::get('/verify-email', EmailVerificationPromptController::class)->middleware('auth')->name('verification.notice');
Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)->middleware(['auth', 'signed', 'throttle:6,1'])->name('verification.verify');
Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])->middleware('auth')->name('password.confirm');
Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])->middleware('auth');
Route::put('/password', [PasswordController::class, 'update'])->middleware('auth')->name('password.update');

Route::post('/auth/firebase/register', [FirebaseAuthController::class, 'handleFirebaseRegister'])->name('auth.firebase.register');
Route::post('/auth/firebase/login', [FirebaseAuthController::class, 'handleFirebaseLogin'])->name('auth.firebase.login');

Route::middleware('auth')->group(function () {
    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', [ProductController::class, 'dashboard'])->name('dashboard');

        Route::get('/products-admin', [ProductController::class, 'dashboard'])->name('products.dashboard');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::patch('/products/{product}/toggle-active', [ProductController::class, 'toggleActive'])->name('products.toggleActive');

        Route::get('/admin/orders', [OrderController::class, 'adminOrders'])->name('orders.admin');
        Route::patch('/admin/orders/{order}/confirm-payment', [OrderController::class, 'confirmPayment'])->name('orders.confirmPayment');
        Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');

        Route::get('/admin/users', [UserController::class, 'adminIndex'])->name('admin.users');
        Route::get('/admin/volunteers', [VolunteerController::class, 'admin'])->name('volunteers.admin');
        Route::patch('/admin/volunteers/{id}', [VolunteerController::class, 'updateStatus'])->name('volunteers.updateStatus');
        Route::delete('/admin/volunteers/{id}', [VolunteerController::class, 'destroy'])->name('volunteers.destroy');
    });

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
    Route::post('/orders/process', [OrderController::class, 'process'])->name('orders.process');
    Route::get('/orders/success/{id}', [OrderController::class, 'success'])->name('orders.success');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/info', [ProfileController::class, 'info'])->name('profile.info');
    Route::patch('/profile/info', [ProfileController::class, 'updateInfo'])->name('profile.info.update');

    Route::get('/volunteers', [VolunteerController::class, 'myApplications'])->name('volunteers.index');
    Route::post('/products/{product}/volunteer', [VolunteerController::class, 'store'])->name('volunteers.store');
});

Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
