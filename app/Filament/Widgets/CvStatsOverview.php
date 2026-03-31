<?php

namespace App\Filament\Widgets;

use App\Models\Cv;
use App\Models\CvCertification;
use App\Models\CvEducation;
use App\Models\CvEvaluation;
use App\Models\CvExperience;
use App\Models\CvProject;
use App\Models\CvSkill;
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
        $totalCvs = Cv::count();
        $totalExperiences = CvExperience::count();
        $totalEducations = CvEducation::count();
        $totalSkills = CvSkill::count();
        $totalCertifications = CvCertification::count();
        $totalProjects = CvProject::count();
        $totalEvaluations = CvEvaluation::count();
        $totalResumeSamples = ResumeSample::count();
        $avgScore = CvEvaluation::avg('overall_score');

        $recentCvsThisWeek = Cv::where('created_at', '>=', now()->subWeek())->count();
        $recentCvsLastWeek = Cv::whereBetween('created_at', [now()->subWeeks(2), now()->subWeek()])->count();

        $acceptedSamples = ResumeSample::where('decision', 'accepted')->count();
        $rejectedSamples = ResumeSample::where('decision', 'rejected')->count();

        return [
            Stat::make('Total Users', number_format($totalUsers))
                ->description($totalUsers > 0 ? '+1 this week' : 'No users yet')
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

            Stat::make('Work Experiences', number_format($totalExperiences))
                ->description("Across {$totalCvs} CVs")
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('success'),

            Stat::make('Certifications', number_format($totalCertifications))
                ->description('AWS & professional')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('warning'),

            Stat::make('Educations', number_format($totalEducations))
                ->description("Across {$totalCvs} CVs")
                ->descriptionIcon('heroicon-m-building-library')
                ->color('pink'),

            Stat::make('CV Evaluations', number_format($totalEvaluations))
                ->description('Average score: '.($avgScore ? number_format($avgScore, 1).'/100' : 'N/A'))
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color($avgScore && $avgScore >= 70 ? 'success' : 'danger'),

            Stat::make('Resume Samples', number_format($totalResumeSamples))
                ->description("{$acceptedSamples} accepted, {$rejectedSamples} rejected")
                ->descriptionIcon('heroicon-m-document-text')
                ->color('gray'),

            Stat::make('Skills', number_format($totalSkills))
                ->description("Across {$totalCvs} CVs")
                ->descriptionIcon('heroicon-m-cpu-chip')
                ->color('violet'),

            Stat::make('Projects', number_format($totalProjects))
                ->description("Across {$totalCvs} CVs")
                ->descriptionIcon('heroicon-m-code-bracket')
                ->color('info'),

            Stat::make('Acceptance Rate', $totalResumeSamples > 0
                ? number_format(($acceptedSamples / max($acceptedSamples + $rejectedSamples, 1)) * 100, 1).'%'
                : 'N/A')
                ->description("{$acceptedSamples} of ".($acceptedSamples + $rejectedSamples).' total')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color($totalResumeSamples > 0 && ($acceptedSamples / max($acceptedSamples + $rejectedSamples, 1)) >= 0.5 ? 'success' : 'danger'),
        ];
    }
}
