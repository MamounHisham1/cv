# Evaluation History & Comparison Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Add evaluation history listing and side-by-side comparison with per-criteria diffs to the CV Evaluator page.

**Architecture:** Extend the existing `CvEvaluator` Livewire component with new properties and methods for loading history, selecting evaluations for comparison, and computing score diffs. All UI goes into the existing `cv-evaluator.blade.php`. No new routes or migrations needed.

**Tech Stack:** Livewire 4, Blade, Tailwind CSS 4, SQLite, Pest 4

---

### Task 1: Test — Evaluation History Loading

**Files:**
- Create: `tests/Feature/CvEvaluatorHistoryTest.php`
- Reference: `app/Livewire/CvEvaluator.php`, `app/Models/CvEvaluation.php`

- [ ] **Step 1: Write the failing test**

```php
<?php

use App\Livewire\CvEvaluator;
use App\Models\CvEvaluation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

describe('Evaluation History', function () {
    it('loads evaluation history on mount', function () {
        CvEvaluation::factory()->count(3)->create(['user_id' => $this->user->id]);

        Livewire::actingAs($this->user)
            ->test(CvEvaluator::class)
            ->assertSet('evaluations', function ($evaluations) {
                return count($evaluations) === 3;
            });
    });

    it('loads evaluations ordered by newest first', function () {
        $old = CvEvaluation::factory()->create([
            'user_id' => $this->user->id,
            'overall_score' => 50,
            'created_at' => now()->subDays(5),
        ]);
        $new = CvEvaluation::factory()->create([
            'user_id' => $this->user->id,
            'overall_score' => 80,
            'created_at' => now(),
        ]);

        Livewire::actingAs($this->user)
            ->test(CvEvaluator::class)
            ->assertSet('evaluations.0.id', $new->id)
            ->assertSet('evaluations.1.id', $old->id);
    });

    it('shows empty evaluations for guest', function () {
        Livewire::test(CvEvaluator::class)
            ->assertSet('evaluations', []);
    });

    it('only loads current users evaluations', function () {
        $otherUser = User::factory()->create();
        CvEvaluation::factory()->create(['user_id' => $otherUser->id]);
        CvEvaluation::factory()->count(2)->create(['user_id' => $this->user->id]);

        Livewire::actingAs($this->user)
            ->test(CvEvaluator::class)
            ->assertSet('evaluations', function ($evaluations) {
                return count($evaluations) === 2;
            });
    });
});
```

- [ ] **Step 2: Create CvEvaluation factory**

Run: `php artisan make:factory CvEvaluation/CvEvaluationFactory --no-interaction`

Then write the factory at `database/factories/CvEvaluationFactory.php`:

```php
<?php

namespace Database\Factories\CvEvaluation;

use App\Models\CvEvaluation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CvEvaluationFactory extends Factory
{
    protected $model = CvEvaluation::class;

    public function definition(): array
    {
        $criteria = [
            'contact_information' => ['score' => fake()->numberBetween(5, 10), 'reason' => fake()->sentence()],
            'professional_summary' => ['score' => fake()->numberBetween(5, 10), 'reason' => fake()->sentence()],
            'work_experience' => ['score' => fake()->numberBetween(5, 10), 'reason' => fake()->sentence()],
            'skills_section' => ['score' => fake()->numberBetween(5, 10), 'reason' => fake()->sentence()],
            'education' => ['score' => fake()->numberBetween(5, 10), 'reason' => fake()->sentence()],
            'ats_compatibility' => ['score' => fake()->numberBetween(5, 10), 'reason' => fake()->sentence()],
            'formatting_readability' => ['score' => fake()->numberBetween(5, 10), 'reason' => fake()->sentence()],
            'achievements_impact' => ['score' => fake()->numberBetween(5, 10), 'reason' => fake()->sentence()],
            'keyword_optimisation' => ['score' => fake()->numberBetween(5, 10), 'reason' => fake()->sentence()],
            'overall_completeness' => ['score' => fake()->numberBetween(5, 10), 'reason' => fake()->sentence()],
        ];

        $overallScore = (int) round(array_sum(array_column($criteria, 'score')) / count($criteria) * 10);

        return [
            'user_id' => User::factory(),
            'filename' => fake()->optional()->word() . '.pdf',
            'overall_score' => $overallScore,
            'grade' => fake()->randomElement(['A+', 'A', 'B+', 'B', 'C+', 'C', 'D', 'F']),
            'summary' => fake()->paragraph(),
            'criteria' => $criteria,
            'top_strengths' => fake()->sentences(3),
            'critical_improvements' => fake()->sentences(3),
            'cv_text' => fake()->paragraphs(5, true),
        ];
    }
}
```

Note: Check if the factory path follows the app's convention. Run `php artisan make:factory --help` to confirm the syntax. The factory class must be on the `CvEvaluation` model — verify the model has the `HasFactory` trait. If not, add it.

- [ ] **Step 3: Run test to verify it fails**

Run: `php artisan test --compact tests/Feature/CvEvaluatorHistoryTest.php`
Expected: FAIL — `$evaluations` property doesn't exist on CvEvaluator yet.

- [ ] **Step 4: Add `evaluations` property and load in mount**

In `app/Livewire/CvEvaluator.php`, add the property and load it in `mount()`:

Add property:
```php
/** @var array<int, \App\Models\CvEvaluation> */
public array $evaluations = [];
```

In `mount()`, after the existing latest-evaluation loading block (after line 59), add:
```php
$this->evaluations = auth()->check()
    ? auth()->user()->cvEvaluations()->latest()->get()->toArray()
    : [];
```

- [ ] **Step 5: Run test to verify it passes**

Run: `php artisan test --compact tests/Feature/CvEvaluatorHistoryTest.php`
Expected: PASS

- [ ] **Step 6: Commit**

```bash
git add tests/Feature/CvEvaluatorHistoryTest.php database/factories/CvEvaluationFactory.php app/Livewire/CvEvaluator.php app/Models/CvEvaluation.php
git commit -m "feat: add evaluation history loading with factory and tests"
```

---

### Task 2: Test — Evaluation Comparison

**Files:**
- Create: nothing new (add to existing test)
- Modify: `tests/Feature/CvEvaluatorHistoryTest.php`
- Reference: `app/Livewire/CvEvaluator.php`

- [ ] **Step 1: Write the failing test for comparison**

Append to `tests/Feature/CvEvaluatorHistoryTest.php` inside a new `describe` block:

```php
describe('Evaluation Comparison', function () {
    it('toggles evaluation selection for comparison', function () {
        $evals = CvEvaluation::factory()->count(3)->create(['user_id' => $this->user->id]);

        Livewire::actingAs($this->user)
            ->test(CvEvaluator::class)
            ->call('toggleEvaluationSelection', $evals[0]->id)
            ->assertSet('selectedEvaluationIds', [$evals[0]->id])
            ->call('toggleEvaluationSelection', $evals[1]->id)
            ->assertSet('selectedEvaluationIds', [$evals[0]->id, $evals[1]->id]);
    });

    it('deselects evaluation on second toggle', function () {
        $evals = CvEvaluation::factory()->count(2)->create(['user_id' => $this->user->id]);

        Livewire::actingAs($this->user)
            ->test(CvEvaluator::class)
            ->call('toggleEvaluationSelection', $evals[0]->id)
            ->call('toggleEvaluationSelection', $evals[0]->id)
            ->assertSet('selectedEvaluationIds', []);
    });

    it('limits selection to two evaluations', function () {
        $evals = CvEvaluation::factory()->count(4)->create(['user_id' => $this->user->id]);

        Livewire::actingAs($this->user)
            ->test(CvEvaluator::class)
            ->call('toggleEvaluationSelection', $evals[0]->id)
            ->call('toggleEvaluationSelection', $evals[1]->id)
            ->call('toggleEvaluationSelection', $evals[2]->id)
            ->assertSet('selectedEvaluationIds', [$evals[0]->id, $evals[1]->id]);
    });

    it('computes comparison diff between two evaluations', function () {
        $evalA = CvEvaluation::factory()->create([
            'user_id' => $this->user->id,
            'overall_score' => 60,
            'criteria' => [
                'contact_information' => ['score' => 6, 'reason' => 'Good'],
                'work_experience' => ['score' => 5, 'reason' => 'Average'],
            ],
        ]);
        $evalB = CvEvaluation::factory()->create([
            'user_id' => $this->user->id,
            'overall_score' => 75,
            'criteria' => [
                'contact_information' => ['score' => 8, 'reason' => 'Excellent'],
                'work_experience' => ['score' => 7, 'reason' => 'Improved'],
            ],
        ]);

        Livewire::actingAs($this->user)
            ->test(CvEvaluator::class)
            ->call('toggleEvaluationSelection', $evalA->id)
            ->call('toggleEvaluationSelection', $evalB->id)
            ->assertSet('comparisonResult', function ($result) {
                return $result['overall_diff'] === 15
                    && $result['criteria_diffs']['contact_information'] === 2
                    && $result['criteria_diffs']['work_experience'] === 2;
            });
    });

    it('clears comparison when deselecting', function () {
        $evals = CvEvaluation::factory()->count(2)->create(['user_id' => $this->user->id]);

        Livewire::actingAs($this->user)
            ->test(CvEvaluator::class)
            ->call('toggleEvaluationSelection', $evals[0]->id)
            ->call('toggleEvaluationSelection', $evals[1]->id)
            ->call('toggleEvaluationSelection', $evals[0]->id)
            ->assertSet('comparisonResult', null);
    });

    it('reloads evaluations after new evaluation is saved', function () {
        CvEvaluation::factory()->create(['user_id' => $this->user->id]);

        $component = Livewire::actingAs($this->user)
            ->test(CvEvaluator::class);

        $initialCount = count($component->get('evaluations'));

        // Simulate a new evaluation being created directly
        CvEvaluation::factory()->create(['user_id' => $this->user->id]);

        $component->call('refreshEvaluations')
            ->assertSet('evaluations', function ($evaluations) use ($initialCount) {
                return count($evaluations) === $initialCount + 1;
            });
    });
});
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --compact tests/Feature/CvEvaluatorHistoryTest.php`
Expected: FAIL — methods don't exist yet.

- [ ] **Step 3: Implement comparison methods in CvEvaluator**

In `app/Livewire/CvEvaluator.php`, add these properties and methods:

Properties:
```php
/** @var array<int, int> Max 2 selected evaluation IDs for comparison */
public array $selectedEvaluationIds = [];

/** @var array<string, mixed>|null Comparison diff result */
public ?array $comparisonResult = null;
```

Methods:
```php
public function toggleEvaluationSelection(int $evaluationId): void
{
    if (in_array($evaluationId, $this->selectedEvaluationIds)) {
        $this->selectedEvaluationIds = array_values(
            array_diff($this->selectedEvaluationIds, [$evaluationId])
        );
        $this->comparisonResult = null;
    } elseif (count($this->selectedEvaluationIds) < 2) {
        $this->selectedEvaluationIds[] = $evaluationId;
    }

    if (count($this->selectedEvaluationIds) === 2) {
        $this->computeComparison();
    }
}

public function computeComparison(): void
{
    if (count($this->selectedEvaluationIds) !== 2) {
        $this->comparisonResult = null;

        return;
    }

    $evaluations = auth()->user()->cvEvaluations()
        ->whereIn('id', $this->selectedEvaluationIds)
        ->get()
        ->keyBy('id');

    $evalA = $evaluations->get($this->selectedEvaluationIds[0]);
    $evalB = $evaluations->get($this->selectedEvaluationIds[1]);

    if (! $evalA || ! $evalB) {
        $this->comparisonResult = null;

        return;
    }

    $allCriteriaKeys = [
        'contact_information', 'professional_summary', 'work_experience',
        'skills_section', 'education', 'ats_compatibility',
        'formatting_readability', 'achievements_impact',
        'keyword_optimisation', 'overall_completeness',
    ];

    $criteriaDiffs = [];
    foreach ($allCriteriaKeys as $key) {
        $scoreA = $evalA->criteria[$key]['score'] ?? 0;
        $scoreB = $evalB->criteria[$key]['score'] ?? 0;
        $criteriaDiffs[$key] = $scoreB - $scoreA;
    }

    $this->comparisonResult = [
        'eval_a' => $evalA->toArray(),
        'eval_b' => $evalB->toArray(),
        'overall_diff' => $evalB->overall_score - $evalA->overall_score,
        'grade_diff' => $evalB->grade !== $evalA->grade,
        'criteria_diffs' => $criteriaDiffs,
    ];
}

public function refreshEvaluations(): void
{
    $this->evaluations = auth()->check()
        ? auth()->user()->cvEvaluations()->latest()->get()->toArray()
        : [];
}
```

Also update the `evaluate()` method — after the `CvEvaluation::create()` block (around line 324), add:
```php
$this->refreshEvaluations();
```

And in `restart()`, add:
```php
$this->selectedEvaluationIds = [];
$this->comparisonResult = null;
```

- [ ] **Step 4: Run test to verify it passes**

Run: `php artisan test --compact tests/Feature/CvEvaluatorHistoryTest.php`
Expected: PASS

- [ ] **Step 5: Commit**

```bash
git add app/Livewire/CvEvaluator.php tests/Feature/CvEvaluatorHistoryTest.php
git commit -m "feat: add evaluation selection and comparison logic with tests"
```

---

### Task 3: UI — Evaluation History List

**Files:**
- Modify: `resources/views/livewire/cv-evaluator.blade.php`

- [ ] **Step 1: Add history section to the Blade view**

In `resources/views/livewire/cv-evaluator.blade.php`, add the following block **before the closing `</div>` of the main container** (before the last `</div>` on line 227). This should appear below the results/strengths section, visible when the user has past evaluations:

```blade
{{-- ===== EVALUATION HISTORY ===== --}}
@if(auth()->check() && count($evaluations) > 0)
<div class="mt-10 {{ $glassCard }}">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h3 class="text-lg font-bold text-white">Evaluation History</h3>
            <p class="text-sm text-zinc-500">Select two evaluations to compare scores</p>
        </div>
        @if(count($selectedEvaluationIds) > 0)
            <button wire:click="$set('selectedEvaluationIds', []) && $set('comparisonResult', null)"
                class="{{ $ghostBtn }} text-xs">
                <x-ui::icon name="x" class="h-3 w-3" /> Clear Selection
            </button>
        @endif
    </div>

    {{-- Score progression sparkline --}}
    <div class="mb-6 flex items-end gap-1 h-16">
        @foreach($evaluations as $eval)
            @php
                $heightPct = max(15, ($eval['overall_score'] ?? 0));
                $isSelected = in_array($eval['id'], $selectedEvaluationIds);
                $barColor = $isSelected
                    ? 'bg-emerald-400'
                    : 'bg-zinc-600 hover:bg-zinc-500';
            @endphp
            <div class="flex-1 min-w-[4px] rounded-t {{ $barColor }} transition-all duration-300 cursor-pointer"
                 style="height: {{ $heightPct }}%"
                 wire:click="toggleEvaluationSelection({{ $eval['id'] }})">
            </div>
        @endforeach>
    </div>

    {{-- History list --}}
    <div class="space-y-3 max-h-[420px] overflow-y-auto pr-1 scrollbar-thin">
        @foreach($evaluations as $index => $eval)
            @php
                $isSelected = in_array($eval['id'], $selectedEvaluationIds);
                $prevScore = isset($evaluations[$index + 1]) ? $evaluations[$index + 1]['overall_score'] : null;
                $scoreDiff = $prevScore !== null ? $eval['overall_score'] - $prevScore : null;
            @endphp
            <div wire:click="toggleEvaluationSelection({{ $eval['id'] }})"
                class="group flex items-center gap-4 rounded-2xl border {{ $isSelected ? 'border-emerald-400/30 bg-emerald-500/10' : 'border-white/5 bg-white/[0.02] hover:bg-white/5' }} p-4 transition-all duration-200 cursor-pointer">

                {{-- Selection indicator --}}
                <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-md border {{ $isSelected ? 'border-emerald-400 bg-emerald-500/20' : 'border-white/20' }}">
                    @if($isSelected)
                        <x-ui::icon name="check" class="h-3 w-3 text-emerald-400" />
                    @endif
                </div>

                {{-- Score badge --}}
                <div class="flex h-12 w-12 shrink-0 flex-col items-center justify-center rounded-xl border {{ $isSelected ? 'border-emerald-400/20 bg-emerald-500/10' : 'border-white/10 bg-white/5' }}">
                    <span class="text-sm font-bold {{ $this->gradeColour($eval['grade'] ?? 'F') }}">{{ $eval['grade'] ?? '—' }}</span>
                    <span class="text-[10px] text-zinc-500">{{ $eval['overall_score'] ?? 0 }}</span>
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <p class="truncate text-sm font-medium text-zinc-200">
                        {{ $eval['filename'] ?? 'Pasted Text' }}
                    </p>
                    <p class="text-xs text-zinc-500">
                        {{ \Carbon\Carbon::parse($eval['created_at'])->format('M j, Y \a\t g:i A') }}
                    </p>
                </div>

                {{-- Score diff indicator --}}
                @if($scoreDiff !== null)
                    <div class="shrink-0 flex items-center gap-1 text-xs font-semibold {{ $scoreDiff > 0 ? 'text-emerald-400' : ($scoreDiff < 0 ? 'text-red-400' : 'text-zinc-500') }}">
                        @if($scoreDiff > 0)
                            <x-ui::icon name="trending-up" class="h-3 w-3" />
                            +{{ $scoreDiff }}
                        @elseif($scoreDiff < 0)
                            <x-ui::icon name="trending-down" class="h-3 w-3" />
                            {{ $scoreDiff }}
                        @else
                            <x-ui::icon name="minus" class="h-3 w-3" />
                            0
                        @endif
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endif
```

- [ ] **Step 2: Verify the icons exist**

Check if the icon names used (`trending-up`, `trending-down`, `minus`, `check`) are available in the project's icon set. Run:

```bash
grep -r 'trending-up\|trending-down\|minus' resources/views/ --include="*.blade.php" -l
```

If any icons are missing, replace with available alternatives from the existing codebase patterns.

- [ ] **Step 3: Visually verify**

Run: `npm run build` (or ask the user to run `npm run dev`)
Then visit `/cv-evaluator` and verify the history section renders.

- [ ] **Step 4: Commit**

```bash
git add resources/views/livewire/cv-evaluator.blade.php
git commit -m "feat: add evaluation history list UI with score progression bar"
```

---

### Task 4: UI — Side-by-Side Comparison Panel

**Files:**
- Modify: `resources/views/livewire/cv-evaluator.blade.php`

- [ ] **Step 1: Add comparison panel to the Blade view**

In `resources/views/livewire/cv-evaluator.blade.php`, add the comparison block **right after the history section** (from Task 3), still inside the main container div:

```blade
{{-- ===== COMPARISON PANEL ===== --}}
@if($comparisonResult)
<div class="mt-6 {{ $glassCard }}" wire:key="comparison-{{ implode('-', $selectedEvaluationIds) }}">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h3 class="text-lg font-bold text-white">Comparison</h3>
            <p class="text-sm text-zinc-500">Score differences between the two selected evaluations</p>
        </div>
    </div>

    {{-- Overall score comparison --}}
    <div class="mb-6 grid grid-cols-3 items-center gap-4 rounded-2xl border border-white/5 bg-white/[0.02] p-4">
        {{-- Eval A --}}
        <div class="text-center">
            <p class="mb-1 text-xs text-zinc-500">{{ \Carbon\Carbon::parse($comparisonResult['eval_a']['created_at'])->format('M j') }}</p>
            <div class="inline-flex h-16 w-16 flex-col items-center justify-center rounded-full border-2 border-white/10 bg-white/5">
                <span class="text-xl font-black {{ $this->gradeColour($comparisonResult['eval_a']['grade']) }}">{{ $comparisonResult['eval_a']['grade'] }}</span>
                <span class="text-[10px] text-zinc-400">{{ $comparisonResult['eval_a']['overall_score'] }}</span>
            </div>
            <p class="mt-1 truncate text-xs text-zinc-400">{{ $comparisonResult['eval_a']['filename'] ?? 'Pasted' }}</p>
        </div>

        {{-- Diff indicator --}}
        <div class="text-center">
            @php
                $overallDiff = $comparisonResult['overall_diff'];
                $diffColor = $overallDiff > 0 ? 'text-emerald-400' : ($overallDiff < 0 ? 'text-red-400' : 'text-zinc-400');
                $diffBg = $overallDiff > 0 ? 'bg-emerald-500/10 border-emerald-400/20' : ($overallDiff < 0 ? 'bg-red-500/10 border-red-400/20' : 'bg-white/5 border-white/10');
            @endphp
            <div class="inline-flex items-center gap-1 rounded-full border {{ $diffBg }} px-4 py-2">
                @if($overallDiff > 0)
                    <x-ui::icon name="trending-up" class="h-4 w-4 {{ $diffColor }}" />
                @elseif($overallDiff < 0)
                    <x-ui::icon name="trending-down" class="h-4 w-4 {{ $diffColor }}" />
                @else
                    <x-ui::icon name="minus" class="h-4 w-4 {{ $diffColor }}" />
                @endif
                <span class="text-sm font-bold {{ $diffColor }}">
                    @if($overallDiff > 0)+@endif{{ $overallDiff }}
                </span>
            </div>
            <p class="mt-1 text-xs text-zinc-500">overall change</p>
        </div>

        {{-- Eval B --}}
        <div class="text-center">
            <p class="mb-1 text-xs text-zinc-500">{{ \Carbon\Carbon::parse($comparisonResult['eval_b']['created_at'])->format('M j') }}</p>
            <div class="inline-flex h-16 w-16 flex-col items-center justify-center rounded-full border-2 border-white/10 bg-white/5">
                <span class="text-xl font-black {{ $this->gradeColour($comparisonResult['eval_b']['grade']) }}">{{ $comparisonResult['eval_b']['grade'] }}</span>
                <span class="text-[10px] text-zinc-400">{{ $comparisonResult['eval_b']['overall_score'] }}</span>
            </div>
            <p class="mt-1 truncate text-xs text-zinc-400">{{ $comparisonResult['eval_b']['filename'] ?? 'Pasted' }}</p>
        </div>
    </div>

    {{-- Per-criteria comparison --}}
    <div class="space-y-4">
        @foreach([
            'contact_information' => 'Contact Information',
            'professional_summary' => 'Professional Summary',
            'work_experience' => 'Work Experience',
            'skills_section' => 'Skills Section',
            'education' => 'Education',
            'ats_compatibility' => 'ATS Compatibility',
            'formatting_readability' => 'Formatting & Readability',
            'achievements_impact' => 'Achievements & Impact',
            'keyword_optimisation' => 'Keyword Optimisation',
            'overall_completeness' => 'Overall Completeness',
        ] as $key => $label)
            @php
                $scoreA = $comparisonResult['eval_a']['criteria'][$key]['score'] ?? 0;
                $scoreB = $comparisonResult['eval_b']['criteria'][$key]['score'] ?? 0;
                $diff = $comparisonResult['criteria_diffs'][$key] ?? 0;
                $pctA = $scoreA * 10;
                $pctB = $scoreB * 10;
                $diffColor = $diff > 0 ? 'text-emerald-400' : ($diff < 0 ? 'text-red-400' : 'text-zinc-500');
                $diffBg = $diff > 0 ? 'bg-emerald-500/10 border-emerald-400/20' : ($diff < 0 ? 'bg-red-500/10 border-red-400/20' : 'bg-white/5 border-white/10');
            @endphp
            <div class="rounded-xl border border-white/5 bg-white/[0.02] p-3">
                <div class="mb-2 flex items-center justify-between">
                    <span class="text-sm font-medium text-zinc-300">{{ $label }}</span>
                    <span class="inline-flex items-center gap-1 rounded-full border {{ $diffBg }} px-2 py-0.5 text-xs font-semibold {{ $diffColor }}">
                        @if($diff > 0)+@endif{{ $diff }}
                    </span>
                </div>
                <div class="space-y-1.5">
                    {{-- Eval A bar --}}
                    <div class="flex items-center gap-2">
                        <span class="w-6 text-right text-[10px] text-zinc-500">{{ $scoreA }}</span>
                        <div class="flex-1 h-1.5 overflow-hidden rounded-full bg-white/5">
                            <div class="h-full rounded-full bg-zinc-500 transition-all duration-500" style="width: {{ $pctA }}%"></div>
                        </div>
                    </div>
                    {{-- Eval B bar --}}
                    <div class="flex items-center gap-2">
                        <span class="w-6 text-right text-[10px] text-zinc-500">{{ $scoreB }}</span>
                        <div class="flex-1 h-1.5 overflow-hidden rounded-full bg-white/5">
                            <div class="h-full rounded-full transition-all duration-500 {{ $diff > 0 ? 'bg-emerald-500' : ($diff < 0 ? 'bg-red-400' : 'bg-zinc-400') }}" style="width: {{ $pctB }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif
```

- [ ] **Step 2: Verify in browser**

Run: `npm run build` (or ask user to run `npm run dev`)
Visit `/cv-evaluator`, upload two different CVs (or create test data via tinker), select two evaluations, verify the comparison panel renders with colored diff indicators.

- [ ] **Step 3: Commit**

```bash
git add resources/views/livewire/cv-evaluator.blade.php
git commit -m "feat: add side-by-side evaluation comparison panel with per-criteria diffs"
```

---

### Task 5: Integration & Polish

**Files:**
- Modify: `app/Livewire/CvEvaluator.php`
- Modify: `resources/views/livewire/cv-evaluator.blade.php`

- [ ] **Step 1: Update `restart()` to also reset history state**

In `CvEvaluator.php`, the `restart()` method should already have the new resets from Task 2. Verify it includes:

```php
$this->selectedEvaluationIds = [];
$this->comparisonResult = null;
```

- [ ] **Step 2: Run all evaluator tests**

Run: `php artisan test --compact tests/Feature/CvEvaluatorHistoryTest.php`
Expected: All PASS

- [ ] **Step 3: Run pint on changed files**

Run: `vendor/bin/pint --dirty --format agent`

- [ ] **Step 4: Full visual smoke test**

Visit `/cv-evaluator` as an authenticated user with no evaluations — should show upload form only.
Upload a CV, evaluate — should show results + new history entry.
Upload another, evaluate — history shows 2 entries with trend bars.
Select both — comparison panel appears.
Deselect one — comparison disappears.

- [ ] **Step 5: Final commit**

```bash
git add -A
git commit -m "feat: complete evaluation history and comparison feature"
```
