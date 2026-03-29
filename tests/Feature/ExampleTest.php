<?php

test('home route redirects to cv-builder', function () {
    $response = $this->get(route('home'));

    $response->assertRedirect('/cv-builder');
});
