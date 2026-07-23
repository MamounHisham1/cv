<?php

namespace App\Support;

use App\Http\Middleware\SetAppLocale;

/**
 * Produces a prompt directive that tells the LLM which language to use
 * for its human-readable output.
 *
 * Why a separate class: every user-facing agent needs the same instruction,
 * but the directive differs per locale. Centralizing it keeps the wording
 * consistent and testable, and lets queue jobs (which lose the request
 * locale) re-derive it from the user's stored preference.
 *
 * Usage from an agent's instructions():
 *   return $baseInstructions . AiLocaleDirective::for();
 */
class AiLocaleDirective
{
    /**
     * Build the language directive for the given locale.
     * Falls back to the mirror-language instruction for unknown locales,
     * so newly added locales default to safe behavior instead of English.
     *
     * @param  string|null  $locale  Locale code ('ar', 'en', ...). Null reads app()->getLocale().
     */
    public static function for(?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        return match ($locale) {
            'ar' => self::arabic(),
            default => self::mirror(),
        };
    }

    /**
     * Arabic directive: everything user-facing must be in Arabic, while
     * structural tokens (JSON keys, tool names, code, technical terms)
     * stay in English so the system keeps working.
     */
    private static function arabic(): string
    {
        return <<<'DIRECTIVE'

=== RESPONSE LANGUAGE ===
You MUST write ALL human-readable output in Arabic (العربية) — this includes chat replies, explanations, suggestions, cover-letter prose, evaluation reasons, summaries, and any free-text field the user will read.

Exceptions that MUST stay in English (do not translate):
- JSON object keys and structured-output field names
- Tool names and function names (e.g. update_cv_summary, ask_clarifying_questions)
- Programming languages, framework names, and well-known technical terms (e.g. Laravel, React, REST, SQL, CI/CD)
- Proper nouns: company names, institution names, certifications, and candidate names as written

If the user writes in English, you may still reply in Arabic unless they explicitly ask for English. Match a casual-professional register (use أنتَ/أنتِ, not strict formal). Write dates and numbers using Arabic-Indic or Western digits as natural for the context.
DIRECTIVE;
    }

    /**
     * Default directive: reply in whatever language the user is writing in.
     * This makes the agent follow an explicit Arabic request even when the
     * stored locale is 'en', and vice versa.
     */
    private static function mirror(): string
    {
        return <<<'DIRECTIVE'

=== RESPONSE LANGUAGE ===
Reply in the same language the user writes in. If the user writes in Arabic, reply in Arabic. If they write in English, reply in English. Keep JSON keys, tool names, and technical terms (frameworks, languages) in English regardless.
DIRECTIVE;
    }

    /**
     * Supported locale codes that have a dedicated directive.
     * Mirrors SetAppLocale::SUPPORTED_LOCALES for symmetry.
     *
     * @return list<string>
     */
    public static function supportedLocales(): array
    {
        return array_keys(SetAppLocale::SUPPORTED_LOCALES);
    }
}
