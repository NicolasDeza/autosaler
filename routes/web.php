<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\VehicleBrandController as AdminVehicleBrandController;
use App\Http\Controllers\Admin\VehicleModelController as AdminVehicleModelController;
use App\Http\Controllers\CitySearchController;
use App\Http\Controllers\ContactAdminController;
use App\Http\Controllers\DealerDashboardController;
use App\Http\Controllers\DealerRegistrationController;
use App\Http\Controllers\DealersPageController;
use App\Http\Controllers\GarageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VehicleAdContactController;
use App\Http\Controllers\VehicleAdController;
use App\Http\Controllers\VehicleModelController;
use App\Http\Controllers\VehicleVersionController;
use Illuminate\Http\Request;
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
Route::get('/vehicles/compare', [VehicleAdController::class, 'compare'])->name('vehicles.compare');
Route::get('/vehicles/{vehicleAd}', [VehicleAdController::class, 'show'])->name('vehicles.show')->whereNumber('vehicleAd');
Route::post('/vehicles/{vehicleAd}/contact', VehicleAdContactController::class)->name('vehicles.contact')->whereNumber('vehicleAd');
Route::get('/dealers', [DealersPageController::class, 'index'])->name('dealers.index');
Route::get('/garages', [GarageController::class, 'index'])->name('garages.index');
Route::post('/dealers/register', DealerRegistrationController::class)->name('dealers.register');
Route::post('/contact', ContactAdminController::class)->name('contact.send');

Route::middleware(['auth', 'verified', 'role:admin|dealer'])->group(function () {
    Route::post('/vehicles/{vehicleAd}/favorite', [VehicleAdController::class, 'toggleFavorite'])->name('vehicles.favorite');

    Route::prefix('vehicles')->name('vehicles.')->group(function () {
        Route::get('/create', [VehicleAdController::class, 'create'])->name('create');
        Route::post('/', [VehicleAdController::class, 'store'])->name('store');
        Route::get('/{vehicleAd}/edit', [VehicleAdController::class, 'edit'])->name('edit');
        Route::get('/{vehicleAd}/images-status', [VehicleAdController::class, 'imagesStatus'])->name('images_status');
        Route::post('/media/{media}/process', [VehicleAdController::class, 'processMedia'])->name('process_media');
        Route::put('/{vehicleAd}', [VehicleAdController::class, 'update'])->name('update');
        Route::patch('/{vehicleAd}/status', [VehicleAdController::class, 'updateStatus'])->name('update_status');
        Route::delete('/{vehicleAd}', [VehicleAdController::class, 'destroy'])->name('destroy');
    });

    Route::middleware('permission:view_dealer_dashboard')->prefix('dealer')->name('dealer.')->group(function () {
        Route::get('/dashboard', [DealerDashboardController::class, 'index'])->name('dashboard');
    });

    Route::middleware('permission:view_admin_dashboard')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::apiResource('vehicle-brands', AdminVehicleBrandController::class)->except(['index', 'show']);
        Route::apiResource('vehicle-models', AdminVehicleModelController::class)->except(['index', 'show']);

        Route::patch('/users/{user}/subscription', [AdminDashboardController::class, 'updateSubscription'])->name('users.update_subscription');
        Route::delete('/users/{user}/subscription', [AdminDashboardController::class, 'cancelSubscription'])->name('users.cancel_subscription');
        Route::patch('/users/{user}/status', [AdminDashboardController::class, 'updateStatus'])->name('users.update_status');
        Route::delete('/users/{user}', [AdminDashboardController::class, 'destroyUser'])->name('users.destroy');
    });
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

            $flatten = function (array $array, string $prefix = '') use (&$flatten) {
                $result = [];
                foreach ($array as $key => $value) {
                    $newKey = $prefix ? "$prefix.$key" : $key;
                    if (is_array($value)) {
                        $result = array_merge($result, $flatten($value, $newKey));
                    } else {
                        $result[$newKey] = $value;
                    }
                }

                return $result;
            };

            foreach (glob(lang_path("$locale/*.php")) as $file) {
                $filename = basename($file, '.php');
                $fileTranslations = require $file;
                $translations = array_merge($translations, $flatten($fileTranslations, $filename));
            }

            return $translations;
        })
    );
});

Route::post('/locale', function (Request $request) {
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
