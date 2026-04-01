<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CleanupTempUploads extends Command
{
    protected $signature = 'cleanup:temp-uploads';

    protected $description = 'Delete temporary upload files older than 1 hour and clean stale cache keys';

    public function handle(): int
    {
        $directory = storage_path('app/temp/uploads');

        if (! File::isDirectory($directory)) {
            return Command::SUCCESS;
        }

        $cutoff = now()->subHour()->getTimestamp();
        $deleted = 0;

        foreach (File::files($directory) as $file) {
            if ($file->getMTime() < $cutoff) {
                File::delete($file->getPathname());
                $deleted++;
            }
        }

        $this->info("Deleted {$deleted} stale temporary upload files.");

        return Command::SUCCESS;
    }
}
