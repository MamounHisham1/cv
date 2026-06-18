<?php

use App\Ai\Tools\Telegram\ReadApplicationLogs;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Laravel\Ai\Tools\Request;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

function appendTestLogLine(string $line): string
{
    $path = storage_path('logs/laravel.log');

    File::ensureDirectoryExists(dirname($path));
    File::append($path, $line."\n");

    return $path;
}

describe('ReadApplicationLogs Tool', function () {
    it('returns recent log lines', function () {
        $marker = 'TELEGRAM_LOG_TEST_'.uniqid();
        appendTestLogLine("[2024-01-01 00:00:00] test.ERROR: {$marker} something happened");

        $tool = new ReadApplicationLogs;
        $output = $tool->handle(new Request(['lines' => 5]));

        expect($output)->toBeString()
            ->and($output)->toContain($marker);
    });

    it('filters by search keyword', function () {
        $needle = 'TELEGRAM_LOG_NEEDLE_'.uniqid();
        $ignored = 'TELEGRAM_LOG_IGNORED_'.uniqid();

        appendTestLogLine("[2024-01-01 00:00:00] test.ERROR: matched {$needle}");
        appendTestLogLine("[2024-01-01 00:00:01] test.INFO: unrelated {$ignored}");

        $tool = new ReadApplicationLogs;
        $output = $tool->handle(new Request([
            'query' => $needle,
            'search' => $needle,
            'lines' => 100,
        ]));

        expect($output)->toBeString()
            ->and($output)->toContain($needle)
            ->and($output)->not->toContain($ignored);
    });
});
