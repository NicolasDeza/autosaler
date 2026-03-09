<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SubscriptionInquiryController;
use App\Http\Controllers\VehicleAdController;
use App\Http\Controllers\VehicleModelController;
use App\Http\Controllers\VehicleVersionController;
use App\Http\Controllers\DealerDashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::post('/subscription-inquiry', SubscriptionInquiryController::class)->name('subscription.inquiry');

// Pages légales
Route::prefix('legal')->name('legal.')->group(function () {
    Route::get('/notices', fn () => Inertia::render('legal/LegalNotice'))->name('notices');
    Route::get('/privacy', fn () => Inertia::render('legal/PrivacyPolicy'))->name('privacy');
    Route::get('/cookies', fn () => Inertia::render('legal/Cookies'))->name('cookies');
});
Route::get('/vehicle-models', [VehicleModelController::class, 'index'])->name('vehicle-models.index');
Route::get('/vehicle-versions', [VehicleVersionController::class, 'index'])->name('vehicle-versions.index');

Route::get('/vehicles', [VehicleAdController::class, 'index'])->name('vehicles.index');
Route::get('/vehicles/{vehicleAd}', [VehicleAdController::class, 'show'])->name('vehicles.show')->whereNumber('vehicleAd');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/vehicles/create', [VehicleAdController::class, 'create'])->name('vehicles.create');
    Route::post('/vehicles', [VehicleAdController::class, 'store'])->name('vehicles.store');
    Route::get('/vehicles/{vehicleAd}/edit', [VehicleAdController::class, 'edit'])->name('vehicles.edit');
    Route::put('/vehicles/{vehicleAd}', [VehicleAdController::class, 'update'])->name('vehicles.update');
    Route::patch('/vehicles/{vehicleAd}/status', [VehicleAdController::class, 'updateStatus'])->name('vehicles.update_status');
    Route::delete('/vehicles/{vehicleAd}', [VehicleAdController::class, 'destroy'])->name('vehicles.destroy');

    Route::middleware('permission:view_admin_dashboard')->group(function () {
        Route::get('/admin/dashboard', function () {
            return Inertia::render('AdminDashboard');
        })->name('admin_dashboard');
    });

    Route::middleware('permission:view_dealer_dashboard')->prefix('dealer')->name('dealer_dashboard.')->group(function () {
        Route::get('/dashboard', [DealerDashboardController::class, 'index'])
            ->name('index');
    });

    // Route::middleware('role:admin')->group(function () {
    //     Route::get('/admindashboard', function () {
    //         return Inertia::render('AdminDashboard');
    //     })->name('admin_dashboard');
    // });
});

Route::get('/translations/{locale}', function ($locale) {
    app()->setLocale($locale);

    return response()->json(
        cache()->remember("translations_$locale", 3600, function () use ($locale) {
            $translations = [];

            $jsonPath = lang_path("$locale.json");
            if (file_exists($jsonPath)) {
                $translations = json_decode(file_get_contents($jsonPath), true);
            }

            foreach (glob(lang_path("$locale/*.php")) as $file) {
                $filename = basename($file, '.php');
                $fileTranslations = require $file;

                foreach ($fileTranslations as $key => $value) {
                    $translations["$filename.$key"] = $value;
                }
            }

            return $translations;
        })
    );
});

Route::post('/locale', function (\Illuminate\Http\Request $request) {
    $locale = $request->input('locale');

    abort_unless(in_array($locale, config('app.available_locales', ['en', 'fr'])), 400);

    // Stocker la langue dans la session
    session(['locale' => $locale]);
    app()->setLocale($locale);

    return response()->json([
        'success' => true,
        'locale' => $locale,
    ]);
});

require __DIR__.'/settings.php';
