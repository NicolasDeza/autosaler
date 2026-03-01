<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Index', [
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

require __DIR__ . '/settings.php';
