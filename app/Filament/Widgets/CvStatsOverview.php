<?php

namespace App\Filament\Widgets;

use App\Models\Cv;
use App\Models\CvEvaluation;
use App\Models\InterviewEvaluation;
use App\Models\InterviewSession;
use App\Models\ResumeSample;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CvStatsOverview extends StatsOverviewWidget
{
    protected ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $totalUsers = User::count();
        $usersThisWeek = User::where('created_at', '>=', now()->subWeek())->count();
        $totalCvs = Cv::count();
        $totalEvaluations = CvEvaluation::count();
        $avgCvScore = CvEvaluation::avg('overall_score');

        $recentCvsThisWeek = Cv::where('created_at', '>=', now()->subWeek())->count();
        $recentCvsLastWeek = Cv::whereBetween('created_at', [now()->subWeeks(2), now()->subWeek()])->count();

        $totalSessions = InterviewSession::count();
        $completedSessions = InterviewSession::where('status', 'completed')->count();
        $totalInterviewEvals = InterviewEvaluation::count();
        $completedInterviewEvals = InterviewEvaluation::where('status', InterviewEvaluation::STATUS_COMPLETED)->count();
        $failedInterviewEvals = InterviewEvaluation::where('status', InterviewEvaluation::STATUS_FAILED)->count();
        $avgInterviewScore = InterviewEvaluation::where('status', InterviewEvaluation::STATUS_COMPLETED)->avg('overall_score');

        $totalResumeSamples = ResumeSample::count();
        $acceptedSamples = ResumeSample::where('decision', 'accepted')->count();
        $rejectedSamples = ResumeSample::where('decision', 'rejected')->count();

        return [
            Stat::make('Total Users', number_format($totalUsers))
                ->description($usersThisWeek > 0 ? "+{$usersThisWeek} this week" : 'No new users this week')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info')
                ->chart([
                    User::where('created_at', '>=', now()->subDays(6))->count(),
                    User::where('created_at', '>=', now()->subDays(5))->count(),
                    User::where('created_at', '>=', now()->subDays(4))->count(),
                    User::where('created_at', '>=', now()->subDays(3))->count(),
                    User::where('created_at', '>=', now()->subDays(2))->count(),
                    User::where('created_at', '>=', now()->subDays(1))->count(),
                    $totalUsers,
                ]),

            Stat::make('Total CVs', number_format($totalCvs))
                ->description("{$recentCvsThisWeek} created this week")
                ->descriptionIcon($recentCvsThisWeek >= $recentCvsLastWeek ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($recentCvsThisWeek >= $recentCvsLastWeek ? 'success' : 'warning'),

            Stat::make('CV Evaluations', number_format($totalEvaluations))
                ->description('Average score: '.($avgCvScore ? number_format($avgCvScore, 1).'/100' : 'N/A'))
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color($avgCvScore && $avgCvScore >= 70 ? 'success' : 'danger'),

            Stat::make('Interviews', number_format($completedSessions))
                ->description("{$totalSessions} total sessions")
                ->descriptionIcon('heroicon-m-microphone')
                ->color('violet'),

            Stat::make('Interview Avg Score', $avgInterviewScore ? number_format($avgInterviewScore, 1).'/100' : 'N/A')
                ->description("{$completedInterviewEvals} completed evaluations")
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color($avgInterviewScore && $avgInterviewScore >= 70 ? 'success' : 'danger'),

            Stat::make('Eval Failures', number_format($failedInterviewEvals))
                ->description($totalInterviewEvals > 0
                    ? number_format(($failedInterviewEvals / $totalInterviewEvals) * 100, 1).'% failure rate'
                    : 'No evaluations yet')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($failedInterviewEvals > 0 ? 'danger' : 'success'),

            Stat::make('Resume Samples', number_format($totalResumeSamples))
                ->description("{$acceptedSamples} accepted, {$rejectedSamples} rejected")
                ->descriptionIcon('heroicon-m-document-text')
                ->color('gray'),

            Stat::make('Acceptance Rate', $totalResumeSamples > 0
                ? number_format(($acceptedSamples / max($acceptedSamples + $rejectedSamples, 1)) * 100, 1).'%'
                : 'N/A')
                ->description("{$acceptedSamples} of ".($acceptedSamples + $rejectedSamples).' total')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color($totalResumeSamples > 0 && ($acceptedSamples / max($acceptedSamples + $rejectedSamples, 1)) >= 0.5 ? 'success' : 'danger'),
        ];
    }
}
