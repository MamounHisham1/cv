<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * Casts a JSON column to an array, tolerating double-encoded JSON.
 *
 * Some legacy rows (e.g. experiences created via certain import/seed paths)
 * stored achievements/technologies as json_encode(json_encode([...])) — a
 * JSON string containing a JSON array. The native 'array' cast leaves such
 * values as a string after one decode, which then crashes foreach() in the
 * templates. This cast decodes again when the first decode still yields a
 * string, so both correctly-encoded and double-encoded data resolve to a
 * real array.
 *
 * @implements CastsAttributes<array, array>
 */
class JsonArray implements CastsAttributes
{
    /**
     * @param  string|null  $value
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?array
    {
        if ($value === null) {
            return null;
        }

        // Already an array (e.g. in-memory) — nothing to do.
        if (is_array($value)) {
            return $value;
        }

        $decoded = json_decode((string) $value, true);

        // Tolerate double-encoded JSON: if the first decode produced a
        // string, decode once more.
        if (is_string($decoded)) {
            $decoded = json_decode($decoded, true);
        }

        return is_array($decoded) ? $decoded : null;
    }

    /**
     * @param  array|null  $value
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        return is_array($value) ? json_encode($value) : (string) $value;
    }
}
