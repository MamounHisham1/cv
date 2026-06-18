<?php

namespace App\Cv\IndustryPacks;

/**
 * The default, industry-neutral pack. Applies to every CV that hasn't
 * opted into a specialized pack.
 *
 * The canonical skill-category vocabulary lives here — every other place
 * in the app (CvSkill constants, AI tool enums, Livewire managers,
 * Filament admin) reads from {@see self::skillCategories()} so the three
 * previously-divergent category lists can never drift again.
 */
class GenericPack implements IndustryPack
{
    public function id(): string
    {
        return 'generic';
    }

    public function name(): string
    {
        return 'General';
    }

    public function description(): string
    {
        return 'Industry-neutral presets suitable for any profession.';
    }

    public function skillCategories(): array
    {
        return [
            'general' => 'General',
            'technical' => 'Technical Skills',
            'software' => 'Software & Tools',
            'industry' => 'Industry Knowledge',
            'soft' => 'Soft Skills',
        ];
    }

    public function skillSuggestions(): array
    {
        return [
            'Microsoft Office', 'Excel', 'PowerPoint', 'Word',
            'Google Workspace', 'Slack', 'Teams', 'Zoom',
            'Project Management', 'Agile', 'Scrum', 'Kanban',
            'Data Analysis', 'SQL', 'Python', 'R',
            'Salesforce', 'HubSpot', 'SAP', 'Oracle',
        ];
    }

    public function certificationSuggestions(): array
    {
        return [
            'Project Management Professional (PMP)',
            'Certified ScrumMaster (CSM)',
            'Google Project Management Certificate',
            'Microsoft Office Specialist',
            'HubSpot Inbound Certification',
        ];
    }

    public function promptContext(): string
    {
        return '';
    }
}
