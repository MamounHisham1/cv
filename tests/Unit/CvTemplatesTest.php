<?php

use App\CvTemplates;

it('registers all 10 templates with correct slugs', function () {
    $slugs = array_keys(CvTemplates::all());

    expect($slugs)->toHaveCount(10)
        ->and($slugs)->toEqual([
            'professional-classic',
            'technical-ats',
            'modern-minimal',
            'creative',
            'executive',
            'bold',
            'timeline',
            'swiss',
            'warm',
            'compact',
        ])
        ->and(CvTemplates::all())->each->toHaveKeys(['name', 'description', 'icon', 'features']);
});

it('returns correct display names (no mangled casing)', function () {
    expect(CvTemplates::name('technical-ats'))->toBe('Technical ATS')
        ->and(CvTemplates::name('modern-minimal'))->toBe('Modern Minimal')
        ->and(CvTemplates::name('professional-classic'))->toBe('Professional Classic')
        ->and(CvTemplates::name('swiss'))->toBe('Swiss');
});

it('falls back to a title-cased slug for unknown templates', function () {
    expect(CvTemplates::name('does-not-exist'))->toBe('Does Not Exist');
});

it('options returns slug => name pairs for selects', function () {
    $options = CvTemplates::options();

    expect($options)->toHaveCount(10)
        ->and($options)->toHaveKey('creative', 'Creative')
        ->and($options)->not->toHaveKey('aws-engineer')
        ->and($options)->not->toHaveKey('technical');
});

it('flags photo and skill-level features only on the right templates', function () {
    expect(CvTemplates::supports('creative', CvTemplates::FEATURE_PHOTO))->toBeTrue()
        ->and(CvTemplates::supports('warm', CvTemplates::FEATURE_PHOTO))->toBeTrue()
        ->and(CvTemplates::supports('swiss', CvTemplates::FEATURE_PHOTO))->toBeFalse()
        ->and(CvTemplates::supports('bold', CvTemplates::FEATURE_PHOTO))->toBeFalse()
        ->and(CvTemplates::supports('creative', CvTemplates::FEATURE_SKILL_LEVELS))->toBeTrue()
        ->and(CvTemplates::supports('warm', CvTemplates::FEATURE_SKILL_LEVELS))->toBeTrue()
        ->and(CvTemplates::supports('swiss', CvTemplates::FEATURE_SKILL_LEVELS))->toBeTrue()
        ->and(CvTemplates::supports('professional-classic', CvTemplates::FEATURE_SKILL_LEVELS))->toBeFalse();
});

it('knows whether a slug exists', function () {
    expect(CvTemplates::exists('creative'))->toBeTrue()
        ->and(CvTemplates::exists('aws-engineer'))->toBeFalse();
});

it('returns a sane default template slug', function () {
    expect(CvTemplates::default())->toBe('professional-classic')
        ->and(CvTemplates::exists(CvTemplates::default()))->toBeTrue();
});
