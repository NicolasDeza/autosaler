<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Route::get('/seller/dashboard', function () {
    //     return Inertia::render('SellerDashboard');
    // })->name('seller.dashboard');

    Route::middleware('permission:view_admin_dashboard')->group(function () {
        Route::get('/admindashboard', function () {
            return Inertia::render('AdminDashboard');
        })->name('admin_dashboard');
    });

    // Route::middleware('role:admin')->group(function () {
    //     Route::get('/admindashboard', function () {
    //         return Inertia::render('AdminDashboard');
    //     })->name('admin_dashboard');
    // });
});

require __DIR__ . '/settings.php';
