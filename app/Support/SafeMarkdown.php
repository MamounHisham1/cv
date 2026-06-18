<?php

namespace App\Support;

use League\CommonMark\GithubFlavoredMarkdownConverter;

/**
 * Render untrusted Markdown (AI output, user-influenced text) as HTML safely.
 *
 * `Str::markdown()` uses CommonMark's default, which passes raw HTML through
 * unchanged. AI output can be poisoned with `<script>` payloads via prompt
 * injection, so we configure the converter to escape any inline HTML instead
 * of emitting it verbatim.
 */
class SafeMarkdown
{
    /**
     * Convert Markdown to sanitized HTML.
     *
     * - Inline/raw HTML is escaped (`html_input => 'escape'`)
     * - Unsafe links (`javascript:`, `data:`) are dropped (`allow_unsafe_links => false`)
     */
    public static function render(string $markdown): string
    {
        $converter = new GithubFlavoredMarkdownConverter([
            'html_input' => 'escape',
            'allow_unsafe_links' => false,
            'max_nesting_level' => 10,
        ]);

        // Normalize to UTF-8 (the previous code did this defensively).
        $normalized = mb_convert_encoding($markdown, 'UTF-8', 'UTF-8');

        return (string) $converter->convert($normalized);
    }
}
