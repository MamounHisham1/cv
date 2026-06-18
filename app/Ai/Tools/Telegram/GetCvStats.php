<?php

namespace App\Ai\Tools\Telegram;

use App\Models\Cv;
use App\Models\CvEvaluation;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\DB;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class GetCvStats implements Tool
{
    public function description(): Stringable|string
    {
        return 'Get statistics about CVs created and CV evaluations: total CVs, by status, total evaluations, grade distribution, average overall score.';
    }

    public function handle(Request $request): Stringable|string
    {
        $totalCvs = Cv::count();

        $byStatus = Cv::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $totalEvaluations = CvEvaluation::count();
        $completedEvaluations = CvEvaluation::completed()->count();
        $avgScore = CvEvaluation::completed()->avg('overall_score');

        $gradeDistribution = CvEvaluation::select('grade', DB::raw('count(*) as count'))
            ->whereNotNull('grade')
            ->groupBy('grade')
            ->pluck('count', 'grade');

        $output = "=== CV Statistics ===\n\n";
        $output .= "Total CVs: {$totalCvs}\n\n";
        $output .= "CVs by status:\n";
        if ($byStatus->isEmpty()) {
            $output .= "- No CVs found.\n";
        } else {
            foreach ($byStatus as $status => $count) {
                $label = $status ?: '(none)';
                $output .= "- {$label}: {$count}\n";
            }
        }

        $avgScoreFormatted = $avgScore !== null ? round((float) $avgScore, 1) : 'N/A';

        $output .= "\nTotal CV evaluations: {$totalEvaluations}\n";
        $output .= "Completed evaluations: {$completedEvaluations}\n";
        $output .= "Average overall score: {$avgScoreFormatted}\n\n";
        $output .= "Grade distribution:\n";
        if ($gradeDistribution->isEmpty()) {
            $output .= "- No graded evaluations yet.\n";
        } else {
            foreach ($gradeDistribution as $grade => $count) {
                $output .= "- {$grade}: {$count}\n";
            }
        }

        return $output;
    }

    public function schema(JsonSchema $schema): array
    {
        return [];
    }
}
