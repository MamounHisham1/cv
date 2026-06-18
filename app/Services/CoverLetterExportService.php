<?php

namespace App\Services;

use App\Models\CoverLetter;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Converter;
use Spatie\Browsershot\Browsershot;

class CoverLetterExportService
{
    /**
     * Pixel-perfect PDF via headless Chromium, reusing the letter's
     * chosen Blade template. Inlines the same fonts/CSS as the CV export.
     *
     * @return string Raw PDF bytes.
     */
    public function toPdf(CoverLetter $letter): string
    {
        $letter->load('cv');

        $html = view('cover-letter.export.pdf', ['letter' => $letter])->render();

        return Browsershot::html($html)
            ->setChromePath(config('browsershot.chrome_path'))
            ->setNodeBinary(config('browsershot.node_path'))
            ->setNpmPackage(config('browsershot.npm_package_path'))
            ->noSandbox()
            ->format('A4')
            ->margins(0, 0, 0, 0)
            ->waitUntilNetworkIdle()
            ->showBackground()
            ->pdf();
    }

    /**
     * Clean ATS-friendly DOCX built from the letter body.
     *
     * @return string Raw DOCX bytes.
     */
    public function toDocx(CoverLetter $letter): string
    {
        $letter->load('cv');
        $info = $letter->cv?->personal_info ?? [];
        $name = trim(($info['first_name'] ?? '').' '.($info['last_name'] ?? '')) ?: $letter->title;

        $phpWord = new PhpWord;
        $phpWord->setDefaultFontName('Calibri');
        $phpWord->setDefaultFontSize(11);

        $section = $phpWord->addSection([
            'marginTop' => Converter::cmToTwip(2.5),
            'marginBottom' => Converter::cmToTwip(2.5),
            'marginLeft' => Converter::cmToTwip(2.5),
            'marginRight' => Converter::cmToTwip(2.5),
        ]);

        $section->addText($name, ['bold' => true, 'size' => 14]);
        $contacts = array_filter([$info['email'] ?? null, $info['phone'] ?? null, $info['location'] ?? null]);
        if (! empty($contacts)) {
            $section->addText(implode(' · ', $contacts), ['size' => 9, 'color' => '555555']);
        }

        $section->addText(now()->format('F j, Y'), ['size' => 10]);
        $section->addTextBreak();

        foreach ($this->paragraphs($letter->body) as $paragraph) {
            $section->addText($paragraph, ['size' => 11]);
            $section->addTextBreak();
        }

        $section->addTextBreak();
        $section->addText('Sincerely,');
        $section->addText($name, ['bold' => true]);

        $tempPath = tempnam(sys_get_temp_dir(), 'cl_docx_').'.docx';

        try {
            IOFactory::createWriter($phpWord, 'Word2007')->save($tempPath);

            return file_get_contents($tempPath);
        } finally {
            @unlink($tempPath);
        }
    }

    public function filename(CoverLetter $letter, string $extension): string
    {
        return (Str::slug($letter->title) ?: 'cover-letter').'.'.$extension;
    }

    /**
     * @return list<string>
     */
    private function paragraphs(?string $body): array
    {
        if (! $body) {
            return [];
        }

        return array_values(array_filter(
            array_map('trim', preg_split('/\r\n|\r|\n/', (string) $body)),
            fn ($p) => $p !== ''
        ));
    }
}
