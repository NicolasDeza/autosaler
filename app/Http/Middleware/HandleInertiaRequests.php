<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

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
            'locale' => fn() => app()->getLocale(),
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
