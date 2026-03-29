<?php

test('home route renders the design 4 landing page', function () {
    $response = $this->get(route('home'));

    $response
        ->assertOk()
        ->assertViewIs('landing.design4')
        ->assertSee('Craft the CV that')
        ->assertSee('Get in Touch')
        ->assertSee('Different resume templates for every career path')
        ->assertSee('5 distinct resume templates')
        ->assertSee('Professional Classic')
        ->assertSee('Technical ATS')
        ->assertSee('bg-zinc-950/80', false)
        ->assertSee('id="templates"', false)
        ->assertSee('id="about"', false)
        ->assertSee('id="pricing"', false)
        ->assertSee('id="faq"', false)
        ->assertSee('id="contact"', false)
        ->assertSee('template-marquee-track', false)
        ->assertSee('flex h-full flex-col', false)
        ->assertSee('border-t border-white/10 px-6 pb-5 pt-4', false);
});

test('contact form fields use the dark glassmorphism styling', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('bg-zinc-900/50', false)
        ->assertSee('border-white/10', false)
        ->assertSee('text-zinc-100', false)
        ->assertSee('placeholder:text-zinc-500', false)
        ->assertSee('focus-visible:border-emerald-500/50', false)
        ->assertSee('focus-visible:ring-emerald-500/20', false);
});

test('contact form validation styling remains legible on dark backgrounds', function () {
    $this->withViewErrors([
        'name' => 'The name field is required.',
        'message' => 'The message field is required.',
    ])->view('livewire.contact-form', ['sent' => false])
        ->assertSee('border-red-400/70', false)
        ->assertSee('focus-visible:ring-red-500/25', false)
        ->assertSee('text-red-300', false);
});

test('standalone marketing pages are not available', function (string $uri) {
    $this->get($uri)->assertNotFound();
})->with([
    '/about',
    '/faq',
    '/contact',
]);
