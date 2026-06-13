<?php

namespace App\Services;

use App\Models\Cv;
use App\Models\CvCertification;
use App\Models\CvEducation;
use App\Models\CvExperience;
use App\Models\CvLanguage;
use App\Models\CvProject;
use App\Models\CvSkill;

/**
 * Normalizes raw AI-extracted CV data into the app's canonical shape and
 * persists its section rows.
 *
 * Single source of truth for CV import, shared by the synchronous importer
 * (CvImporter Livewire component) and the asynchronous one (ParseCvWithAi job).
 */
class CvDataHydrator
{
    /**
     * Normalize an arbitrary AI response into the canonical flat schema:
     * first_name, last_name, email, phone, location, linkedin, github,
     * website, title, summary, experiences, skills, educations,
     * certifications, projects, languages.
     *
     * If the data is already in the expected flat shape, it is returned as-is.
     */
    public function normalize(array $data): array
    {
        // If the AI returned the expected flat structure, return as-is.
        if (isset($data['first_name']) || isset($data['experiences'])) {
            return $data;
        }

        $normalized = [];

        // Handle nested personal_information object.
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

        // Summary could be under "profile", "summary", "about", "objective".
        $normalized['summary'] = $data['profile'] ?? $data['summary'] ?? $data['about'] ?? $data['objective'] ?? '';

        // Experiences could be under "work_experience", "experience", "experiences".
        $rawExperiences = $data['work_experience'] ?? $data['experience'] ?? $data['experiences'] ?? [];
        if (is_array($rawExperiences)) {
            $normalized['experiences'] = array_map(function ($exp) {
                // A "name" key with no "company" is a project, not a work experience.
                $company = $exp['company'] ?? (isset($exp['name']) ? $exp['name'] : '');

                return [
                    'company' => $company,
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

        // Skills may be a nested object {frontend: [...], backend: [...]} or a flat array.
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

                return [
                    'name' => $skill['name'] ?? '',
                    'category' => $skill['category'] ?? 'general',
                    'level' => $skill['level'] ?? 'intermediate',
                ];
            }, $rawSkills);
        }

        // Education.
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
        $normalized['projects'] = $data['projects'] ?? $data['project'] ?? [];
        $normalized['languages'] = $data['languages'] ?? $data['language'] ?? [];

        return $normalized;
    }

    /**
     * Persist all CV sections from normalized data.
     *
     * Each section value may be an array of rows or a JSON string; non-array
     * values are skipped. Rows are created with a sequential sort_order.
     */
    public function import(Cv $cv, array $data): void
    {
        $this->importExperiences($cv, $data['experiences'] ?? []);
        $this->importSkills($cv, $data['skills'] ?? []);
        $this->importEducations($cv, $data['educations'] ?? []);
        $this->importCertifications($cv, $data['certifications'] ?? []);
        $this->importProjects($cv, $data['projects'] ?? []);
        $this->importLanguages($cv, $data['languages'] ?? []);
    }

    private function importExperiences(Cv $cv, array|string $data): void
    {
        $experiences = $this->asArray($data);

        foreach ($experiences as $index => $exp) {
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

    private function importSkills(Cv $cv, array|string $data): void
    {
        $skills = $this->asArray($data);

        foreach ($skills as $index => $skill) {
            CvSkill::create([
                'cv_id' => $cv->id,
                'name' => $skill['name'] ?? '',
                'category' => $skill['category'] ?? 'general',
                'level' => $skill['level'] ?? 'intermediate',
                'sort_order' => $index,
            ]);
        }
    }

    private function importEducations(Cv $cv, array|string $data): void
    {
        $educations = $this->asArray($data);

        foreach ($educations as $index => $edu) {
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

    private function importCertifications(Cv $cv, array|string $data): void
    {
        $certifications = $this->asArray($data);

        foreach ($certifications as $index => $cert) {
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

    private function importProjects(Cv $cv, array|string $data): void
    {
        $projects = $this->asArray($data);

        foreach ($projects as $index => $proj) {
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

    private function importLanguages(Cv $cv, array|string $data): void
    {
        $languages = $this->asArray($data);

        foreach ($languages as $index => $lang) {
            CvLanguage::create([
                'cv_id' => $cv->id,
                'language' => $lang['language'] ?? '',
                'proficiency' => $lang['proficiency'] ?? 'intermediate',
                'sort_order' => $index,
            ]);
        }
    }

    /**
     * Coerce a section value (array or JSON string) into an array, or return [].
     *
     * @return array<int, array>
     */
    private function asArray(array|string $data): array
    {
        if (is_array($data)) {
            return $data;
        }

        $decoded = json_decode($data, true);

        return is_array($decoded) ? $decoded : [];
    }
}
