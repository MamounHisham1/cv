<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

#[Signature('accounts:purge-deleted {--days=30 : Grace period before permanent deletion}')]
#[Description('Permanently delete user accounts that were soft-deleted more than --days ago. Honours the Privacy Policy\'s 30-day retention window.')]
class PurgeDeletedAccounts extends Command
{
    public function handle(): int
    {
        $days = (int) $this->option('days');
        $cutoff = now()->subDays($days);

        // Only fetch IDs — forceDelete() will cascade through FKs without
        // holding all models in memory.
        $staleIds = User::onlyTrashed()
            ->where('deleted_at', '<', $cutoff)
            ->limit(500)
            ->pluck('id')
            ->all();

        if (empty($staleIds)) {
            $this->info('No accounts due for permanent deletion.');

            return self::SUCCESS;
        }

        $purged = 0;
        foreach ($staleIds as $id) {
            // withTrashed() so forceDelete() finds the row.
            $user = User::withTrashed()->find($id);
            if (! $user) {
                continue;
            }

            try {
                $user->forceDelete();
                $purged++;
            } catch (\Throwable $e) {
                Log::error('PurgeDeletedAccounts: failed to purge user', [
                    'user_id' => $id,
                    'message' => $e->getMessage(),
                ]);
                $this->error("Failed to purge user {$id}: {$e->getMessage()}");
            }
        }

        $this->info("Permanently deleted {$purged} account(s) (and their associated data).");

        return self::SUCCESS;
    }
}
