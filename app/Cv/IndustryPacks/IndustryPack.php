<?php

namespace App\Cv\IndustryPacks;

/**
 * An industry pack supplies presets (skill categories, quick-add skill
 * suggestions, certification suggestions, etc.) and an optional prompt
 * context string that the AI agents can fold into their instructions.
 *
 * The CV schema itself is industry-neutral; a pack only shapes what the
 * UI offers and how the AI frames its output. Every CV resolves to
 * exactly one pack (defaulting to {@see GenericPack}).
 */
interface IndustryPack
{
    /** Stable slug stored on `cvs.industry_pack`. */
    public function id(): string;

    /** Human-readable name shown in selectors. */
    public function name(): string;

    /** Short marketing copy. */
    public function description(): string;

    /**
     * @return array<string, string> key => label, fed straight into skill
     *                               category selectors. Keys are persisted on `cv_skills.category`.
     */
    public function skillCategories(): array;

    /**
     * @return list<string> quick-add skill chips surfaced in the builder.
     */
    public function skillSuggestions(): array;

    /**
     * @return list<string> certification name suggestions.
     */
    public function certificationSuggestions(): array;

    /**
     * Extra context injected into AI agent instructions when this pack is
     * active. Empty for industry-neutral packs.
     */
    public function promptContext(): string;
}
