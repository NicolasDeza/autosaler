<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Laravel\Fortify\Features;
use Symfony\Component\HttpFoundation\Response;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = parent::handle($request, $next);

        // Keep caches/proxies from mixing Inertia JSON and normal HTML responses.
        $existingVary = array_filter(array_map('trim', explode(',', (string) $response->headers->get('Vary'))));
        $varyValues = array_unique([...$existingVary, 'X-Inertia', 'X-Requested-With']);
        $response->headers->set('Vary', implode(', ', $varyValues));

        // Prevent edge caches from storing JSON payloads requested by Inertia visits.
        if ($request->headers->has('X-Inertia')) {
            $response->headers->set('Cache-Control', 'no-store, private');
        }

        return $response;
    }

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user()?->load('roles.permissions');

        // dd($request->user());
        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'auth' => [
                'user' => $user
                    ? array_merge(
                        $user->toArray(),
                        [
                            'roles' => $user->getRoleNames(),
                            'permissions' => $user->getAllPermissions()->pluck('name'),
                        ]
                    )
                    : null,
            ],
            'canRegister' => Features::enabled(Features::registration()),
            'locale' => fn () => session('locale', app()->getLocale()),
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'processing_images' => fn () => $request->session()->get('processing_images'),
                'created' => fn () => $request->session()->get('created'),
                'dealerRegistrationSuccess' => fn () => $request->session()->get('dealerRegistrationSuccess'),
            ],
            // 'translations' => fn() => $this->getTranslations(),
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
        ];
    }

    protected function getTranslations()
    {
        $locale = app()->getLocale();

        return cache()->remember("translations_$locale", 3600, function () use ($locale) {
            $translations = [];

            $jsonPath = lang_path("$locale.json");
            if (file_exists($jsonPath)) {
                $translations = json_decode(file_get_contents($jsonPath), true);
            }

            $langPath = lang_path($locale);
            if (is_dir($langPath)) {
                foreach (glob("$langPath/*.php") as $file) {
                    $filename = basename($file, '.php');
                    $fileTranslations = require $file;

                    foreach ($fileTranslations as $key => $value) {
                        $translations["$filename.$key"] = $value;
                    }
                }
            }

            return $translations;
        });
    }
}
