<?php

use App\Http\Controllers\CitySearchController;
use App\Http\Controllers\DealerDashboardController;
use App\Http\Controllers\DealerRegistrationController;
use App\Http\Controllers\DealersPageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VehicleAdContactController;
use App\Http\Controllers\VehicleAdController;
use App\Http\Controllers\VehicleModelController;
use App\Http\Controllers\VehicleVersionController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Pages légales
Route::prefix('legal')->name('legal.')->group(function () {
    Route::get('/notices', fn () => Inertia::render('legal/LegalNotice'))->name('notices');
    Route::get('/privacy', fn () => Inertia::render('legal/PrivacyPolicy'))->name('privacy');
    Route::get('/cookies', fn () => Inertia::render('legal/Cookies'))->name('cookies');
});
Route::get('/vehicle-models', [VehicleModelController::class, 'index'])->name('vehicle-models.index');
Route::get('/vehicle-versions', [VehicleVersionController::class, 'index'])->name('vehicle-versions.index');
Route::get('/cities/search', CitySearchController::class)->name('cities.search');

Route::get('/vehicles', [VehicleAdController::class, 'index'])->name('vehicles.index');
Route::get('/vehicles/{vehicleAd}', [VehicleAdController::class, 'show'])->name('vehicles.show')->whereNumber('vehicleAd');
Route::post('/vehicles/{vehicleAd}/contact', VehicleAdContactController::class)->name('vehicles.contact')->whereNumber('vehicleAd');
Route::get('/dealers', [DealersPageController::class, 'index'])->name('dealers.index');
Route::post('/dealers/register', DealerRegistrationController::class)->name('dealers.register');

Route::middleware(['auth', 'verified', 'role:admin|dealer'])->group(function () {
    Route::post('/vehicles/{vehicleAd}/favorite', [VehicleAdController::class, 'toggleFavorite'])->name('vehicles.favorite');

    Route::prefix('vehicles')->name('vehicles.')->group(function () {
        Route::get('/create', [VehicleAdController::class, 'create'])->name('create');
        Route::post('/', [VehicleAdController::class, 'store'])->name('store');
        Route::get('/{vehicleAd}/edit', [VehicleAdController::class, 'edit'])->name('edit');
        Route::put('/{vehicleAd}', [VehicleAdController::class, 'update'])->name('update');
        Route::patch('/{vehicleAd}/status', [VehicleAdController::class, 'updateStatus'])->name('update_status');
        Route::delete('/{vehicleAd}', [VehicleAdController::class, 'destroy'])->name('destroy');
    });

    Route::middleware('permission:view_admin_dashboard')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return Inertia::render('AdminDashboard');
        })->name('dashboard');
    });

    Route::middleware('permission:view_dealer_dashboard')->prefix('dealer')->name('dealer.')->group(function () {
        Route::get('/dashboard', [DealerDashboardController::class, 'index'])->name('dashboard');
    });

    // Route::middleware('role:admin')->group(function () {
    //     Route::get('/admindashboard', function () {
    //         return Inertia::render('AdminDashboard');
    //     })->name('admin_dashboard');
    // });
});

Route::get('/translations/{locale}', function ($locale) {
    app()->setLocale($locale);

    $jsonPath = lang_path("$locale.json");
    $phpFiles = glob(lang_path("$locale/*.php")) ?: [];

    $versionSeed = '';

    if (file_exists($jsonPath)) {
        $versionSeed .= $jsonPath.'|'.filemtime($jsonPath).'|';
    }

    foreach ($phpFiles as $file) {
        $versionSeed .= $file.'|'.filemtime($file).'|';
    }

    $cacheKey = 'translations_'.$locale.'_'.md5($versionSeed);

    return response()->json(
        cache()->remember($cacheKey, 3600, function () use ($locale, $jsonPath, $phpFiles) {
            $translations = [];

            if (file_exists($jsonPath)) {
                $translations = json_decode(file_get_contents($jsonPath), true);
            }

            foreach ($phpFiles as $file) {
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

Route::fallback(function () {
    return Inertia::render('Errors/404', ['status' => 404]);
});
