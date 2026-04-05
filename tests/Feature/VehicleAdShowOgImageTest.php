<?php

use App\Models\VehicleAd;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('renders a vehicle-specific og image on vehicle show page', function () {
    Storage::fake('public');

    $vehicleAd = VehicleAd::factory()->create();
    $vehicleAd->addMedia(UploadedFile::fake()->image('vehicle.jpg'))
        ->toMediaCollection('gallery');
    $vehicleAd->refresh()->load(['brand', 'model']);

    $mediaPath = parse_url($vehicleAd->getFirstMedia('gallery')?->getUrl() ?? '', PHP_URL_PATH);
    $expectedOgImage = rtrim(config('app.url', 'http://localhost'), '/').$mediaPath;

    $response = $this->get(route('vehicles.show', $vehicleAd));

    $response->assertOk();
    $response->assertSee('property="og:type" content="product"', false);
    $response->assertSee('property="og:image" content="'.$expectedOgImage.'"', false);

    $content = $response->getContent();
    $brand = preg_quote((string) $vehicleAd->brand?->name, '/');
    $model = preg_quote((string) $vehicleAd->model?->name, '/');

    expect($content)->toMatch('/<meta property="og:title" content="[^"]*'.$brand.'[^"]*'.$model.'[^"]*" inertia="og:title" \\/>/');
    expect($content)->toMatch('/<meta property="og:description" content="[^"]*'.$brand.'[^"]*'.$model.'[^"]*" inertia="og:description" \\/>/');
});
