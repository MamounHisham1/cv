<?php

namespace App\Ai\Tools\Telegram;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Str;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class ReadApplicationLogs implements Tool
{
    public function description(): Stringable|string
    {
        return "Read the application's log file (storage/logs/laravel.log). Tail recent lines, filter by log level, or search by keyword. Use this to investigate errors or recent activity.";
    }

    public function handle(Request $request): Stringable|string
    {
        $lines = min((int) ($request['lines'] ?? 50), 200);
        $level = $request['level'] ?? null;
        $search = $request['search'] ?? null;

        $path = storage_path('logs/laravel.log');

        if (! file_exists($path)) {
            return 'Log file not found.';
        }

        $contents = file_get_contents($path);

        if ($contents === false || $contents === '') {
            return 'Log file is empty.';
        }

        $allLines = explode("\n", rtrim($contents));

        $tail = array_slice($allLines, max(0, count($allLines) - $lines));

        $matched = array_values(array_filter($tail, function (string $line) use ($level, $search): bool {
            if ($level !== null && $level !== '' && ! preg_match('/\.'.preg_quote($level, '/').'\b/i', $line)) {
                return false;
            }

            if ($search !== null && $search !== '' && stripos($line, (string) $search) === false) {
                return false;
            }

            return true;
        }));

        if (empty($matched)) {
            return 'No matching log lines found.';
        }

        $output = '=== Recent log lines ('.count($matched)." matched) ===\n\n";
        $output .= implode("\n", $matched);

        if (strlen($output) > 6000) {
            $output = Str::limit($output, 6000)."\n\n[... output truncated ...]";
        }

        return $output;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'lines' => $schema->integer()
                ->description('Number of recent lines to return (default: 50, max: 200)')
                ->default(50)
                ->max(200),
            'level' => $schema->string()
                ->description('Filter to a log level: error, warning, info, or debug')
                ->enum(['error', 'warning', 'info', 'debug']),
            'search' => $schema->string()
                ->description('Case-insensitive substring to filter lines by'),
        ];
    }
}
