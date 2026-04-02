<?php

namespace App\Jobs;

use App\Ai\Agents\CvParser;
use App\Models\Cv;
use App\Models\CvCertification;
use App\Models\CvEducation;
use App\Models\CvExperience;
use App\Models\CvLanguage;
use App\Models\CvProject;
use App\Models\CvSkill;
use App\Services\CreditManager;
use App\Services\CvTextExtractor;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ParseCvWithAi implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public $backoff = [10, 30];

    public $tries = 2;

    public $timeout = 300;

    public function __construct(
        public int $userId,
        public string $filePath,
        public string $extension,
        public string $filename,
        public int $fileSize,
        public int $cvId,
    ) {}

    public function handle(): void
    {
        try {
            $text = app(CvTextExtractor::class)->extractFromPath(
                $this->filePath,
                $this->extension,
                $this->filename,
                $this->fileSize,
            );

            if (empty(trim($text))) {
                $this->failImport('Could not extract text from the file.');

                return;
            }

            $agent = new CvParser;
            $response = $agent->prompt("Extract all data from this CV into the exact schema fields (first_name, last_name, email, phone, location, linkedin, github, website, title, summary, experiences, skills, educations, certifications, projects, languages). Return ONLY valid JSON with these exact keys:\n\n{$text}");

            $data = $response->structured;
            $data = $this->normalizeImportData($data);

            $cv = Cv::find($this->cvId);

            if (! $cv) {
                $this->failImport('CV not found.');

                return;
            }

            $personalInfo = [
                'first_name' => $data['first_name'] ?? '',
                'last_name' => $data['last_name'] ?? '',
                'email' => $data['email'] ?? $cv->personal_info['email'] ?? '',
                'phone' => $data['phone'] ?? '',
                'location' => $data['location'] ?? '',
                'linkedin' => $data['linkedin'] ?? '',
                'github' => $data['github'] ?? '',
                'website' => $data['website'] ?? '',
            ];

            $cv->update([
                'title' => $data['title'] ?? 'Imported CV',
                'personal_info' => $personalInfo,
                'summary' => $data['summary'] ?? '',
            ]);

            $cv->experiences()->delete();
            $cv->skills()->delete();
            $cv->educations()->delete();
            $cv->certifications()->delete();
            $cv->projects()->delete();
            $cv->languages()->delete();

            $this->importExperiences($cv, $data['experiences'] ?? []);
            $this->importSkills($cv, $data['skills'] ?? []);
            $this->importEducations($cv, $data['educations'] ?? []);
            $this->importCertifications($cv, $data['certifications'] ?? []);
            $this->importProjects($cv, $data['projects'] ?? []);
            $this->importLanguages($cv, $data['languages'] ?? []);

            Log::info('ParseCvWithAi: Import completed', ['cv_id' => $cv->id, 'title' => $cv->title]);

            $user = $cv->user;
            if ($user) {
                $creditManager = app(CreditManager::class);
                $credits = $creditManager->calculateFromUsage($response->usage, 'ai_parse');
                $creditManager->deduct($user, $credits, 'ai_parse', $cv, [
                    'prompt_tokens' => $response->usage->promptTokens,
                    'completion_tokens' => $response->usage->completionTokens,
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('ParseCvWithAi: Import failed', [
                'cv_id' => $this->cvId,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $this->failImport($e->getMessage());
        } finally {
            $relativePath = str_replace(storage_path('app/private/'), '', $this->filePath);
            Storage::delete($relativePath);
        }
    }

    private function failImport(string $message): void
    {
        $cv = Cv::find($this->cvId);
        if ($cv) {
            $cv->update(['title' => 'Import failed']);
        }

        Log::warning('ParseCvWithAi: Import failed', ['cv_id' => $this->cvId, 'message' => $message]);
    }

    private function normalizeImportData(array $data): array
    {
        if (isset($data['first_name']) || isset($data['experiences'])) {
            return $data;
        }

        $normalized = [];

        $pi = $data['personal_information'] ?? $data['personal_info'] ?? [];
        if (isset($pi['name']) && ! isset($pi['first_name'])) {
            $nameParts = preg_split('/\s+/', trim($pi['name']), 2);
            $pi['first_name'] = $nameParts[0] ?? '';
            $pi['last_name'] = $nameParts[1] ?? '';
        }
        $normalized['first_name'] = $pi['first_name'] ?? $data['first_name'] ?? '';
        $normalized['last_name'] = $pi['last_name'] ?? $data['last_name'] ?? '';
        $normalized['email'] = $pi['email'] ?? $data['email'] ?? '';
        $normalized['phone'] = $pi['phone'] ?? $data['phone'] ?? '';
        $normalized['location'] = $pi['location'] ?? $data['location'] ?? '';
        $normalized['linkedin'] = $pi['linkedin'] ?? $data['linkedin'] ?? '';
        $normalized['github'] = $pi['github'] ?? $data['github'] ?? '';
        $normalized['website'] = $pi['website'] ?? $data['website'] ?? '';
        $normalized['title'] = $pi['title'] ?? $data['title'] ?? 'Imported CV';
        $normalized['summary'] = $data['profile'] ?? $data['summary'] ?? $data['about'] ?? $data['objective'] ?? '';

        $rawExperiences = $data['work_experience'] ?? $data['experience'] ?? $data['experiences'] ?? [];
        if (is_array($rawExperiences)) {
            $normalized['experiences'] = array_map(function ($exp) {
                return [
                    'company' => $exp['company'] ?? $exp['name'] ?? '',
                    'title' => $exp['title'] ?? $exp['role'] ?? '',
                    'location' => $exp['location'] ?? '',
                    'start_date' => $exp['start_date'] ?? null,
                    'end_date' => $exp['end_date'] ?? null,
                    'is_current' => $exp['is_current'] ?? false,
                    'description' => $exp['description'] ?? '',
                    'achievements' => $exp['achievements'] ?? [],
                ];
            }, $rawExperiences);
        }

        $rawSkills = $data['skills'] ?? [];
        if (isset($rawSkills['frontend']) || isset($rawSkills['backend']) || isset($rawSkills['tools'])) {
            $normalized['skills'] = [];
            foreach ($rawSkills as $category => $names) {
                if (is_array($names)) {
                    foreach ($names as $name) {
                        if (is_string($name) && strlen(trim($name)) > 0) {
                            $normalized['skills'][] = ['name' => $name, 'category' => $category, 'level' => 'intermediate'];
                        }
                    }
                }
            }
        } elseif (is_array($rawSkills)) {
            $normalized['skills'] = array_map(function ($skill) {
                if (is_string($skill)) {
                    return ['name' => $skill, 'category' => 'general', 'level' => 'intermediate'];
                }

                return ['name' => $skill['name'] ?? '', 'category' => $skill['category'] ?? 'general', 'level' => $skill['level'] ?? 'intermediate'];
            }, $rawSkills);
        }

        $rawEducations = $data['education'] ?? $data['educations'] ?? [];
        if (is_array($rawEducations)) {
            $normalized['educations'] = array_map(function ($edu) {
                return [
                    'institution' => $edu['institution'] ?? $edu['school'] ?? '',
                    'degree' => $edu['degree'] ?? '',
                    'field_of_study' => $edu['field_of_study'] ?? $edu['major'] ?? '',
                    'location' => $edu['location'] ?? '',
                    'start_date' => $edu['start_date'] ?? null,
                    'end_date' => $edu['end_date'] ?? null,
                    'is_current' => $edu['is_current'] ?? false,
                ];
            }, $rawEducations);
        }

        $normalized['certifications'] = $data['certifications'] ?? [];
        $normalized['projects'] = $data['projects'] ?? [];
        $normalized['languages'] = $data['languages'] ?? [];

        return $normalized;
    }

    private function importExperiences(Cv $cv, array $data): void
    {
        foreach ($data as $index => $exp) {
            CvExperience::create([
                'cv_id' => $cv->id,
                'company' => $exp['company'] ?? '',
                'title' => $exp['title'] ?? '',
                'location' => $exp['location'] ?? '',
                'start_date' => $exp['start_date'] ?? null,
                'end_date' => $exp['end_date'] ?? null,
                'is_current' => $exp['is_current'] ?? false,
                'description' => $exp['description'] ?? '',
                'achievements' => $exp['achievements'] ?? [],
                'sort_order' => $index,
            ]);
        }
    }

    private function importSkills(Cv $cv, array $data): void
    {
        foreach ($data as $index => $skill) {
            CvSkill::create([
                'cv_id' => $cv->id,
                'name' => $skill['name'] ?? '',
                'category' => $skill['category'] ?? 'general',
                'level' => $skill['level'] ?? 'intermediate',
                'sort_order' => $index,
            ]);
        }
    }

    private function importEducations(Cv $cv, array $data): void
    {
        foreach ($data as $index => $edu) {
            CvEducation::create([
                'cv_id' => $cv->id,
                'institution' => $edu['institution'] ?? '',
                'degree' => $edu['degree'] ?? '',
                'field_of_study' => $edu['field_of_study'] ?? '',
                'location' => $edu['location'] ?? '',
                'start_date' => $edu['start_date'] ?? null,
                'end_date' => $edu['end_date'] ?? null,
                'is_current' => $edu['is_current'] ?? false,
                'sort_order' => $index,
            ]);
        }
    }

    private function importCertifications(Cv $cv, array $data): void
    {
        foreach ($data as $index => $cert) {
            CvCertification::create([
                'cv_id' => $cv->id,
                'name' => $cert['name'] ?? '',
                'issuing_organization' => $cert['issuing_organization'] ?? '',
                'issue_date' => $cert['issue_date'] ?? null,
                'expiration_date' => $cert['expiration_date'] ?? null,
                'credential_id' => $cert['credential_id'] ?? '',
                'sort_order' => $index,
            ]);
        }
    }

    private function importProjects(Cv $cv, array $data): void
    {
        foreach ($data as $index => $proj) {
            CvProject::create([
                'cv_id' => $cv->id,
                'name' => $proj['name'] ?? '',
                'description' => $proj['description'] ?? '',
                'key_achievements' => $proj['key_achievements'] ?? [],
                'project_url' => $proj['project_url'] ?? '',
                'github_url' => $proj['github_url'] ?? '',
                'start_date' => $proj['start_date'] ?? null,
                'end_date' => $proj['end_date'] ?? null,
                'sort_order' => $index,
            ]);
        }
    }

    private function importLanguages(Cv $cv, array $data): void
    {
        foreach ($data as $index => $lang) {
            CvLanguage::create([
                'cv_id' => $cv->id,
                'language' => $lang['language'] ?? '',
                'proficiency' => $lang['proficiency'] ?? 'intermediate',
                'sort_order' => $index,
            ]);
        }
    }
}
