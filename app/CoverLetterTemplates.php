<?php

namespace App;

/**
 * Single source of truth for cover-letter template metadata, mirroring
 * {@see CvTemplates}. Adding a template = create the Blade file + add an
 * entry to {@see self::all()}.
 */
final class CoverLetterTemplates
{
    /**
     * @return array<string, array{name: string, description: string, icon: string}>
     */
    public static function all(): array
    {
        return [
            'classic' => [
                'name' => 'Classic',
                'description' => 'Traditional single-column business letter',
                'icon' => 'document-text',
            ],
            'modern' => [
                'name' => 'Modern',
                'description' => 'Clean sans-serif layout with a subtle accent rule',
                'icon' => 'sparkles',
            ],
            'concise' => [
                'name' => 'Concise',
                'description' => 'Tight three-paragraph letter for quick reads',
                'icon' => 'light-bulb',
            ],
        ];
    }

    /**
     * @return array<string, string> slug => display name, for selectors.
     */
    public static function options(): array
    {
        return array_map(fn (array $t): string => $t['name'], self::all());
    }

    public static function name(string $slug): string
    {
        return self::all()[$slug]['name'] ?? ucwords(str_replace('-', ' ', $slug));
    }

    public static function default(): string
    {
        return 'classic';
    }

    public static function exists(string $slug): bool
    {
        return isset(self::all()[$slug]);
    }
}
