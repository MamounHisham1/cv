<?php

namespace App\Services;

use App\Models\Cv;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Converter;
use Spatie\Browsershot\Browsershot;

class CvExportService
{
    /**
     * Render the CV's chosen Blade template to a pixel-perfect PDF via
     * headless Chromium (Browsershot). Fonts are embedded locally so the
     * render is deterministic and never blocked on the network.
     *
     * @return string Raw PDF bytes.
     */
    public function toPdf(Cv $cv): string
    {
        $cv->load(['experiences', 'educations', 'skills', 'certifications', 'projects', 'languages']);

        $html = view('cv.export.pdf', ['cv' => $cv])->render();

        $shot = Browsershot::html($html)
            ->setChromePath(config('browsershot.chrome_path'))
            ->setNodeBinary(config('browsershot.node_path'))
            ->setNpmPackage(config('browsershot.npm_package_path'))
            ->noSandbox()
            ->format('A4')
            ->margins(0, 0, 0, 0)
            ->waitUntilNetworkIdle()
            ->showBackground();

        // Browsershot runs Chromium with file:// or data: origins, so base64
        // the local fonts + css directly into the HTML to avoid load failures.
        return $shot->pdf();
    }

    /**
     * Build a clean, ATS-friendly DOCX via PHPWord from the CV's data.
     * Mirrors the structure of professional-classic — one section per
     * CV section in section_order.
     *
     * @return string Raw DOCX bytes (temp file is cleaned up by the writer).
     */
    public function toDocx(Cv $cv): string
    {
        $cv->load(['experiences', 'educations', 'skills', 'certifications', 'projects', 'languages']);

        $phpWord = new PhpWord;
        $phpWord->setDefaultFontName('Calibri');
        $phpWord->setDefaultFontSize(11);

        $section = $phpWord->addSection([
            'marginTop' => Converter::cmToTwip(1.5),
            'marginBottom' => Converter::cmToTwip(1.5),
            'marginLeft' => Converter::cmToTwip(1.8),
            'marginRight' => Converter::cmToTwip(1.8),
        ]);

        $this->renderHeader($section, $cv);

        foreach ($cv->getSectionOrder() as $sectionKey) {
            if ($sectionKey === 'personal') {
                continue;
            }

            match ($sectionKey) {
                'experience' => $this->renderExperience($section, $cv),
                'education' => $this->renderEducation($section, $cv),
                'skills' => $this->renderSkills($section, $cv),
                'certifications' => $this->renderCertifications($section, $cv),
                'projects' => $this->renderProjects($section, $cv),
                'languages' => $this->renderLanguages($section, $cv),
                default => null,
            };
        }

        $tempPath = tempnam(sys_get_temp_dir(), 'cv_docx_').'.docx';

        try {
            $writer = IOFactory::createWriter($phpWord, 'Word2007');
            $writer->save($tempPath);

            return file_get_contents($tempPath);
        } finally {
            @unlink($tempPath);
        }
    }

    /**
     * A safe, filesystem-friendly filename for a CV download.
     */
    public function filename(Cv $cv, string $extension): string
    {
        $base = Str::slug($cv->title ?? 'cv') ?: 'cv';

        return $base.'.'.$extension;
    }

    private function renderHeader(Section $section, Cv $cv): void
    {
        $info = $cv->personal_info ?? [];
        $name = trim(($info['first_name'] ?? '').' '.($info['last_name'] ?? ''));

        if ($name) {
            $section->addText($name, ['bold' => true, 'size' => 24], ['alignment' => 'center']);
        }

        $contacts = array_filter([
            $info['email'] ?? null,
            $info['phone'] ?? null,
            $info['location'] ?? null,
            $info['linkedin'] ?? null,
            $info['website'] ?? null,
            $info['github'] ?? null,
        ]);

        if (! empty($contacts)) {
            $section->addText(implode(' | ', $contacts), ['size' => 9, 'color' => '555555'], ['alignment' => 'center']);
        }

        if ($cv->summary) {
            $section->addTextBreak();
            $this->heading($section, 'Professional Summary');
            $section->addText($cv->summary, ['size' => 10]);
        }
    }

    private function renderExperience(Section $section, Cv $cv): void
    {
        if ($cv->experiences->isEmpty()) {
            return;
        }

        $this->heading($section, 'Work Experience');

        foreach ($cv->experiences as $exp) {
            $title = trim($exp->title.'  ·  '.$exp->company);
            $section->addText($title, ['bold' => true, 'size' => 11]);

            $section->addText($this->range($exp->start_date, $exp->end_date, $exp->is_current), ['italic' => true, 'size' => 9, 'color' => '777777']);

            if ($exp->description) {
                $section->addText($exp->description, ['size' => 10]);
            }

            foreach ((array) ($exp->achievements ?? []) as $achievement) {
                if (is_string($achievement) && trim($achievement)) {
                    $section->addListItem($achievement, 0, ['size' => 10]);
                }
            }

            $section->addTextBreak();
        }
    }

    private function renderEducation(Section $section, Cv $cv): void
    {
        if ($cv->educations->isEmpty()) {
            return;
        }

        $this->heading($section, 'Education');

        foreach ($cv->educations as $edu) {
            $degree = trim(($edu->degree ?? '').($edu->field_of_study ? ' in '.$edu->field_of_study : ''));
            $section->addText($degree, ['bold' => true, 'size' => 11]);
            $section->addText($edu->institution.($edu->location ? ', '.$edu->location : ''), ['size' => 10]);
            $section->addText($this->range($edu->start_date, $edu->end_date, $edu->is_current), ['italic' => true, 'size' => 9, 'color' => '777777']);
            $section->addTextBreak();
        }
    }

    private function renderSkills(Section $section, Cv $cv): void
    {
        if ($cv->skills->isEmpty()) {
            return;
        }

        $this->heading($section, 'Skills');
        $section->addText($cv->skills->map(fn ($s) => $s->name)->implode(', '), ['size' => 10]);
        $section->addTextBreak();
    }

    private function renderCertifications(Section $section, Cv $cv): void
    {
        if ($cv->certifications->isEmpty()) {
            return;
        }

        $this->heading($section, 'Certifications');

        foreach ($cv->certifications as $cert) {
            $line = $cert->name.($cert->issuing_organization ? ' — '.$cert->issuing_organization : '');
            $section->addListItem($line, 0, ['size' => 10]);
        }

        $section->addTextBreak();
    }

    private function renderProjects(Section $section, Cv $cv): void
    {
        if ($cv->projects->isEmpty()) {
            return;
        }

        $this->heading($section, 'Projects');

        foreach ($cv->projects as $proj) {
            $section->addText($proj->name, ['bold' => true, 'size' => 11]);

            if ($proj->description) {
                $section->addText($proj->description, ['size' => 10]);
            }

            foreach ((array) ($proj->key_achievements ?? []) as $achievement) {
                if (is_string($achievement) && trim($achievement)) {
                    $section->addListItem($achievement, 0, ['size' => 10]);
                }
            }

            $section->addTextBreak();
        }
    }

    private function renderLanguages(Section $section, Cv $cv): void
    {
        if ($cv->languages->isEmpty()) {
            return;
        }

        $this->heading($section, 'Languages');

        foreach ($cv->languages as $lang) {
            $section->addListItem($lang->language.($lang->proficiency ? ' — '.ucfirst($lang->proficiency) : ''), 0, ['size' => 10]);
        }
    }

    private function heading(Section $section, string $text): void
    {
        $section->addText($text, ['bold' => true, 'size' => 12, 'color' => '1f2937', 'allCaps' => true], ['spaceBefore' => 60]);
        $section->addTextBreak(0);
    }

    private function range(?string $start, ?string $end, ?bool $current): string
    {
        $parts = array_filter([$start, $current ? 'Present' : $end]);

        return implode(' — ', $parts);
    }
}
