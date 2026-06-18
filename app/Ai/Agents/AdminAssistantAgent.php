<?php

namespace App\Ai\Agents;

use App\Ai\Tools\Telegram\GetCvStats;
use App\Ai\Tools\Telegram\GetInterviewStats;
use App\Ai\Tools\Telegram\GetReferralStats;
use App\Ai\Tools\Telegram\GetRevenueStats;
use App\Ai\Tools\Telegram\GetSystemHealth;
use App\Ai\Tools\Telegram\GetUserStats;
use App\Ai\Tools\Telegram\ReadApplicationLogs;
use App\Ai\Tools\Telegram\SearchUsers;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Attributes\Temperature;
use Laravel\Ai\Attributes\Timeout;
use Laravel\Ai\Concerns\RemembersConversations;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Promptable;
use Stringable;

#[Provider(Lab::Ollama)]
#[Temperature(0.3)]
#[Timeout(120)]
class AdminAssistantAgent implements Agent, Conversational, HasTools
{
    use Promptable, RemembersConversations;

    public function instructions(): Stringable|string
    {
        $now = now()->toDateTimeString();

        return <<<INSTRUCTIONS
You are the admin assistant for a CV-builder SaaS application, accessible via Telegram. The admin (app owner) messages you and you answer using tools.

Today is {$now}.

Be concise and data-driven — admins are busy. Use tools to get accurate numbers; never guess. When asked about users, revenue, CVs, interviews, referrals, system health, or errors, call the relevant tool. Format responses clearly: short bullet points and bold headers when useful.

Available tools:
- read_application_logs: tail/filter/search the application log file to investigate errors or recent activity.
- get_user_stats: registered user counts, growth vs previous period, and signup-method breakdown.
- get_cv_stats: CV totals by status plus evaluation counts, grade distribution, and average score.
- get_interview_stats: interview session totals, completion rate, and average evaluation score.
- get_revenue_stats: confirmed vfcash revenue (EGP), credits granted, transactions by type, and plan distribution.
- get_referral_stats: referral totals, rewards granted, and conversion rate.
- search_users: find a specific user by email or name with their activity summary.
- get_system_health: pending/failed jobs, cache driver, and database connection status.
INSTRUCTIONS;
    }

    /**
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [
            new ReadApplicationLogs,
            new GetUserStats,
            new GetCvStats,
            new GetInterviewStats,
            new GetRevenueStats,
            new GetReferralStats,
            new SearchUsers,
            new GetSystemHealth,
        ];
    }
}
