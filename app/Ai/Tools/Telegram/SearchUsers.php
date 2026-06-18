<?php

namespace App\Ai\Tools\Telegram;

use App\Models\User;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class SearchUsers implements Tool
{
    public function description(): Stringable|string
    {
        return "Search for users by email or name. Returns the matching user's profile and activity summary (CVs count, evaluations count, credits, signup method, join date).";
    }

    public function handle(Request $request): Stringable|string
    {
        $query = trim((string) ($request['query'] ?? ''));
        $limit = min((int) ($request['limit'] ?? 3), 10);

        if ($query === '') {
            return 'Error: Please provide a search query (email or name).';
        }

        $users = User::where('email', 'like', "%{$query}%")
            ->orWhere('name', 'like', "%{$query}%")
            ->withCount(['cvs', 'cvEvaluations'])
            ->with('creditBalance')
            ->take($limit)
            ->get();

        if ($users->isEmpty()) {
            return "No users found for '{$query}'.";
        }

        $output = "=== Users matching '{$query}' (".$users->count()." found) ===\n\n";

        foreach ($users as $user) {
            $signupMethod = $user->google_id !== null ? 'Google OAuth' : 'Password (email)';
            $credits = $user->creditBalance?->balance ?? 0;
            $plan = $user->creditBalance?->plan ?? 'none';
            $joined = $user->created_at?->format('Y-m-d H:i');

            $output .= "--- User #{$user->id} ---\n";
            $output .= "Name: {$user->name}\n";
            $output .= "Email: {$user->email}\n";
            $output .= "Signup method: {$signupMethod}\n";
            $output .= "Joined: {$joined}\n";
            $output .= "Plan: {$plan}\n";
            $output .= "Credits balance: {$credits}\n";
            $output .= "CVs: {$user->cvs_count}\n";
            $output .= "CV evaluations: {$user->cv_evaluations_count}\n\n";
        }

        return $output;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'query' => $schema->string()
                ->description('Email or name to search for')
                ->required(),
            'limit' => $schema->integer()
                ->description('Max number of users to return (default: 3, max: 10)')
                ->default(3)
                ->max(10),
        ];
    }
}
