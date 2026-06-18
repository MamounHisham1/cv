<?php

namespace App\Ai\Tools\Telegram;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\DB;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;
use Throwable;

class GetSystemHealth implements Tool
{
    public function description(): Stringable|string
    {
        return 'Get system health: queue size (pending jobs), failed jobs count, cache driver, database connection status.';
    }

    public function handle(Request $request): Stringable|string
    {
        $pendingJobs = DB::table('jobs')->count();
        $failedJobs = DB::table('failed_jobs')->count();
        $cacheDriver = (string) config('cache.default');

        $dbStatus = 'OK';
        try {
            DB::select('SELECT 1');
        } catch (Throwable $e) {
            $dbStatus = 'ERROR: '.$e->getMessage();
        }

        return <<<TEXT
=== System Health ===

Pending queue jobs: {$pendingJobs}
Failed jobs: {$failedJobs}
Cache driver: {$cacheDriver}
Database connection: {$dbStatus}
TEXT;
    }

    public function schema(JsonSchema $schema): array
    {
        return [];
    }
}
