<?php

namespace App\Ai\Tools\Telegram;

use App\Models\User;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class GetUserStats implements Tool
{
    public function description(): Stringable|string
    {
        return 'Get statistics about registered users: total count, new users today / last 7 days / last 30 days, growth percentage vs previous period, and breakdown by signup method (Google OAuth vs password).';
    }

    public function handle(Request $request): Stringable|string
    {
        $days = (int) ($request['days'] ?? 30);

        $total = User::count();
        $newToday = User::where('created_at', '>=', now()->startOfDay())->count();
        $new7Days = User::where('created_at', '>=', now()->subDays(7))->count();

        $periodStart = now()->subDays($days);
        $previousStart = now()->subDays($days * 2);

        $currentPeriod = User::where('created_at', '>=', $periodStart)->count();
        $previousPeriod = User::whereBetween('created_at', [$previousStart, $periodStart])->count();

        $growth = $previousPeriod > 0
            ? round((($currentPeriod - $previousPeriod) / $previousPeriod) * 100, 1)
            : null;

        $googleUsers = User::whereNotNull('google_id')->count();
        $passwordUsers = User::whereNull('google_id')->count();

        $growthText = $growth === null
            ? 'N/A (no users in previous period)'
            : ($growth >= 0 ? '+'.$growth.'%' : $growth.'%');

        return <<<TEXT
=== User Statistics ===

Total registered users: {$total}
New today: {$newToday}
New in last 7 days: {$new7Days}
New in last {$days} days: {$currentPeriod}
Growth vs previous {$days} days: {$growthText}

Signup method breakdown:
- Google OAuth: {$googleUsers}
- Password (email): {$passwordUsers}
TEXT;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'days' => $schema->integer()
                ->description('Window in days for the growth calculation (default: 30)')
                ->default(30),
        ];
    }
}
