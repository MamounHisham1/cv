<?php

namespace App;

/**
 * Single source of truth for CV template metadata.
 *
 * Every place that lists, names, or picks a template MUST read from here —
 * the homepage gallery, the builder picker, the importer picker, the Filament
 * admin form/table, and the AI template-selection tool. Hardcoding template
 * arrays anywhere else causes the slugs and display names to drift.
 *
 * When adding a new template:
 *   1. Create resources/views/cv/templates/<slug>.blade.php
 *   2. Add an entry to {@see self::all()} with name/description/icon/features
 *   3. Declare the features it renders so the builder can notify the user.
 */
final class CvTemplates
{
    /**
     * Feature: template renders an optional profile photo/avatar.
     * The builder surfaces a photo upload (creative + warm) only when set.
     */
    public const FEATURE_PHOTO = 'photo';

    /**
     * Feature: template visualizes skill proficiency levels
     * (bars/dots/text). The builder nudges users to fill skill levels.
     */
    public const FEATURE_SKILL_LEVELS = 'skill_levels';

    /**
     * @return array<string, array{name: string, description: string, icon: string, features: list<string>}>
     */
    public static function all(): array
    {
        return [
            'professional-classic' => [
                'name' => 'Professional Classic',
                'description' => 'Traditional, corporate-friendly layout',
                'icon' => 'document-text',
                'features' => [],
            ],
            'technical-ats' => [
                'name' => 'Technical ATS',
                'description' => 'Optimized for Applicant Tracking Systems',
                'icon' => 'code-bracket',
                'features' => [],
            ],
            'modern-minimal' => [
                'name' => 'Modern Minimal',
                'description' => 'Clean, contemporary design',
                'icon' => 'sparkles',
                'features' => [],
            ],
            'creative' => [
                'name' => 'Creative',
                'description' => 'Visual sidebar with skill highlights',
                'icon' => 'paint-brush',
                'features' => [self::FEATURE_PHOTO, self::FEATURE_SKILL_LEVELS],
            ],
            'executive' => [
                'name' => 'Executive',
                'description' => 'Leadership-focused layout',
                'icon' => 'briefcase',
                'features' => [],
            ],
            'bold' => [
                'name' => 'Bold',
                'description' => 'Eye-catching header with vibrant indigo tones and categorized skills',
                'icon' => 'fire',
                'features' => [],
            ],
            'timeline' => [
                'name' => 'Timeline',
                'description' => 'Visual career timeline with connected dots and date axis',
                'icon' => 'clock',
                'features' => [],
            ],
            'swiss' => [
                'name' => 'Swiss',
                'description' => 'Grid-based typographic design with bold red accents',
                'icon' => 'grid',
                'features' => [self::FEATURE_SKILL_LEVELS],
            ],
            'warm' => [
                'name' => 'Warm',
                'description' => 'Approachable two-column layout with warm cream sidebar and amber accents',
                'icon' => 'sun',
                'features' => [self::FEATURE_PHOTO, self::FEATURE_SKILL_LEVELS],
            ],
            'compact' => [
                'name' => 'Compact',
                'description' => 'Dense single-column layout for experienced professionals',
                'icon' => 'arrows-pointing-in',
                'features' => [],
            ],
        ];
    }

    /**
     * @return array<string, string> slug => display name, for selects/filters.
     */
    public static function options(): array
    {
        return array_map(fn (array $t): string => $t['name'], self::all());
    }

    /**
     * Resolve a slug to its display name, falling back to a title-cased slug.
     */
    public static function name(string $slug): string
    {
        return self::all()[$slug]['name'] ?? ucwords(str_replace('-', ' ', $slug));
    }

    /**
     * The default template slug for new CVs.
     */
    public static function default(): string
    {
        return 'professional-classic';
    }

    /**
     * Whether a template renders a given feature (see FEATURE_* constants).
     */
    public static function supports(string $slug, string $feature): bool
    {
        return in_array($feature, self::all()[$slug]['features'] ?? [], true);
    }

    /**
     * Whether the slug is a registered template.
     */
    public static function exists(string $slug): bool
    {
        return isset(self::all()[$slug]);
    }
}
