<?php

namespace App\Console\Commands;

use App\Models\CreditBalance;
use App\Services\CreditManager;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:fix-monthly-credits')]
#[Description('One-time fix: top up all users below their plan monthly credits and send email notifications')]
class FixMonthlyCredits extends Command
{
    public function handle(CreditManager $creditManager): int
    {
        if (! $this->confirm('This will top up all users below their plan monthly credit limit and send emails. Continue?')) {
            return self::SUCCESS;
        }

        $balances = CreditBalance::all();
        $toppedUp = 0;
        $skipped = 0;

        foreach ($balances as $balance) {
            $planConfig = config("credits.plans.{$balance->plan}", config('credits.plans.free'));
            $monthlyCredits = $planConfig['monthly_credits'] ?? 0;

            if ($balance->balance < $monthlyCredits) {
                $creditManager->grantMonthlyCredits($balance->user);
                $toppedUp++;
                $this->info("Topped up user #{$balance->user_id}: {$balance->balance} -> ".($balance->fresh()->balance).' credits');
            } else {
                $skipped++;
                $this->line("Skipped user #{$balance->user_id}: already has {$balance->balance} credits (>= {$monthlyCredits})");
            }
        }

        $this->newLine();
        $this->info("Done. Topped up: {$toppedUp}, Skipped: {$skipped}");

        return self::SUCCESS;
    }
}
