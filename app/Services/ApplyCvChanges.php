<?php

namespace App\Services;

use App\Models\Cv;
use Illuminate\Support\Facades\DB;

/**
 * Applies user-approved CV change operations to the database.
 *
 * Operations come from ProposedCvChanges (staged by the AI write tools) and
 * are only ever applied after the user reviews the diff and clicks approve.
 * Everything runs in a single transaction, ordered so resequencing sees the
 * final row set: deletes → creates/updates → resequence touched sections.
 */
class ApplyCvChanges
{
    /** Sections that are real HasMany relations on Cv (vs. Cv-level fields). */
    private const RELATION_SECTIONS = [
        'experiences',
        'educations',
        'projects',
        'certifications',
        'skills',
        'languages',
    ];

    /**
     * @param  array<int, array{id: string, action: string, section: string, record_id: int|null, before: array, after: array, label: string, summary: string}>  $ops
     * @return array{applied: int, skipped: int}
     */
    public function apply(Cv $cv, array $ops): array
    {
        $applied = 0;
        $skipped = 0;
        $touchedSections = [];

        DB::transaction(function () use ($cv, $ops, &$applied, &$skipped, &$touchedSections) {
            // 1) Deletes first so later resequencing reflects the final set.
            foreach ($ops as $op) {
                if ($op['action'] !== 'delete') {
                    continue;
                }

                if (! in_array($op['section'], self::RELATION_SECTIONS, true)) {
                    $skipped++;

                    continue;
                }

                $record = $cv->{$op['section']}()->find($op['record_id']);
                if (! $record) {
                    $skipped++;

                    continue;
                }

                $record->delete();
                $touchedSections[$op['section']] = true;
                $applied++;
            }

            // 2) Creates then updates (order doesn't matter between them).
            foreach ($ops as $op) {
                if ($op['action'] === 'create') {
                    if (! in_array($op['section'], self::RELATION_SECTIONS, true)) {
                        $skipped++;

                        continue;
                    }

                    $cv->{$op['section']}()->create($this->writableAttributes($op['after']));
                    $touchedSections[$op['section']] = true;
                    $applied++;
                } elseif ($op['action'] === 'update') {
                    $this->applyUpdate($cv, $op, $touchedSections, $skipped);
                    $applied++;
                }
            }

            // 3) Resequence every section that had rows added or removed.
            foreach (array_keys($touchedSections) as $section) {
                $this->resequence($cv, $section);
            }
        });

        return ['applied' => $applied, 'skipped' => $skipped];
    }

    private function applyUpdate(Cv $cv, array $op, array &$touchedSections, int &$skipped): void
    {
        // Cv-level fields (no record id): personal_info / summary.
        if ($op['record_id'] === null) {
            $this->applyCvField($cv, $op);

            return;
        }

        if (! in_array($op['section'], self::RELATION_SECTIONS, true)) {
            $skipped++;

            return;
        }

        $record = $cv->{$op['section']}()->find($op['record_id']);
        if (! $record) {
            $skipped++;

            return;
        }

        $record->update($this->writableAttributes($op['after']));
    }

    private function applyCvField(Cv $cv, array $op): void
    {
        if ($op['section'] === 'personal_info') {
            // `after` is the fully-merged personal_info array.
            $cv->update(['personal_info' => $op['after']]);
        } elseif ($op['section'] === 'summary') {
            $cv->update(['summary' => $op['after']['summary'] ?? $cv->summary]);
        }
    }

    /**
     * Strip out keys that must never be written from a proposed payload
     * (id, cv_id, timestamps) so re-creating from a `before` snapshot or
     * applying an `after` patch can't clobber ownership.
     */
    private function writableAttributes(array $attributes): array
    {
        return collect($attributes)
            ->except(['id', 'cv_id', 'created_at', 'updated_at'])
            ->toArray();
    }

    private function resequence(Cv $cv, string $section): void
    {
        $cv->{$section}()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->each(fn ($item, $i) => $item->update(['sort_order' => $i]));
    }
}
