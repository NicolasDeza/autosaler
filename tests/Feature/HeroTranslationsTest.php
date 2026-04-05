<?php

it('has the hero professional cta translated in each supported locale', function (string $locale, string $expectedTranslation) {
    app()->setLocale($locale);

    expect(__('hero.cta_pro_link'))->toBe($expectedTranslation);
})->with([
    'english' => ['en', 'Become a professional'],
    'french' => ['fr', 'Devenir professionnel'],
    'dutch' => ['nl', 'Professioneel worden'],
]);
