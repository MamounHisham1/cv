<?php

namespace App\Cv\IndustryPacks;

use App\CvTemplates;

/**
 * Single source of truth for available industry packs — mirrors the
 * {@see CvTemplates} registry pattern.
 *
 * Every place that lists, names, or resolves a pack MUST read from here.
 * Stored slugs on `cvs.industry_pack` resolve through {@see self::get()}.
 */
final class IndustryPacks
{
    /**
     * @return array<string, IndustryPack> slug => pack instance.
     */
    public static function all(): array
    {
        return [
            'generic' => new GenericPack,
            'cloud' => new CloudPack,
        ];
    }

    /**
     * @return array<string, string> slug => display name, for selectors.
     */
    public static function options(): array
    {
        return array_map(fn (IndustryPack $pack): string => $pack->name(), self::all());
    }

    /**
     * Resolve a stored slug to a pack, falling back to the default.
     */
    public static function get(?string $slug): IndustryPack
    {
        return self::all()[$slug] ?? self::default();
    }

    /**
     * The pack used when a CV has no `industry_pack` set.
     */
    public static function default(): GenericPack
    {
        return new GenericPack;
    }

    public static function exists(string $slug): bool
    {
        return isset(self::all()[$slug]);
    }
}
