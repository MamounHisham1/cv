<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * GDPR Article 20 — data portability. Exports the authenticated user's
 * personal data (profile, CVs + sections, evaluations, interview history,
 * credit ledger, referrals) as a downloadable JSON document.
 */
class UserDataExportController extends Controller
{
    public function __invoke(Request $request): StreamedResponse|JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $user->load([
            'cvs.experiences',
            'cvs.educations',
            'cvs.skills',
            'cvs.projects',
            'cvs.certifications',
            'cvs.languages',
            'cvEvaluations' => fn ($q) => $q->select(['id', 'user_id', 'cv_id', 'filename', 'overall_score', 'grade', 'status', 'created_at']),
            'creditBalance',
            'creditTransactions' => fn ($q) => $q->select(['id', 'user_id', 'amount', 'type', 'metadata', 'created_at'])->latest()->limit(500),
        ]);

        $payload = [
            'exported_at' => Carbon::now()->toIso8601String(),
            'schema_version' => 1,
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at?->toIso8601String(),
            ],
            'cvs' => $user->cvs->map(fn ($cv) => [
                'id' => $cv->id,
                'title' => $cv->title,
                'template_id' => $cv->template_id,
                'personal_info' => $cv->personal_info,
                'summary' => $cv->summary,
                'created_at' => $cv->created_at?->toIso8601String(),
                'experiences' => $cv->experiences->toArray(),
                'educations' => $cv->educations->toArray(),
                'skills' => $cv->skills->toArray(),
                'projects' => $cv->projects->toArray(),
                'certifications' => $cv->certifications->toArray(),
                'languages' => $cv->languages->toArray(),
            ])->all(),
            'cv_evaluations' => $user->cvEvaluations->toArray(),
            'credit_balance' => $user->creditBalance?->only(['balance', 'plan', 'created_at']),
            'credit_transactions' => $user->creditTransactions->toArray(),
        ];

        $filename = 'seratyai-data-'.$user->id.'-'.now()->format('Y-m-d').'.json';

        return response()->streamDownload(function () use ($payload): void {
            echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }, $filename, [
            'Content-Type' => 'application/json',
        ]);
    }
}
