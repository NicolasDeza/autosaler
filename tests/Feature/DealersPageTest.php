<?php

use Inertia\Testing\AssertableInertia as Assert;

test('dealers page can be rendered', function () {
    $response = $this->get(route('dealers.index'));

    $response
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page->component('Dealers/Index'));
});
