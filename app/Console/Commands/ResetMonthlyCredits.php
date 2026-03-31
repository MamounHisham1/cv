<?php

namespace App\Console\Commands;

use App\Models\CreditBalance;
use App\Services\CreditManager;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('credits:reset-monthly')]
#[Description('Reset monthly credits for users whose renewal period has elapsed')]
class ResetMonthlyCredits extends Command
{
    public function handle(CreditManager $creditManager): int
    {
        $threshold = now()->subDays(30);

        $balances = CreditBalance::where('monthly_credits_reset_at', '<=', $threshold)->get();

        if ($balances->isEmpty()) {
            $this->info('No users require monthly credit reset.');

            return self::SUCCESS;
        }

        $count = 0;

        foreach ($balances as $balance) {
            $creditManager->grantMonthlyCredits($balance->user);
            $count++;
        }

        $this->info("Reset monthly credits for {$count} user(s).");

        return self::SUCCESS;
    }
}
