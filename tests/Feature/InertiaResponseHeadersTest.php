<?php

use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Http\Request;

it('adds vary headers on web responses to separate inertia and normal requests', function () {
    $response = $this->get(route('home'));

    $response->assertOk();

    $vary = (string) $response->headers->get('Vary');

    expect($vary)->toContain('X-Inertia');
    expect($vary)->toContain('X-Requested-With');
});

it('prevents caching of inertia payload responses', function () {
    $version = app(HandleInertiaRequests::class)->version(Request::create(route('home')));

    $response = $this->withHeaders([
        'X-Inertia' => 'true',
        'X-Requested-With' => 'XMLHttpRequest',
        'X-Inertia-Version' => $version ?? '',
    ])->get(route('home'));

    $response->assertOk();

    $cacheControl = (string) $response->headers->get('Cache-Control');

    expect($cacheControl)->toContain('no-store');
});
