<?php

namespace App\Services;

use App\Exceptions\InsufficientCreditsException;
use App\Models\CreditBalance;
use App\Models\CreditTransaction;
use App\Models\User;
use App\Notifications\CreditsGrantedNotification;
use App\Notifications\CreditsLowNotification;
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

            $transaction = CreditTransaction::create([
                'user_id' => $user->id,
                'amount' => $credits,
                'type' => $type,
                'metadata' => ! empty($metadata) ? $metadata : null,
            ]);

            if ($type === 'admin_grant' || $type === 'admin_adjustment') {
                $newBalance = $balance->fresh()->balance;
                $reason = $metadata['reason'] ?? 'Admin action';
                $user->notify(new CreditsGrantedNotification($credits, $reason, $newBalance));
            }

            return $transaction;
        });
    }

    public function deduct(User $user, int $credits, string $type, ?object $reference = null, array $metadata = []): CreditTransaction
    {
        $transaction = DB::transaction(function () use ($user, $credits, $type, $reference, $metadata) {
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

        $this->checkAndNotifyLowBalance($user);

        return $transaction;
    }

    /**
     * Reserve credits before an operation. Returns the reservation ID.
     * The credits are deducted immediately and held as "reserved".
     */
    public function reserve(User $user, int $amount, string $operationType, ?object $reference = null, array $metadata = []): CreditTransaction
    {
        return DB::transaction(function () use ($user, $amount, $operationType, $reference, $metadata) {
            $balance = CreditBalance::where('user_id', $user->id)->lockForUpdate()->first();

            if (! $balance) {
                $balance = $this->createBalance($user);
            }

            if ($balance->balance < $amount) {
                throw new InsufficientCreditsException(
                    "Not enough credits to reserve {$amount} for {$operationType}. Balance: {$balance->balance}"
                );
            }

            $balance->decrement('balance', $amount);

            $transactionData = [
                'user_id' => $user->id,
                'amount' => -$amount,
                'type' => 'reservation',
                'metadata' => array_merge($metadata, [
                    'operation_type' => $operationType,
                    'status' => 'reserved',
                ]),
            ];

            if ($reference) {
                $transactionData['reference_type'] = $reference::class;
                $transactionData['reference_id'] = $reference->id;
            }

            return CreditTransaction::create($transactionData);
        });
    }

    /**
     * Settle a reservation — charge the actual amount and refund any excess.
     * The reservation transaction's metadata status changes to 'settled'.
     */
    public function settle(User $user, int $reservationId, int $actualAmount): void
    {
        DB::transaction(function () use ($user, $reservationId, $actualAmount) {
            $reservation = CreditTransaction::where('id', $reservationId)
                ->where('user_id', $user->id)
                ->where('type', 'reservation')
                ->lockForUpdate()
                ->first();

            if (! $reservation) {
                return;
            }

            $reservedAmount = abs($reservation->amount);
            $diff = $reservedAmount - $actualAmount;

            // Update reservation status to settled
            $metadata = $reservation->metadata ?? [];
            $metadata['status'] = 'settled';
            $metadata['actual_charge'] = $actualAmount;
            $reservation->update(['metadata' => $metadata]);

            // Refund excess if actual cost was less than reserved
            if ($diff > 0) {
                $balance = CreditBalance::where('user_id', $user->id)->lockForUpdate()->first();
                $balance->increment('balance', $diff);

                CreditTransaction::create([
                    'user_id' => $user->id,
                    'amount' => $diff,
                    'type' => 'refund',
                    'metadata' => [
                        'reservation_id' => $reservationId,
                        'operation_type' => $metadata['operation_type'] ?? 'unknown',
                        'reason' => 'Excess reservation refunded after settle',
                        'reserved' => $reservedAmount,
                        'actual' => $actualAmount,
                    ],
                ]);
            }
        });
    }

    /**
     * Cancel a reservation — refund all reserved credits.
     * The reservation transaction's metadata status changes to 'cancelled'.
     */
    public function cancelReservation(User $user, int $reservationId): void
    {
        DB::transaction(function () use ($user, $reservationId) {
            $reservation = CreditTransaction::where('id', $reservationId)
                ->where('user_id', $user->id)
                ->where('type', 'reservation')
                ->lockForUpdate()
                ->first();

            if (! $reservation) {
                return;
            }

            $metadata = $reservation->metadata ?? [];
            if (($metadata['status'] ?? '') === 'settled' || ($metadata['status'] ?? '') === 'cancelled') {
                return; // Already settled or cancelled
            }

            $reservedAmount = abs($reservation->amount);
            $balance = CreditBalance::where('user_id', $user->id)->lockForUpdate()->first();
            $balance->increment('balance', $reservedAmount);

            $metadata['status'] = 'cancelled';
            $reservation->update(['metadata' => $metadata]);

            CreditTransaction::create([
                'user_id' => $user->id,
                'amount' => $reservedAmount,
                'type' => 'refund',
                'metadata' => [
                    'reservation_id' => $reservationId,
                    'operation_type' => $metadata['operation_type'] ?? 'unknown',
                    'reason' => 'Reservation cancelled',
                ],
            ]);
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

    /**
     * Fast pre-check whether a user can perform a credit-gated operation.
     *
     * For 'ai_builder_message', this checks the plan's free message cap
     * without a conversation ID — a fast but slightly generous estimate.
     * The accurate per-conversation check should be done in the calling code
     * (e.g., CvAiChat::getAiResponse()).
     */
    public function canPerformOperation(User $user, string $operation): bool
    {
        if ($operation === 'ai_builder_message') {
            $remaining = $this->getFreeBuilderMessagesRemaining($user);

            if ($remaining > 0) {
                return true;
            }

            return $this->hasCredits($user);
        }

        $minimumCharge = config("credits.minimum_charge.{$operation}", 1);

        return $this->getBalance($user) >= $minimumCharge;
    }

    /**
     * Check if balance is low and dispatch notification if so.
     * Should be called after a deduction.
     */
    public function checkAndNotifyLowBalance(User $user): void
    {
        $balance = $this->getBalance($user);
        $threshold = config('credits.low_balance_threshold', 5);

        if ($balance > 0 && $balance <= $threshold) {
            $recentNotification = CreditTransaction::where('user_id', $user->id)
                ->where('type', 'low_balance_notification')
                ->where('created_at', '>', now()->subDay())
                ->exists();

            if (! $recentNotification) {
                $user->notify(new CreditsLowNotification($balance, $threshold));

                CreditTransaction::create([
                    'user_id' => $user->id,
                    'amount' => 0,
                    'type' => 'low_balance_notification',
                    'metadata' => ['balance' => $balance, 'threshold' => $threshold],
                ]);
            }
        }
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

            $creditsToGrant = max(0, $monthlyCredits - $balance->balance);

            if ($creditsToGrant === 0) {
                $balance->update([
                    'monthly_credits_reset_at' => now(),
                ]);

                return CreditTransaction::create([
                    'user_id' => $user->id,
                    'amount' => 0,
                    'type' => 'monthly_grant',
                    'metadata' => ['plan' => $balance->plan, 'skipped' => true, 'reason' => 'Balance already at or above monthly limit'],
                ]);
            }

            $balance->increment('balance', $creditsToGrant);
            $balance->update(['monthly_credits_reset_at' => now()]);

            $transaction = CreditTransaction::create([
                'user_id' => $user->id,
                'amount' => $creditsToGrant,
                'type' => 'monthly_grant',
                'metadata' => ['plan' => $balance->plan, 'previous_balance' => $balance->balance - $creditsToGrant],
            ]);

            $newBalance = $balance->fresh()->balance;
            $user->notify(new CreditsGrantedNotification($creditsToGrant, 'Monthly free credits', $newBalance));

            return $transaction;
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

    /**
     * Check if the user can start a new interview.
     *
     * @return array{allowed: bool, is_free_trial: bool, reason: string}
     */
    public function canStartInterview(User $user): array
    {
        $balance = CreditBalance::where('user_id', $user->id)->first();

        if (! $balance) {
            return ['allowed' => true, 'is_free_trial' => true, 'reason' => ''];
        }

        if (! $balance->free_trial_interview_used) {
            return ['allowed' => true, 'is_free_trial' => true, 'reason' => ''];
        }

        if ($balance->hasPaidSubscription()) {
            if ($balance->balance >= config('credits.minimum_charge.ai_interview', 3)) {
                return ['allowed' => true, 'is_free_trial' => false, 'reason' => ''];
            }

            return ['allowed' => false, 'is_free_trial' => false, 'reason' => 'Not enough credits. You need at least '.config('credits.minimum_charge.ai_interview', 3).' credits.'];
        }

        return ['allowed' => false, 'is_free_trial' => false, 'reason' => 'Your free interview has been used. Upgrade to Pro or Enterprise to continue practicing interviews.'];
    }

    /**
     * Mark the user's free trial interview as used.
     */
    public function markFreeTrialUsed(User $user): void
    {
        $balance = CreditBalance::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0, 'plan' => 'free', 'monthly_credits_reset_at' => now()]
        );

        $balance->update(['free_trial_interview_used' => true]);
    }
}
