<?php

namespace App\Livewire;

use App\Ai\Agents\CvEvaluatorAgent;
use App\Models\CvEvaluation;
use App\Services\CreditManager;
use App\Services\EvaluationVectorStore;
use App\Services\ResumeVectorStore;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\PdfToText\Pdf;
use thiagoalessio\TesseractOCR\TesseractOCR;

#[Layout('layouts.app')]
#[Title('AI CV Evaluator')]
class CvEvaluator extends Component
{
    use WithFileUploads;

    /** 'idle' | 'uploading' | 'evaluating' | 'complete' | 'error' */
    public string $evaluationState = 'idle';

    public ?object $uploadedFile = null;

    /** @var array<string, mixed>|null */
    public ?array $result = null;

    public ?string $errorMessage = null;

    /** Raw CV text extracted from the uploaded file. */
    public string $cvText = '';

    /** @var array<int, array<string, mixed>> */
    public array $evaluations = [];

    /** @var array<int, int> Max 2 selected evaluation IDs for comparison */
    public array $selectedEvaluationIds = [];

    /** @var array<string, mixed>|null Comparison diff result */
    public ?array $comparisonResult = null;

    /** Manual text-paste fallback. */
    public string $pastedText = '';

    public string $inputMode = 'upload'; // 'upload' | 'paste'

    /**
     * Load the most recent evaluation for the authenticated user.
     */
    public function mount(): void
    {
        if (! auth()->check()) {
            return;
        }

        $latestEvaluation = auth()->user()->cvEvaluations()->latest()->first();

        if ($latestEvaluation) {
            $this->result = [
                'overall_score' => $latestEvaluation->overall_score,
                'grade' => $latestEvaluation->grade,
                'summary' => $latestEvaluation->summary,
                'criteria' => $latestEvaluation->criteria,
                'top_strengths' => $latestEvaluation->top_strengths ?? [],
                'critical_improvements' => $latestEvaluation->critical_improvements ?? [],
            ];
            $this->evaluationState = 'complete';
        }

        $this->evaluations = auth()->check()
            ? auth()->user()->cvEvaluations()->latest()->get()->toArray()
            : [];
    }

    public function updatedUploadedFile(): void
    {
        $this->validate([
            'uploadedFile' => 'required|file|mimes:pdf,doc,docx,txt|max:5120',
        ]);

        $this->evaluationState = 'uploading';
    }

    public function evaluate(): void
    {
        $this->errorMessage = null;
        $this->result = null;

        if ($this->inputMode === 'upload') {
            $this->validate([
                'uploadedFile' => 'required|file|mimes:pdf,doc,docx,txt|max:5120',
            ]);

            $this->cvText = $this->extractTextFromFile();
        } else {
            $this->validate([
                'pastedText' => 'required|string|min:100',
            ]);

            $this->cvText = $this->pastedText;
        }

        if (empty(trim($this->cvText))) {
            $this->errorMessage = 'Could not extract text from the uploaded file. Please try pasting the text directly.';
            $this->evaluationState = 'error';

            return;
        }

        $this->evaluationState = 'evaluating';

        if (auth()->check() && ! app(CreditManager::class)->hasCredits(auth()->user())) {
            $this->errorMessage = "You're out of credits. Invite friends to earn more!";
            $this->evaluationState = 'error';
            $this->dispatch('insufficient-credits');

            return;
        }

        try {
            \Log::info('CvEvaluator: Starting RAG-powered evaluation', [
                'cv_text_length' => strlen($this->cvText),
                'cv_preview' => mb_substr($this->cvText, 0, 200),
            ]);

            // Step 1: Search for reference resumes and past evaluations (RAG context)
            $ragContext = $this->gatherRagContext($this->cvText);

            // Step 2: Build the prompt with injected RAG context
            $prompt = $this->buildEvaluationPrompt($this->cvText, $ragContext);

            \Log::info('CvEvaluator: Sending enriched prompt to agent', [
                'prompt_length' => strlen($prompt),
                'has_resume_samples' => ! empty($ragContext['resumes']),
                'has_past_evaluations' => ! empty($ragContext['evaluations']),
                'resume_sample_count' => count($ragContext['resumes'] ?? []),
                'past_evaluation_count' => count($ragContext['evaluations'] ?? []),
            ]);

            $agent = new CvEvaluatorAgent;
            $response = $agent->prompt($prompt);

            \Log::info('CvEvaluator: Response type', [
                'class' => get_class($response),
                'has_text' => isset($response->text),
                'text_length' => isset($response->text) ? strlen($response->text) : 0,
            ]);

            // The AI returns nested structure like {"evaluation": {...}} instead of flat keys
            $data = $response->toArray();
            \Log::info('CvEvaluator: toArray() result', [
                'count' => count($data),
                'keys' => array_keys($data),
                'full_data' => json_encode($data),
            ]);

            // Unwrap the "evaluation" key if present
            if (isset($data['evaluation']) && is_array($data['evaluation'])) {
                \Log::info('CvEvaluator: Unwrapping nested "evaluation" key');
                $data = $data['evaluation'];
            }

            // If still empty, try parsing raw text
            if (empty(array_filter($data))) {
                \Log::warning('CvEvaluator: Structured array empty, falling back to text parsing');
                $rawText = trim((string) $response->text);
                \Log::info('CvEvaluator: Raw text from response', [
                    'length' => strlen($rawText),
                    'first_500' => substr($rawText, 0, 500),
                ]);

                $decoded = json_decode($rawText, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    \Log::error('CvEvaluator: JSON decode failed', [
                        'error' => json_last_error_msg(),
                        'raw_text' => $rawText,
                    ]);
                    throw new \RuntimeException('AI returned invalid JSON: '.json_last_error_msg());
                }
                $data = $decoded;
            }

            \Log::info('CvEvaluator: Data after unwrapping', [
                'keys' => array_keys($data),
                'sample_keys' => array_slice(array_keys($data), 0, 5),
            ]);

            // The AI returns criteria as an array of objects: [{criterion: "...", score: X, reason: "..."}, ...]
            // Map criterion names to our snake_case keys
            $criterionNameMap = [
                'Contact Information' => 'contact_information',
                '1. Contact Information' => 'contact_information',
                'Professional Summary' => 'professional_summary',
                '2. Professional Summary' => 'professional_summary',
                'Work Experience' => 'work_experience',
                '3. Work Experience' => 'work_experience',
                'Skills Section' => 'skills_section',
                '4. Skills Section' => 'skills_section',
                'Education' => 'education',
                '5. Education' => 'education',
                'ATS Compatibility' => 'ats_compatibility',
                '6. ATS Compatibility' => 'ats_compatibility',
                'Formatting and Readability' => 'formatting_readability',
                '7. Formatting and Readability' => 'formatting_readability',
                'Achievements and Impact' => 'achievements_impact',
                '8. Achievements and Impact' => 'achievements_impact',
                'Keyword Optimisation' => 'keyword_optimisation',
                '9. Keyword Optimisation' => 'keyword_optimisation',
                'Overall Completeness' => 'overall_completeness',
                '10. Overall Completeness' => 'overall_completeness',
            ];

            $criteria = [];

            // If data is a numeric array of criterion objects
            if (isset($data[0]) && is_array($data[0]) && isset($data[0]['criterion'])) {
                \Log::info('CvEvaluator: Parsing array-of-objects format');
                foreach ($data as $item) {
                    $criterionName = $item['criterion'] ?? '';
                    $ourKey = $criterionNameMap[$criterionName] ?? null;

                    if ($ourKey) {
                        $criteria[$ourKey] = [
                            'score' => (int) ($item['score'] ?? 0),
                            'reason' => (string) ($item['reason'] ?? ''),
                        ];
                        \Log::debug("CvEvaluator: Mapped '{$criterionName}' -> '{$ourKey}'", $criteria[$ourKey]);
                    } else {
                        \Log::warning("CvEvaluator: Unknown criterion '{$criterionName}'");
                    }
                }
            }
            // Else try keyed object format (e.g., "1. Contact Information": {...})
            else {
                \Log::info('CvEvaluator: Parsing keyed-object format');
                foreach ($criterionNameMap as $aiKey => $ourKey) {
                    if (isset($data[$aiKey]) && is_array($data[$aiKey])) {
                        $criteria[$ourKey] = [
                            'score' => (int) ($data[$aiKey]['score'] ?? 0),
                            'reason' => (string) ($data[$aiKey]['reason'] ?? ''),
                        ];
                        \Log::debug("CvEvaluator: Mapped '{$aiKey}' -> '{$ourKey}'", $criteria[$ourKey]);
                    }
                    // Fallback to flat schema keys (contact_information_score, etc.)
                    elseif (isset($data["{$ourKey}_score"])) {
                        $criteria[$ourKey] = [
                            'score' => (int) ($data["{$ourKey}_score"] ?? 0),
                            'reason' => (string) ($data["{$ourKey}_reason"] ?? ''),
                        ];
                        \Log::debug("CvEvaluator: Flat key '{$ourKey}' mapped", $criteria[$ourKey]);
                    }
                }
            }

            // Fill in any missing criteria with defaults
            $allKeys = ['contact_information', 'professional_summary', 'work_experience', 'skills_section', 'education', 'ats_compatibility', 'formatting_readability', 'achievements_impact', 'keyword_optimisation', 'overall_completeness'];
            foreach ($allKeys as $key) {
                if (! isset($criteria[$key])) {
                    $criteria[$key] = ['score' => 0, 'reason' => ''];
                    \Log::warning("CvEvaluator: No data found for '{$key}', using defaults");
                }
            }

            // Parse top_strengths and critical_improvements
            // They could be: string with "||", array of strings, or missing
            $strengths = [];
            if (isset($data['top_strengths'])) {
                if (is_array($data['top_strengths'])) {
                    $strengths = $data['top_strengths'];
                } else {
                    $strengths = array_filter(array_map('trim', explode('||', (string) $data['top_strengths'])));
                }
            }

            $improvements = [];
            if (isset($data['critical_improvements'])) {
                if (is_array($data['critical_improvements'])) {
                    $improvements = $data['critical_improvements'];
                } else {
                    $improvements = array_filter(array_map('trim', explode('||', (string) $data['critical_improvements'])));
                }
            }

            // If AI didn't provide strengths/improvements, generate them from criteria
            if (empty($strengths) && ! empty($criteria)) {
                $sortedCriteria = $criteria;
                arsort($sortedCriteria);
                $topThree = array_slice($sortedCriteria, 0, 3, true);
                foreach ($topThree as $key => $item) {
                    if ($item['score'] >= 8) {
                        $strengths[] = ucwords(str_replace('_', ' ', $key)).': '.$item['reason'];
                    }
                }
                \Log::info('CvEvaluator: Generated strengths from top criteria', ['count' => count($strengths)]);
            }

            if (empty($improvements) && ! empty($criteria)) {
                $sortedCriteria = $criteria;
                asort($sortedCriteria);
                $bottomThree = array_slice($sortedCriteria, 0, 3, true);
                foreach ($bottomThree as $key => $item) {
                    if ($item['score'] <= 7) {
                        $improvements[] = ucwords(str_replace('_', ' ', $key)).': '.$item['reason'];
                    }
                }
                \Log::info('CvEvaluator: Generated improvements from low criteria', ['count' => count($improvements)]);
            }

            // Calculate overall_score if missing (weighted average of criteria)
            $overallScore = (int) ($data['overall_score'] ?? 0);
            if ($overallScore === 0 && ! empty($criteria)) {
                $total = array_sum(array_column($criteria, 'score'));
                $overallScore = (int) round(($total / count($criteria)) * 10);
                \Log::info('CvEvaluator: Calculated overall_score from criteria', ['score' => $overallScore]);
            }

            // Calculate grade if missing
            $grade = (string) ($data['grade'] ?? '');
            if (empty($grade) || $grade === 'N/A') {
                $grade = match (true) {
                    $overallScore >= 90 => 'A+',
                    $overallScore >= 85 => 'A',
                    $overallScore >= 80 => 'B+',
                    $overallScore >= 75 => 'B',
                    $overallScore >= 70 => 'C+',
                    $overallScore >= 65 => 'C',
                    $overallScore >= 50 => 'D',
                    default => 'F',
                };
                \Log::info('CvEvaluator: Calculated grade', ['grade' => $grade]);
            }

            $this->result = [
                'overall_score' => $overallScore,
                'grade' => $grade,
                'summary' => (string) ($data['summary'] ?? 'AI evaluation completed.'),
                'criteria' => $criteria,
                'top_strengths' => $strengths,
                'critical_improvements' => $improvements,
            ];

            \Log::info('CvEvaluator: Final result', [
                'overall_score' => $this->result['overall_score'],
                'grade' => $this->result['grade'],
                'criteria_count' => count($this->result['criteria']),
                'strengths_count' => count($this->result['top_strengths']),
                'improvements_count' => count($this->result['critical_improvements']),
            ]);

            // Save evaluation to database for authenticated users
            $savedEvaluation = null;
            if (auth()->check()) {
                $savedEvaluation = CvEvaluation::create([
                    'user_id' => auth()->id(),
                    'filename' => $this->uploadedFile?->getClientOriginalName(),
                    'overall_score' => $this->result['overall_score'],
                    'grade' => $this->result['grade'],
                    'summary' => $this->result['summary'],
                    'criteria' => $this->result['criteria'],
                    'top_strengths' => $this->result['top_strengths'],
                    'critical_improvements' => $this->result['critical_improvements'],
                    'cv_text' => $this->cvText,
                ]);

                \Log::info('CvEvaluator: Saved evaluation to database', ['user_id' => auth()->id()]);

                // Store evaluation embedding in Qdrant for future RAG
                try {
                    $vectorStore = app(EvaluationVectorStore::class);
                    $vectorStore->ensureCollectionExists();
                    $embedding = $vectorStore->generateEmbedding($this->cvText);
                    $vectorStore->store(
                        "eval_{$savedEvaluation->id}",
                        auth()->id(),
                        $this->result['grade'],
                        $this->result['overall_score'],
                        $this->cvText,
                        $embedding,
                    );
                    \Log::info('CvEvaluator: Stored evaluation embedding in Qdrant', [
                        'evaluation_id' => $savedEvaluation->id,
                    ]);
                } catch (\Throwable $e) {
                    \Log::warning('CvEvaluator: Failed to store evaluation in Qdrant', [
                        'message' => $e->getMessage(),
                    ]);
                }

                $this->refreshEvaluations();
            }

            if (auth()->check() && $savedEvaluation) {
                $creditManager = app(CreditManager::class);
                $credits = $creditManager->calculateFromUsage($response->usage, 'ai_evaluation');
                $creditManager->deduct(
                    auth()->user(),
                    $credits,
                    'ai_evaluation',
                    $savedEvaluation,
                    [
                        'prompt_tokens' => $response->usage->promptTokens,
                        'completion_tokens' => $response->usage->completionTokens,
                        'model' => 'mistral-large-3',
                    ]
                );
                $this->dispatch('credits-updated');
            }

            $this->evaluationState = 'complete';
        } catch (\Throwable $e) {
            \Log::error('CvEvaluator: Evaluation failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->errorMessage = 'AI evaluation failed: '.$e->getMessage();
            $this->evaluationState = 'error';
        }
    }

    public function restart(): void
    {
        $this->evaluationState = 'idle';
        $this->uploadedFile = null;
        $this->result = null;
        $this->errorMessage = null;
        $this->cvText = '';
        $this->pastedText = '';
        $this->selectedEvaluationIds = [];
        $this->comparisonResult = null;

        // Clear session data
        session()->forget('cv_evaluation_result');
    }

    /**
     * Extract plain text from the uploaded file using OCR and text extraction tools.
     */
    private function extractTextFromFile(): string
    {
        if (! $this->uploadedFile) {
            return '';
        }

        $path = $this->uploadedFile->getRealPath();
        $extension = strtolower($this->uploadedFile->getClientOriginalExtension());

        \Log::info('CvEvaluator: Extracting text from file', [
            'extension' => $extension,
            'path' => $path,
            'size' => filesize($path),
        ]);

        // Plain text files
        if ($extension === 'txt') {
            $text = file_get_contents($path) ?: '';
            \Log::info('CvEvaluator: Extracted from TXT', ['length' => strlen($text)]);

            return $text;
        }

        // PDF files: try text extraction first, then OCR
        if ($extension === 'pdf') {
            try {
                // First attempt: text-based PDF extraction
                $pdfText = Pdf::getText($path);
                \Log::info('CvEvaluator: Extracted from PDF (text layer)', ['length' => strlen($pdfText)]);

                // If we got substantial text, return it
                if (strlen(trim($pdfText)) > 100) {
                    return $pdfText;
                }

                \Log::warning('CvEvaluator: PDF text layer too short, trying OCR');
            } catch (\Throwable $e) {
                \Log::warning('CvEvaluator: PDF text extraction failed', ['error' => $e->getMessage()]);
            }

            // Second attempt: OCR extraction
            // Note: Imagick is recommended for converting PDF pages to images for better OCR
            if (extension_loaded('imagick')) {
                try {
                    $imagick = new \Imagick;
                    $imagick->setResolution(300, 300);
                    $imagick->readImage($path.'[0]'); // First page only
                    $imagick->setImageFormat('png');

                    $tempImage = sys_get_temp_dir().'/'.uniqid('cv_ocr_', true).'.png';
                    $imagick->writeImage($tempImage);
                    $imagick->clear();

                    // Run OCR on the image
                    $ocrText = (new TesseractOCR($tempImage))
                        ->lang('eng')
                        ->run();
                    @unlink($tempImage);

                    \Log::info('CvEvaluator: Extracted from PDF via Imagick+OCR', ['length' => strlen($ocrText)]);

                    if (strlen(trim($ocrText)) > 50) {
                        return $ocrText;
                    }
                } catch (\Throwable $e) {
                    \Log::error('CvEvaluator: Imagick+OCR extraction failed', ['error' => $e->getMessage()]);
                }
            } else {
                \Log::info('CvEvaluator: Imagick not available. Install php-imagick for better PDF OCR support.');
            }
        }

        // DOC/DOCX: basic text extraction (strip binary)
        if (in_array($extension, ['doc', 'docx'])) {
            $raw = file_get_contents($path) ?: '';
            $text = preg_replace('/[^\x20-\x7E\n\r\t]/', ' ', $raw) ?? '';
            \Log::info('CvEvaluator: Extracted from DOC/DOCX (basic)', ['length' => strlen($text)]);

            return $text;
        }

        \Log::warning('CvEvaluator: No extraction method worked, returning empty');

        return '';
    }

    /**
     * Compute the grade colour for display.
     */
    public function gradeColour(string $grade): string
    {
        return match (true) {
            str_starts_with($grade, 'A') => 'text-emerald-400',
            str_starts_with($grade, 'B') => 'text-blue-400',
            str_starts_with($grade, 'C') => 'text-amber-400',
            default => 'text-red-400',
        };
    }

    public function toggleEvaluationSelection(int $evaluationId): void
    {
        if (in_array($evaluationId, $this->selectedEvaluationIds)) {
            $this->selectedEvaluationIds = array_values(
                array_diff($this->selectedEvaluationIds, [$evaluationId])
            );
            $this->comparisonResult = null;
        } elseif (count($this->selectedEvaluationIds) < 2) {
            $this->selectedEvaluationIds[] = $evaluationId;
        }

        if (count($this->selectedEvaluationIds) === 2) {
            $this->computeComparison();
        }
    }

    public function computeComparison(): void
    {
        if (count($this->selectedEvaluationIds) !== 2) {
            $this->comparisonResult = null;

            return;
        }

        $evaluations = auth()->user()->cvEvaluations()
            ->whereIn('id', $this->selectedEvaluationIds)
            ->get()
            ->keyBy('id');

        $evalA = $evaluations->get($this->selectedEvaluationIds[0]);
        $evalB = $evaluations->get($this->selectedEvaluationIds[1]);

        if (! $evalA || ! $evalB) {
            $this->comparisonResult = null;

            return;
        }

        $allCriteriaKeys = [
            'contact_information', 'professional_summary', 'work_experience',
            'skills_section', 'education', 'ats_compatibility',
            'formatting_readability', 'achievements_impact',
            'keyword_optimisation', 'overall_completeness',
        ];

        $criteriaDiffs = [];
        foreach ($allCriteriaKeys as $key) {
            $scoreA = $evalA->criteria[$key]['score'] ?? 0;
            $scoreB = $evalB->criteria[$key]['score'] ?? 0;
            $criteriaDiffs[$key] = $scoreB - $scoreA;
        }

        $this->comparisonResult = [
            'eval_a' => $evalA->toArray(),
            'eval_b' => $evalB->toArray(),
            'overall_diff' => $evalB->overall_score - $evalA->overall_score,
            'grade_diff' => $evalB->grade !== $evalA->grade,
            'criteria_diffs' => $criteriaDiffs,
        ];
    }

    public function clearSelection(): void
    {
        $this->selectedEvaluationIds = [];
        $this->comparisonResult = null;
    }

    public function refreshEvaluations(): void
    {
        $this->evaluations = auth()->check()
            ? auth()->user()->cvEvaluations()->latest()->get()->toArray()
            : [];
    }

    /**
     * Gather RAG context from Qdrant: similar resumes + past evaluations.
     *
     * @return array{resumes: array, evaluations: array}
     */
    private function gatherRagContext(string $cvText): array
    {
        $resumes = [];
        $evaluations = [];

        // Generate embedding once, reuse for both searches
        $searchQuery = mb_substr($cvText, 0, 1500);

        try {
            $embedding = app(EvaluationVectorStore::class)->generateEmbedding($searchQuery);

            \Log::info('CvEvaluator: RAG — shared embedding generated', [
                'dimensions' => count($embedding),
            ]);
        } catch (\Throwable $e) {
            \Log::warning('CvEvaluator: RAG — embedding generation failed, skipping all searches', [
                'message' => $e->getMessage(),
            ]);

            return compact('resumes', 'evaluations');
        }

        try {
            $resumeStore = app(ResumeVectorStore::class);
            $resumes = $resumeStore->searchByEmbedding($embedding, 3);

            \Log::info('CvEvaluator: RAG — resume samples found', [
                'count' => count($resumes),
                'scores' => array_column($resumes, 'score'),
                'roles' => array_column($resumes, 'role'),
            ]);
        } catch (\Throwable $e) {
            \Log::warning('CvEvaluator: RAG — resume search failed', [
                'message' => $e->getMessage(),
            ]);
        }

        try {
            $evaluationStore = app(EvaluationVectorStore::class);
            $evaluations = $evaluationStore->searchByEmbedding($embedding, 3);

            \Log::info('CvEvaluator: RAG — past evaluations found', [
                'count' => count($evaluations),
                'scores' => array_column($evaluations, 'score'),
                'grades' => array_column($evaluations, 'grade'),
                'overall_scores' => array_column($evaluations, 'overall_score'),
            ]);
        } catch (\Throwable $e) {
            \Log::warning('CvEvaluator: RAG — evaluation search failed', [
                'message' => $e->getMessage(),
            ]);
        }

        return compact('resumes', 'evaluations');
    }

    /**
     * Build the evaluation prompt with injected RAG context.
     */
    private function buildEvaluationPrompt(string $cvText, array $ragContext): string
    {
        $prompt = '';

        // Inject resume samples
        if (! empty($ragContext['resumes'])) {
            $prompt .= '=== REFERENCE RESUME SAMPLES ('.count($ragContext['resumes'])." results from 17,000+ database) ===\n\n";

            foreach ($ragContext['resumes'] as $i => $resume) {
                $prompt .= '--- Sample '.($i + 1)." (Role: {$resume['role']}, Source: {$resume['source']}, Relevance: {$resume['score']}) ---\n";
                $prompt .= mb_substr($resume['content'], 0, 2000)."\n\n";
            }

            $prompt .= "Use these real-world resume samples as benchmarks. Compare the CV against the strongest samples found.\n\n";
        } else {
            \Log::info('CvEvaluator: RAG — no resume samples available, proceeding without reference data');
        }

        // Inject past evaluations
        if (! empty($ragContext['evaluations'])) {
            $prompt .= '=== PAST CV EVALUATIONS ('.count($ragContext['evaluations'])." results) ===\n\n";

            foreach ($ragContext['evaluations'] as $i => $eval) {
                $prompt .= '--- Evaluation '.($i + 1)." (Grade: {$eval['grade']}, Overall Score: {$eval['overall_score']}/100, Similarity: {$eval['score']}) ---\n";
                $prompt .= mb_substr($eval['content'], 0, 2000)."\n\n";
            }

            $prompt .= "Use these past evaluations as benchmarks. Pay attention to common weaknesses found in similar CVs and strengths that earned high scores. Adjust your scoring accordingly.\n\n";
        } else {
            \Log::info('CvEvaluator: RAG — no past evaluations available, proceeding without benchmark data');
        }

        // Finally, the actual CV to evaluate
        $prompt .= "=== CV TO EVALUATE ===\n\n{$cvText}";

        return $prompt;
    }

    public function render()
    {
        return view('livewire.cv-evaluator');
    }
}
