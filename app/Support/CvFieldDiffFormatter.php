<?php

namespace App\Support;

use Carbon\CarbonInterface;

/**
 * Turns a proposed-change op (from ProposedCvChanges) into a list of
 * human-readable diff rows for the review card UI.
 *
 * Each row: ['field' => 'Start date', 'before' => 'Jun 2026', 'after' => 'Jun 2024'].
 * Only fields that actually changed are included.
 */
class CvFieldDiffFormatter
{
    /** Map machine field names to human labels. */
    private const LABELS = [
        'title' => 'Job title',
        'company' => 'Company',
        'location' => 'Location',
        'description' => 'Description',
        'start_date' => 'Start date',
        'end_date' => 'End date',
        'is_current' => 'Currently working here',
        'achievements' => 'Achievements',
        'key_achievements' => 'Key achievements',
        'technologies' => 'Technologies',
        'name' => 'Name',
        'institution' => 'Institution',
        'degree' => 'Degree',
        'field_of_study' => 'Field of study',
        'issuing_organization' => 'Issuing organization',
        'issue_date' => 'Issue date',
        'expiration_date' => 'Expiration date',
        'credential_id' => 'Credential ID',
        'credential_url' => 'Verification URL',
        'project_url' => 'Project URL',
        'github_url' => 'GitHub URL',
        'category' => 'Category',
        'level' => 'Level',
        'language' => 'Language',
        'proficiency' => 'Proficiency',
        'first_name' => 'First name',
        'last_name' => 'Last name',
        'email' => 'Email',
        'phone' => 'Phone',
        'linkedin' => 'LinkedIn',
        'github' => 'GitHub',
        'website' => 'Website',
        'summary' => 'Summary',
    ];

    /** Date fields to render as "M Y". */
    private const DATE_FIELDS = [
        'start_date', 'end_date', 'issue_date', 'expiration_date',
    ];

    /**
     * @param  array{action: string, section: string, before: array, after: array}  $op
     * @return array<int, array{field: string, before: string, after: string}>
     */
    public function rows(array $op): array
    {
        // Creates have no "before" — show all fields as additions.
        if ($op['action'] === 'create') {
            return $this->createRows($op['after']);
        }

        $rows = [];
        $before = $op['before'];
        $after = $op['after'];

        foreach ($after as $field => $newValue) {
            $oldValue = $before[$field] ?? null;

            // Skip genuinely unchanged values.
            if ($this->equals($oldValue, $newValue)) {
                continue;
            }

            $rows[] = [
                'field' => self::LABELS[$field] ?? ucfirst(str_replace('_', ' ', $field)),
                'before' => $this->format($field, $oldValue),
                'after' => $this->format($field, $newValue),
            ];
        }

        return $rows;
    }

    /**
     * @return array<int, array{field: string, before: string, after: string}>
     */
    private function createRows(array $after): array
    {
        $rows = [];

        foreach ($after as $field => $value) {
            if (in_array($field, ['id', 'cv_id', 'sort_order', 'created_at', 'updated_at'], true)) {
                continue;
            }

            $formatted = $this->format($field, $value);
            if ($formatted === '' || $formatted === '—') {
                continue;
            }

            $rows[] = [
                'field' => self::LABELS[$field] ?? ucfirst(str_replace('_', ' ', $field)),
                'before' => '',
                'after' => $formatted,
            ];
        }

        return $rows;
    }

    private function format(string $field, mixed $value): string
    {
        if (is_array($value)) {
            $filtered = array_filter($value, fn ($v) => ! is_array($v) && trim((string) $v) !== '');

            return empty($filtered) ? '—' : implode(', ', $filtered);
        }

        if ($value === null || trim((string) $value) === '') {
            return '—';
        }

        if (in_array($field, self::DATE_FIELDS, true)) {
            try {
                $carbon = $value instanceof CarbonInterface ? $value : now()->parse($value);

                return $carbon->format('M Y');
            } catch (\Throwable) {
                return (string) $value;
            }
        }

        if (is_bool($value)) {
            return $value ? 'Yes' : 'No';
        }

        return (string) $value;
    }

    private function equals(mixed $a, mixed $b): bool
    {
        // Normalize arrays before comparing.
        if (is_array($a) || is_array($b)) {
            return $this->format('x', $a) === $this->format('x', $b);
        }

        return (string) $a === (string) $b;
    }
}
