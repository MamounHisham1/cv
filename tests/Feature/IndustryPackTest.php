<?php

use App\Cv\IndustryPacks\CloudPack;
use App\Cv\IndustryPacks\GenericPack;
use App\Cv\IndustryPacks\IndustryPacks;
use App\Models\Cv;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('industry packs', function () {
    it('resolves the generic pack as the default', function () {
        expect(IndustryPacks::default())->toBeInstanceOf(GenericPack::class)
            ->and(IndustryPacks::get(null))->toBeInstanceOf(GenericPack::class)
            ->and(IndustryPacks::get('unknown-pack'))->toBeInstanceOf(GenericPack::class);
    });

    it('resolves the cloud pack by slug', function () {
        expect(IndustryPacks::get('cloud'))->toBeInstanceOf(CloudPack::class)
            ->and(IndustryPacks::exists('cloud'))->toBeTrue()
            ->and(IndustryPacks::exists('generic'))->toBeTrue()
            ->and(IndustryPacks::exists('nope'))->toBeFalse();
    });

    it('exposes both packs via options()', function () {
        $options = IndustryPacks::options();

        expect($options)->toHaveKey('generic')
            ->and($options)->toHaveKey('cloud')
            ->and($options['generic'])->toBe('General')
            ->and($options['cloud'])->toBe('Cloud & DevOps');
    });

    it('gives the generic pack a neutral category list without cloud-flavored labels', function () {
        $categories = (new GenericPack)->skillCategories();

        expect($categories)->toHaveKey('general')
            ->and($categories)->not->toHaveKey('cloud')
            ->and(implode(' ', $categories))->not->toContain('AWS');
    });

    it('gives the cloud pack cloud-flavored categories and suggestions', function () {
        $pack = new CloudPack;

        expect($pack->skillCategories())->toHaveKey('cloud')
            ->and($pack->skillSuggestions())->toContain('Lambda')
            ->and($pack->certificationSuggestions())->toContain('AWS Certified Solutions Architect - Associate')
            ->and($pack->promptContext())->not->toBeEmpty();
    });

    it('resolves a CV without a pack to the generic pack', function () {
        $cv = Cv::factory()->create(['industry_pack' => null]);

        expect($cv->industryPack())->toBeInstanceOf(GenericPack::class);
    });

    it('resolves a CV with the cloud pack to the cloud pack', function () {
        $cv = Cv::factory()->create(['industry_pack' => 'cloud']);

        expect($cv->industryPack())->toBeInstanceOf(CloudPack::class);
    });
});
