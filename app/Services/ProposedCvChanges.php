<?php

namespace App\Services;

/**
 * Per-request buffer of CV edits proposed by the AI tools.
 *
 * Registered as a singleton. The write tools (Add/Update/Delete Cv*) push
 * proposed operations here instead of mutating the database — nothing the
 * model invents can reach the CV until the user reviews and approves it.
 * The Livewire component reads the buffer after the agent run and renders
 * a diff card; approved ops are handed to ApplyCvChanges.
 */
class ProposedCvChanges
{
    public const SECTIONS = [
        'experiences',
        'educations',
        'projects',
        'certifications',
        'skills',
        'languages',
        'personal_info',
        'summary',
    ];

    /** @var array<int, array{id: string, action: string, section: string, record_id: int|null, before: array, after: array, label: string, summary: string}> */
    protected array $ops = [];

    private int $counter = 0;

    /**
     * @param  array  $before  Current attributes of the record (empty for creates).
     * @param  array  $after  Proposed attributes (only changed/new keys for updates; full row for creates).
     * @param  int|null  $recordId  The record id being updated/deleted, null for creates & Cv-level fields.
     */
    public function add(string $action, string $section, array $before, array $after, ?int $recordId, string $label, string $summary): string
    {
        // Deduplicate against existing ops targeting the same record. LLMs
        // commonly call the same update tool several times in one turn
        // (looping over fields); without this, the review card shows the
        // same record many times. We keep ONE op per (action, section,
        // record_id) and merge successive updates' fields into it.
        foreach ($this->ops as $i => $op) {
            if ($op['action'] !== $action || $op['section'] !== $section || $op['record_id'] !== $recordId) {
                continue;
            }

            // Same target. For updates, merge field changes (later wins on
            // conflict); for everything else, replace wholesale.
            if ($action === 'update') {
                $this->ops[$i]['after'] = array_merge($op['after'], $after);
                // Refresh label/summary to the latest call's wording.
                $this->ops[$i]['label'] = $label;
                $this->ops[$i]['summary'] = $summary;
            } else {
                $this->ops[$i]['after'] = $after;
                $this->ops[$i]['before'] = $before;
                $this->ops[$i]['label'] = $label;
                $this->ops[$i]['summary'] = $summary;
            }

            return $this->ops[$i]['id'];
        }

        // No existing op for this target — append a new one.
        $id = 'pc_'.(++$this->counter);

        $this->ops[] = [
            'id' => $id,
            'action' => $action,
            'section' => $section,
            'record_id' => $recordId,
            'before' => $before,
            'after' => $after,
            'label' => $label,
            'summary' => $summary,
        ];

        return $id;
    }

    public function proposeUpdate(string $section, int $recordId, array $before, array $after, string $label, string $summary): string
    {
        return $this->add('update', $section, $before, $after, $recordId, $label, $summary);
    }

    public function proposeCreate(string $section, array $after, string $label, string $summary): string
    {
        return $this->add('create', $section, [], $after, null, $label, $summary);
    }

    public function proposeDelete(string $section, int $recordId, array $before, string $label, string $summary): string
    {
        return $this->add('delete', $section, $before, [], $recordId, $label, $summary);
    }

    /**
     * Specialized helper for Cv-level fields (personal_info / summary),
     * which have no per-row id.
     */
    public function proposeCvField(string $field, array $before, array $after, string $label, string $summary): string
    {
        return $this->add('update', $field, $before, $after, null, $label, $summary);
    }

    /**
     * @return array<int, array{id: string, action: string, section: string, record_id: int|null, before: array, after: array, label: string, summary: string}>
     */
    public function all(): array
    {
        return $this->ops;
    }

    public function has(): bool
    {
        return ! empty($this->ops);
    }

    public function count(): int
    {
        return count($this->ops);
    }

    public function clear(): void
    {
        $this->ops = [];
        $this->counter = 0;
    }
}
