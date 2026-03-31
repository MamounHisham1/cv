<?php

namespace App\Services;

use App\Models\CreditBalance;
use App\Models\CreditTransaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Ai\Responses\Data\Usage;

class CreditManager
{
    public function getBalance(User $user): int
    {
        return $this->getOrCreateBalance($user)->balance;
    }

    public function hasCredits(User $user): bool
    {
        return $this->getBalance($user) > 0;
    }

    public function add(User $user, int $credits, string $type, array $metadata = []): CreditTransaction
    {
        return DB::transaction(function () use ($user, $credits, $type, $metadata) {
            $balance = CreditBalance::where('user_id', $user->id)->lockForUpdate()->first();

            if (! $balance) {
                $balance = $this->createBalance($user);
            }

            $balance->increment('balance', $credits);

            return CreditTransaction::create([
                'user_id' => $user->id,
                'amount' => $credits,
                'type' => $type,
                'metadata' => ! empty($metadata) ? $metadata : null,
            ]);
        });
    }

    public function deduct(User $user, int $credits, string $type, ?object $reference = null, array $metadata = []): CreditTransaction
    {
        return DB::transaction(function () use ($user, $credits, $type, $reference, $metadata) {
            $balance = CreditBalance::where('user_id', $user->id)->lockForUpdate()->first();

            if (! $balance) {
                $balance = $this->createBalance($user);
            }

            $actualDeduction = min($credits, $balance->balance);
            $balance->decrement('balance', $actualDeduction);

            $transactionData = [
                'user_id' => $user->id,
                'amount' => -$actualDeduction,
                'type' => $type,
                'metadata' => ! empty($metadata) ? $metadata : null,
            ];

            if ($reference) {
                $transactionData['reference_type'] = $reference::class;
                $transactionData['reference_id'] = $reference->id;
            }

            return CreditTransaction::create($transactionData);
        });
    }

    public function calculateFromUsage(Usage $usage, string $operationType = 'ai_evaluation'): int
    {
        $totalTokens = $usage->promptTokens + $usage->completionTokens;

        if ($totalTokens === 0) {
            $minimum = config("credits.minimum_charge.{$operationType}", 1);

            Log::warning("Token usage returned 0 for {$operationType}, charging minimum: {$minimum}");

            return $minimum;
        }

        $tokenUnit = config('credits.token_unit', 1000);
        $tokenRate = config('credits.token_rate', 1);

        return (int) ceil($totalTokens / $tokenUnit) * $tokenRate;
    }

    public function canPerformOperation(User $user, string $operation): bool
    {
        if ($operation === 'ai_builder_message') {
            $remaining = $this->getFreeBuilderMessagesRemaining($user);

            if ($remaining > 0) {
                return true;
            }

            return $this->hasCredits($user);
        }

        return $this->hasCredits($user);
    }

    public function getFreeBuilderMessagesRemaining(User $user, ?string $conversationId = null): int
    {
        if ($conversationId === null) {
            return $this->getPlanFreeBuilderMessages($user);
        }

        $messagesUsed = DB::table('agent_conversation_messages')
            ->where('conversation_id', $conversationId)
            ->where('user_id', $user->id)
            ->where('role', 'user')
            ->count();

        $freeCap = $this->getPlanFreeBuilderMessages($user);

        if ($freeCap === null) {
            return PHP_INT_MAX;
        }

        return max(0, $freeCap - $messagesUsed);
    }

    public function grantMonthlyCredits(User $user): CreditTransaction
    {
        return DB::transaction(function () use ($user) {
            $balance = CreditBalance::where('user_id', $user->id)->lockForUpdate()->first();

            if (! $balance) {
                $balance = $this->createBalance($user);
            }

            $planConfig = config("credits.plans.{$balance->plan}", config('credits.plans.free'));
            $monthlyCredits = $planConfig['monthly_credits'] ?? 0;

            $balance->update([
                'balance' => $monthlyCredits,
                'monthly_credits_reset_at' => now(),
            ]);

            return CreditTransaction::create([
                'user_id' => $user->id,
                'amount' => $monthlyCredits,
                'type' => 'monthly_grant',
                'metadata' => ['plan' => $balance->plan],
            ]);
        });
    }

    private function getOrCreateBalance(User $user): CreditBalance
    {
        return CreditBalance::firstOrCreate(
            ['user_id' => $user->id],
            [
                'balance' => 0,
                'plan' => 'free',
                'monthly_credits_reset_at' => now(),
            ]
        );
    }

    private function createBalance(User $user): CreditBalance
    {
        return CreditBalance::create([
            'user_id' => $user->id,
            'balance' => 0,
            'plan' => 'free',
            'monthly_credits_reset_at' => now(),
        ]);
    }

    private function getPlanFreeBuilderMessages(User $user): ?int
    {
        $balance = CreditBalance::where('user_id', $user->id)->first();

        if (! $balance) {
            return config('credits.plans.free.free_builder_messages', 5);
        }

        return config("credits.plans.{$balance->plan}.free_builder_messages", 5);
    }
}
