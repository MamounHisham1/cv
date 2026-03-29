<?php

namespace App\Console\Commands;

use App\Models\ResumeSample;
use App\Services\ResumeHtmlParser;
use App\Services\ResumeVectorStore;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Command;
use Laravel\Ai\Embeddings;
use Laravel\Ai\Enums\Lab;

#[Description('Import resume samples from CSV/JSONL into Qdrant vector store')]
class ImportResumes extends Command
{
    protected $signature = 'app:import-resumes
        {source : Dataset to import (resume_csv, master_jsonl, decisions_csv, all)}
        {--batch=5 : Number of resumes to embed per API call}
        {--limit= : Max number of resumes to import}
        {--role= : Only import resumes matching this role}';

    private const EMBEDDING_MAX_CHARS = 1500;

    private const MAX_RETRIES = 3;

    public function handle(ResumeVectorStore $vectorStore, ResumeHtmlParser $parser): int
    {
        $source = $this->argument('source');
        $validSources = ['resume_csv', 'master_jsonl', 'decisions_csv', 'all'];

        if (! in_array($source, $validSources)) {
            $this->error('Invalid source. Choose from: '.implode(', ', $validSources));

            return self::FAILURE;
        }

        $vectorStore->ensureCollectionExists();
        $this->components->info('Qdrant collection ready.');

        $importers = match ($source) {
            'resume_csv' => ['resume_csv' => $this->importResumeCsv(...)],
            'master_jsonl' => ['master_jsonl' => $this->importMasterJsonl(...)],
            'decisions_csv' => ['decisions_csv' => $this->importDecisionsCsv(...)],
            'all' => [
                'resume_csv' => $this->importResumeCsv(...),
                'master_jsonl' => $this->importMasterJsonl(...),
                'decisions_csv' => $this->importDecisionsCsv(...),
            ],
        };

        $totalImported = 0;
        $totalErrors = 0;
        $totalTime = 0;

        foreach ($importers as $name => $importer) {
            $result = $importer($vectorStore, $parser);
            $totalImported += $result['imported'];
            $totalErrors += $result['errors'];
            $totalTime += $result['time'];
        }

        $this->newLine();
        $this->components->info('All imports complete!');
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total imported', $totalImported],
                ['Total errors', $totalErrors],
                ['Total time', round($totalTime, 1).'s'],
                ['Overall rate', $totalTime > 0 ? round($totalImported / $totalTime, 2).' resumes/s' : '-'],
            ]
        );

        if ($totalErrors > 0) {
            $this->warn("{$totalErrors} resumes failed. Re-run to retry.");
        }

        return self::SUCCESS;
    }

    private function importResumeCsv(ResumeVectorStore $vectorStore, ResumeHtmlParser $parser): array
    {
        $this->newLine();
        $this->components->info('Importing resume_csv (Resume/Resume.csv)...');

        $csvPath = base_path('resume data/Resume/Resume.csv');
        if (! file_exists($csvPath)) {
            $this->warn("Skipping: {$csvPath} not found.");

            return ['imported' => 0, 'errors' => 0, 'time' => 0];
        }

        $handle = fopen($csvPath, 'r');
        $headers = fgetcsv($handle, escape: '');
        $idCol = array_search('ID', $headers);
        $htmlCol = array_search('Resume_html', $headers);
        $categoryCol = array_search('Category', $headers);

        return $this->processSource(
            'resume_csv',
            (function () use ($handle, $idCol, $htmlCol, $categoryCol, $parser) {
                while (($row = fgetcsv($handle, escape: '')) !== false) {
                    $id = (string) ($row[$idCol] ?? '');
                    $html = $row[$htmlCol] ?? '';
                    $category = $row[$categoryCol] ?? '';

                    if (empty(trim($category))) {
                        continue;
                    }

                    $text = $parser->toEmbeddingText($html, $category);

                    yield [
                        'id' => "resume_csv_{$id}",
                        'source' => 'resume_csv',
                        'role' => $category,
                        'content' => $text,
                    ];
                }
                fclose($handle);
            })(),
            $vectorStore
        );
    }

    private function importMasterJsonl(ResumeVectorStore $vectorStore, ResumeHtmlParser $parser): array
    {
        $this->newLine();
        $this->components->info('Importing master_jsonl (master_resumes.jsonl)...');

        $filePath = base_path('master_resumes.jsonl');
        if (! file_exists($filePath)) {
            $this->warn("Skipping: {$filePath} not found.");

            return ['imported' => 0, 'errors' => 0, 'time' => 0];
        }

        $handle = fopen($filePath, 'r');

        return $this->processSource(
            'master_jsonl',
            (function () use ($handle) {
                $lineNum = 0;
                while (($line = fgets($handle)) !== false) {
                    $lineNum++;
                    $data = json_decode($line, true);
                    if (! $data) {
                        continue;
                    }

                    $text = $this->buildTextFromJson($data);

                    yield [
                        'id' => "master_{$lineNum}",
                        'source' => 'master_jsonl',
                        'role' => $this->extractRoleFromJson($data),
                        'content' => $text,
                        'structured_data' => $data,
                    ];
                }
                fclose($handle);
            })(),
            $vectorStore
        );
    }

    private function importDecisionsCsv(ResumeVectorStore $vectorStore, ResumeHtmlParser $parser): array
    {
        $this->newLine();
        $this->components->info('Importing decisions_csv (dataset.csv)...');

        $filePath = base_path('dataset.csv');
        if (! file_exists($filePath)) {
            $this->warn("Skipping: {$filePath} not found.");

            return ['imported' => 0, 'errors' => 0, 'time' => 0];
        }

        $handle = fopen($filePath, 'r');
        $headers = fgetcsv($handle, escape: '');
        $roleCol = array_search('Role', $headers);
        $resumeCol = array_search('Resume', $headers);
        $decisionCol = array_search('Decision', $headers);
        $reasonCol = array_search('Reason_for_decision', $headers);
        $jdCol = array_search('Job_Description', $headers);

        return $this->processSource(
            'decisions_csv',
            (function () use ($handle, $roleCol, $resumeCol, $decisionCol, $reasonCol, $jdCol) {
                $rowNum = 0;
                while (($row = fgetcsv($handle, escape: '')) !== false) {
                    $rowNum++;
                    $role = $row[$roleCol] ?? '';
                    $resume = $row[$resumeCol] ?? '';
                    $decision = $row[$decisionCol] ?? '';
                    $reason = $row[$reasonCol] ?? '';
                    $jd = $row[$jdCol] ?? '';

                    if (empty(trim($resume)) || empty(trim($role))) {
                        continue;
                    }

                    yield [
                        'id' => "decision_{$rowNum}",
                        'source' => 'decisions_csv',
                        'role' => $role,
                        'content' => "Role: {$role}\n\n{$resume}",
                        'decision' => $decision,
                        'reason' => $reason,
                        'job_description' => $jd,
                    ];
                }
                fclose($handle);
            })(),
            $vectorStore
        );
    }

    private function processSource(string $sourceName, iterable $items, ResumeVectorStore $vectorStore): array
    {
        $existingIds = ResumeSample::pluck('external_id')
            ->map(fn ($id) => (string) $id)
            ->flip()
            ->toArray();

        $rows = [];
        $skipped = 0;
        $roleFilter = $this->option('role');
        $limit = $this->option('limit') ? (int) $this->option('limit') : null;

        foreach ($items as $item) {
            if ($roleFilter && strtolower($item['role']) !== strtolower($roleFilter)) {
                continue;
            }
            if (isset($existingIds[$item['id']])) {
                $skipped++;

                continue;
            }

            $rows[] = $item;
            if ($limit && count($rows) >= $limit) {
                break;
            }
        }

        $toProcess = count($rows);
        if ($toProcess === 0) {
            $this->components->twoColumnDetail($sourceName, 'No new resumes');
            $this->components->twoColumnDetail('Skipped', $skipped);

            return ['imported' => 0, 'errors' => 0, 'time' => 0];
        }

        $batchSize = (int) $this->option('batch');
        $imported = 0;
        $errors = 0;
        $startTime = microtime(true);

        $this->components->twoColumnDetail($sourceName, "{$toProcess} to import");
        $this->components->twoColumnDetail('Already imported', $skipped);
        $this->newLine();

        $progressBar = $this->output->createProgressBar($toProcess);
        $progressBar->start();

        $batches = array_chunk($rows, $batchSize);

        foreach ($batches as $batch) {
            $count = $this->processBatch($batch, $vectorStore);

            if ($count > 0) {
                $imported += $count;
            } else {
                $errors += count($batch);
            }

            $progressBar->advance(count($batch));
        }

        $progressBar->finish();
        $this->newLine();

        $elapsed = microtime(true) - $startTime;

        $this->table(
            ['Metric', 'Value'],
            [
                ["{$sourceName} imported", $imported],
                ["{$sourceName} errors", $errors],
                ['Time', round($elapsed, 1).'s'],
            ]
        );

        return ['imported' => $imported, 'errors' => $errors, 'time' => $elapsed];
    }

    private function processBatch(array $batch, ResumeVectorStore $vectorStore): int
    {
        $items = [];

        foreach ($batch as $item) {
            $text = mb_substr($item['content'], 0, self::EMBEDDING_MAX_CHARS);
            $response = $this->retryEmbedding([$text]);

            if (! $response) {
                continue;
            }

            $embedding = $response->embeddings[0] ?? null;
            if (! $embedding) {
                continue;
            }

            $items[] = [
                'id' => $item['id'],
                'content' => $item['content'],
                'role' => $item['role'],
                'embedding' => $embedding,
            ];

            ResumeSample::firstOrCreate(
                ['external_id' => $item['id']],
                [
                    'source' => $item['source'] ?? null,
                    'role' => $item['role'],
                    'content' => mb_substr($item['content'], 0, 8000),
                    'decision' => $item['decision'] ?? null,
                    'reason' => $item['reason'] ?? null,
                    'job_description' => $item['job_description'] ?? null,
                    'structured_data' => $item['structured_data'] ?? null,
                ]
            );
        }

        if (! empty($items)) {
            $vectorStore->storeBatch($items);
        }

        return count($items);
    }

    private function retryEmbedding(array $texts): ?object
    {
        for ($attempt = 1; $attempt <= self::MAX_RETRIES; $attempt++) {
            try {
                return Embeddings::for($texts)
                    ->generate(Lab::Ollama, 'mxbai-embed-large');
            } catch (\Throwable $e) {
                $msg = $e->getMessage();
                if ($e->getPrevious()) {
                    $msg = $e->getPrevious()->getMessage();
                }

                $this->newLine();
                $this->warn("Embedding attempt {$attempt}/".self::MAX_RETRIES." failed: {$msg}");

                if (str_contains($msg, 'exceeds the context length')) {
                    $this->warn('Content too long, skipping.');

                    return null;
                }

                if ($attempt < self::MAX_RETRIES) {
                    $wait = $attempt * 5;
                    $this->components->twoColumnDetail('Retrying in', "{$wait}s...");
                    sleep($wait);
                } else {
                    return null;
                }
            }
        }

        return null;
    }

    private function buildTextFromJson(array $data): string
    {
        $parts = [];

        $info = $data['personal_info'] ?? [];
        if (! empty($info['summary'])) {
            $parts[] = "Summary: {$info['summary']}";
        }
        if (! empty($info['name']) && $info['name'] !== 'Unknown' && $info['name'] !== '') {
            $parts[] = "Name: {$info['name']}";
        }

        $experience = $data['experience'] ?? [];
        if (! empty($experience)) {
            $expParts = [];
            foreach (array_slice($experience, 0, 3) as $exp) {
                $entry = ($exp['title'] ?? 'Unknown').' at '.($exp['company'] ?? 'Unknown');
                if (! empty($exp['dates']['duration'])) {
                    $entry .= " ({$exp['dates']['duration']})";
                }
                if (! empty($exp['responsibilities'])) {
                    $entry .= ' - '.implode(', ', array_slice($exp['responsibilities'], 0, 3));
                }
                $expParts[] = $entry;
            }
            if (! empty($expParts)) {
                $parts[] = 'Experience: '.implode('; ', $expParts);
            }
        }

        $skills = $data['skills']['technical'] ?? [];
        $skillNames = [];
        foreach (['programming_languages', 'frameworks', 'databases', 'cloud'] as $cat) {
            foreach ($skills[$cat] ?? [] as $skill) {
                $skillNames[] = is_array($skill) ? ($skill['name'] ?? '') : $skill;
            }
        }
        if (! empty($skillNames)) {
            $parts[] = 'Skills: '.implode(', ', array_slice($skillNames, 0, 20));
        }

        $education = $data['education'] ?? [];
        if (! empty($education)) {
            $eduParts = [];
            foreach (array_slice($education, 0, 2) as $edu) {
                $entry = ($edu['degree']['level'] ?? '').' in '.($edu['degree']['field'] ?? '');
                if (! empty($edu['institution']['name'])) {
                    $entry .= ' from '.$edu['institution']['name'];
                }
                $eduParts[] = $entry;
            }
            if (! empty($eduParts)) {
                $parts[] = 'Education: '.implode('; ', $eduParts);
            }
        }

        return implode("\n\n", $parts);
    }

    private function extractRoleFromJson(array $data): string
    {
        $experience = $data['experience'] ?? [];

        if (! empty($experience)) {
            return $experience[0]['title'] ?? 'Unknown';
        }

        return 'Unknown';
    }
}
