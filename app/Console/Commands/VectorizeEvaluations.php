<?php

namespace App\Console\Commands;

use App\Models\CvEvaluation;
use App\Services\EvaluationVectorStore;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Command;

#[Description('Backfill existing CV evaluations into Qdrant vector store')]
class VectorizeEvaluations extends Command
{
    protected $signature = 'evaluations:vectorize
        {--batch=5 : Number of evaluations to embed per batch}
        {--limit= : Max number of evaluations to process}
        {--dry-run : Show count without processing}';

    public function handle(EvaluationVectorStore $vectorStore): int
    {
        $query = CvEvaluation::orderBy('created_at');

        $limit = $this->option('limit');
        if ($limit) {
            $query->limit((int) $limit);
        }

        $evaluations = $query->get();
        $count = $evaluations->count();

        if ($this->option('dry-run')) {
            $this->components->info("{$count} evaluation(s) would be vectorized.");

            return self::SUCCESS;
        }

        if ($count === 0) {
            $this->components->info('No evaluations to vectorize.');

            return self::SUCCESS;
        }

        $vectorStore->ensureCollectionExists();
        $this->components->info('Qdrant collection ready.');

        $progressBar = $this->output->createProgressBar($count);
        $progressBar->start();

        $imported = 0;
        $errors = 0;

        foreach ($evaluations as $evaluation) {
            try {
                $embedding = $vectorStore->generateEmbedding($evaluation->cv_text ?? '');
                $vectorStore->store(
                    "eval_{$evaluation->id}",
                    $evaluation->user_id,
                    $evaluation->grade,
                    $evaluation->overall_score,
                    $evaluation->cv_text ?? '',
                    $embedding,
                );
                $imported++;
            } catch (\Throwable $e) {
                $this->warn("Failed to vectorize evaluation {$evaluation->id}: {$e->getMessage()}");
                $errors++;
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();

        $this->table(
            ['Metric', 'Value'],
            [
                ['Imported', $imported],
                ['Errors', $errors],
            ]
        );

        return self::SUCCESS;
    }
}
