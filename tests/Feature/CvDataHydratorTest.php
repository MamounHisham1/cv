<?php

use App\Models\Cv;
use App\Services\CvDataHydrator;

describe('CvDataHydrator::normalize', function () {
    it('returns already-flat data unchanged', function () {
        $flat = [
            'first_name' => 'Jane',
            'experiences' => [['company' => 'Acme']],
        ];

        expect((new CvDataHydrator)->normalize($flat))->toBe($flat);
    });

    it('splits a nested personal_information name into first/last', function () {
        $normalized = (new CvDataHydrator)->normalize([
            'personal_information' => [
                'name' => 'Jane Doe',
                'email' => 'jane@example.com',
            ],
        ]);

        expect($normalized['first_name'])->toBe('Jane')
            ->and($normalized['last_name'])->toBe('Doe')
            ->and($normalized['email'])->toBe('jane@example.com');
    });

    it('maps summary aliases and resolves experience/skill variants', function () {
        $normalized = (new CvDataHydrator)->normalize([
            'personal_information' => ['name' => 'Solo'],
            'profile' => 'A short bio.',
            'work_experience' => [['company' => 'Globex', 'role' => 'Engineer']],
            'skills' => ['Python', 'Go'],
        ]);

        expect($normalized['summary'])->toBe('A short bio.')
            ->and($normalized['experiences'][0])->toMatchArray([
                'company' => 'Globex',
                'title' => 'Engineer',
            ])
            ->and($normalized['skills'])->toHaveCount(2)
            ->and($normalized['skills'][0])->toMatchArray([
                'name' => 'Python',
                'category' => 'general',
                'level' => 'intermediate',
            ]);
    });
});

describe('CvDataHydrator::import', function () {
    it('persists all six sections with sequential sort_order', function () {
        $cv = Cv::factory()->create();

        (new CvDataHydrator)->import($cv, [
            'experiences' => [
                ['company' => 'Acme', 'title' => 'Dev', 'start_date' => '2022-01-01', 'end_date' => '2023-01-01'],
                ['company' => 'Globex', 'title' => 'Senior Dev', 'start_date' => '2023-02-01', 'is_current' => true],
            ],
            'skills' => [['name' => 'PHP']],
            'educations' => [['institution' => 'MIT', 'degree' => 'BSc', 'start_date' => '2018-01-01', 'end_date' => '2022-01-01']],
            'certifications' => [['name' => 'AWS Certified']],
            'projects' => [['name' => 'Open Source Lib']],
            'languages' => [['language' => 'English', 'proficiency' => 'fluent']],
        ]);

        expect($cv->fresh()->experiences)->toHaveCount(2)
            ->and($cv->fresh()->experiences->first()->sort_order)->toBe(0)
            ->and($cv->fresh()->skills)->toHaveCount(1)
            ->and($cv->fresh()->educations)->toHaveCount(1)
            ->and($cv->fresh()->certifications)->toHaveCount(1)
            ->and($cv->fresh()->projects)->toHaveCount(1)
            ->and($cv->fresh()->languages)->toHaveCount(1);
    });

    it('skips sections that are empty or malformed without error', function () {
        $cv = Cv::factory()->create();

        (new CvDataHydrator)->import($cv, [
            'experiences' => [],
            'skills' => 'not-json',
            'educations' => [],
        ]);

        expect($cv->fresh()->experiences)->toBeEmpty()
            ->and($cv->fresh()->skills)->toBeEmpty()
            ->and($cv->fresh()->projects)->toBeEmpty();
    });
});
