<?php

namespace App\Livewire\Concerns;

use App\Exceptions\InsufficientCreditsException;
use App\Models\CreditTransaction;
use App\Services\CreditManager;
use Illuminate\Support\Facades\Auth;
use Laravel\Ai\Responses\Data\Usage;

/**
 * Provides standardized credit checking and deduction methods for Livewire components.
 *
 * Usage:
 *   use RequiresCredits;
 *
 *   // Check if user can perform an operation
 *   if (!$this->canAffordOperation('ai_evaluation')) {
 *       $this->addError('credits', 'Not enough credits.');
 *       return;
 *   }
 *
 *   // Reserve credits before AI call
 *   try {
 *       $reservation = $this->reserveCredits(1, 'ai_evaluation');
 *       $response = $agent->prompt($prompt);
 *       $actual = $creditManager->calculateFromUsage($response->usage, 'ai_evaluation');
 *       $creditManager->settle(Auth::user(), $reservation->id, $actual);
 *   } catch (InsufficientCreditsException $e) {
 *       $this->addError('credits', $e->getMessage());
 *       return;
 *   } catch (\Throwable $e) {
 *       $creditManager->cancelReservation(Auth::user(), $reservation->id);
 *       throw $e;
 *   }
 */
trait RequiresCredits
{
    /**
     * Check if the current user can afford an operation based on minimum charge.
     */
    protected function canAffordOperation(string $operation): bool
    {
        return app(CreditManager::class)->canPerformOperation(Auth::user(), $operation);
    }

    /**
     * Check if the current user has any credits.
     */
    protected function hasAnyCredits(): bool
    {
        return app(CreditManager::class)->hasCredits(Auth::user());
    }

    /**
     * Get the current user's credit balance.
     */
    protected function creditBalance(): int
    {
        return app(CreditManager::class)->getBalance(Auth::user());
    }

    /**
     * Get the minimum charge for a specific operation type.
     */
    protected function minimumChargeFor(string $operation): int
    {
        return config("credits.minimum_charge.{$operation}", 1);
    }

    /**
     * Reserve credits for an operation. Throws InsufficientCreditsException if insufficient.
     *
     * @return CreditTransaction The reservation transaction (use ->id as reservation ID)
     */
    protected function reserveCredits(int $amount, string $operationType, ?object $reference = null, array $metadata = []): CreditTransaction
    {
        return app(CreditManager::class)->reserve(
            Auth::user(),
            $amount,
            $operationType,
            $reference,
            $metadata
        );
    }

    /**
     * Settle a reservation — charge actual amount and refund excess.
     */
    protected function settleReservation(int $reservationId, int $actualAmount): void
    {
        app(CreditManager::class)->settle(Auth::user(), $reservationId, $actualAmount);
    }

    /**
     * Cancel a reservation — refund all reserved credits.
     */
    protected function cancelReservation(int $reservationId): void
    {
        app(CreditManager::class)->cancelReservation(Auth::user(), $reservationId);
    }

    /**
     * Dispatch the insufficient-credits event to the frontend.
     */
    protected function dispatchInsufficientCredits(?string $message = null): void
    {
        $this->dispatch('insufficient-credits');
        $this->addError('credits', $message ?? "You're out of credits. Invite friends to earn more!");
    }

    /**
     * Calculate credits from AI usage response.
     */
    protected function calculateCreditsFromUsage(Usage $usage, string $operationType = 'ai_evaluation'): int
    {
        return app(CreditManager::class)->calculateFromUsage($usage, $operationType);
    }

    /**
     * Get free builder messages remaining for the current user.
     */
    protected function freeBuilderMessagesRemaining(?string $conversationId = null): int
    {
        return app(CreditManager::class)->getFreeBuilderMessagesRemaining(Auth::user(), $conversationId);
    }
}
