<?php

namespace App\Ai\Tools\Telegram;

use App\Models\InterviewEvaluation;
use App\Models\InterviewSession;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\DB;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class GetInterviewStats implements Tool
{
    public function description(): Stringable|string
    {
        return 'Get statistics about interview practice sessions: total sessions, completion rate, average score.';
    }

    public function handle(Request $request): Stringable|string
    {
        $totalSessions = InterviewSession::count();
        $completedSessions = InterviewSession::where('status', 'completed')->count();

        $completionRate = $totalSessions > 0
            ? round(($completedSessions / $totalSessions) * 100, 1)
            : 0.0;

        $completed = InterviewEvaluation::where('status', InterviewEvaluation::STATUS_COMPLETED);
        $totalEvaluations = InterviewEvaluation::count();
        $completedEvaluations = $completed->count();
        $avgScore = (clone $completed)->avg('overall_score');

        $gradeDistribution = InterviewEvaluation::select('grade', DB::raw('count(*) as count'))
            ->whereNotNull('grade')
            ->groupBy('grade')
            ->pluck('count', 'grade');

        $avgScoreFormatted = $avgScore !== null ? round((float) $avgScore, 1) : 'N/A';

        $output = "=== Interview Statistics ===\n\n";
        $output .= "Total interview sessions: {$totalSessions}\n";
        $output .= "Completed sessions: {$completedSessions}\n";
        $output .= "Completion rate: {$completionRate}%\n\n";
        $output .= "Total interview evaluations: {$totalEvaluations}\n";
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
