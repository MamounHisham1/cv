<?php

use App\Ai\Agents\CoverLetterAgent;
use App\Ai\Agents\CvBuilderAgent;
use App\Ai\Agents\CvEvaluatorAgent;
use App\Ai\Agents\InterviewEvaluatorAgent;
use App\Ai\Agents\JobMatchAgent;
use App\Http\Middleware\SetAppLocale;
use App\Jobs\ProcessCoverLetter;
use App\Jobs\ProcessCvEvaluation;
use App\Models\CoverLetter;
use App\Models\Cv;
use App\Models\User;
use App\Support\AiLocaleDirective;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('AiLocaleDirective', function () {
    it('returns an Arabic directive for the ar locale', function () {
        $directive = AiLocaleDirective::for('ar');

        expect($directive)
            ->toContain('Arabic')
            ->toContain('العربية')
            ->and(mb_strpos($directive, 'JSON') !== false ?: 'should mention JSON keys as an English-only exception')->toBeTrue();
    });

    it('returns the mirror-language directive for en', function () {
        $directive = AiLocaleDirective::for('en');

        expect($directive)
            ->toContain('same language')
            ->not->toContain('العربية');
    });

    it('falls back to mirror for unknown locales', function () {
        expect(AiLocaleDirective::for('fr'))
            ->toContain('same language');
    });

    it('reads app()->getLocale() when no argument is passed', function () {
        app()->setLocale('ar');

        expect(AiLocaleDirective::for())->toContain('العربية');
    });

    it('reads app()->getLocale() for english too', function () {
        app()->setLocale('en');

        expect(AiLocaleDirective::for())->toContain('same language');
    });

    it('declares every supported locale key', function () {
        expect(AiLocaleDirective::supportedLocales())
            ->toBe(array_keys(SetAppLocale::SUPPORTED_LOCALES));
    });
});

describe('agent prompts are locale-aware', function () {
    it('appends Arabic directive to CvBuilderAgent when locale is ar', function () {
        app()->setLocale('ar');

        $agent = new CvBuilderAgent;
        $instructions = $agent->instructions();

        expect($instructions)->toContain('العربية');
    });

    it('appends mirror directive to CvBuilderAgent when locale is en', function () {
        app()->setLocale('en');

        $agent = new CvBuilderAgent;
        $instructions = $agent->instructions();

        expect($instructions)
            ->toContain('same language')
            ->not->toContain('العربية');
    });

    it('appends Arabic directive to CoverLetterAgent when locale is ar', function () {
        app()->setLocale('ar');

        $agent = new CoverLetterAgent;
        $instructions = $agent->instructions();

        expect($instructions)->toContain('العربية');
    });

    it('appends Arabic directive to CvEvaluatorAgent when locale is ar', function () {
        app()->setLocale('ar');

        $agent = new CvEvaluatorAgent;
        $instructions = $agent->instructions();

        expect($instructions)->toContain('العربية');
    });

    it('appends Arabic directive to JobMatchAgent when locale is ar', function () {
        app()->setLocale('ar');

        $agent = new JobMatchAgent;
        $instructions = $agent->instructions();

        expect($instructions)->toContain('العربية');
    });

    it('appends Arabic directive to InterviewEvaluatorAgent when locale is ar', function () {
        app()->setLocale('ar');

        $cv = Cv::factory()->create();
        $agent = new InterviewEvaluatorAgent($cv, [['role' => 'interviewer', 'content' => 'Hello']]);
        $instructions = $agent->instructions();

        expect($instructions)->toContain('العربية');
    });

    it('keeps core agent instructions intact regardless of locale', function () {
        app()->setLocale('ar');

        $agent = new CvBuilderAgent;
        $instructions = $agent->instructions();

        // The truthfulness contract and tool-usage rules must survive.
        expect($instructions)
            ->toContain('TRUTHFULNESS')
            ->toContain('ask_clarifying_questions');
    });
});

describe('queue jobs resolve locale from the user model', function () {
    it('ProcessCvEvaluation sets app locale from the owning user', function () {
        $user = User::factory()->create(['locale' => 'ar']);

        $job = new ProcessCvEvaluation(
            userId: $user->id,
            cvText: 'test',
            filename: 'test.pdf',
            inputMode: 'text',
        );

        app()->setLocale('en'); // simulate fresh queue context

        // handle() sets the locale first, then will throw when it can't
        // reach the AI. We only care that the locale was set before that.
        try {
            $job->handle();
        } catch (Throwable) {
            // expected — no live Ollama in CI
        }

        expect(app()->getLocale())->toBe('ar');
    });

    it('ProcessCvEvaluation defaults to en when user has no locale', function () {
        $user = User::factory()->create(['locale' => null]);

        $job = new ProcessCvEvaluation(
            userId: $user->id,
            cvText: 'test',
            filename: 'test.pdf',
            inputMode: 'text',
        );

        app()->setLocale('ar'); // start from a different locale

        try {
            $job->handle();
        } catch (Throwable) {
            // expected
        }

        expect(app()->getLocale())->toBe('en');
    });

    it('ProcessCoverLetter sets app locale from the cover letter owner', function () {
        $user = User::factory()->create(['locale' => 'ar']);
        $cv = Cv::factory()->create(['user_id' => $user->id]);
        $letter = CoverLetter::factory()->create([
            'user_id' => $user->id,
            'cv_id' => $cv->id,
        ]);

        app()->setLocale('en');

        try {
            (new ProcessCoverLetter($letter->id))->handle();
        } catch (Throwable) {
            // expected
        }

        expect(app()->getLocale())->toBe('ar');
    });
});
