<?php

namespace App\Services;

class ResumeHtmlParser
{
    private const SECTION_MAP = [
        'NAME' => 'name',
        'SUMM' => 'summary',
        'SKLL' => 'skills',
        'HILT' => 'highlights',
        'ACCM' => 'accomplishments',
        'EXPR' => 'experience',
        'EDUC' => 'education',
        'CERT' => 'certifications',
    ];

    public function parse(string $html): array
    {
        $dom = new \DOMDocument;
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="UTF-8">'.$html);
        libxml_clear_errors();

        $xpath = new \DOMXPath($dom);
        $sections = $xpath->query('//div[contains(@id, "SECTION_")]');

        $result = [];

        foreach ($sections as $section) {
            $id = $section->getAttribute('id');

            foreach (self::SECTION_MAP as $pattern => $key) {
                if (str_contains($id, $pattern)) {
                    $heading = $xpath->query('.//div[@class="sectiontitle"]', $section);
                    $headingText = $heading->length > 0 ? trim($heading->item(0)->textContent) : '';

                    $text = trim(preg_replace('/\s+/', ' ', $section->textContent));

                    if (! empty($headingText)) {
                        $text = trim(mb_substr($text, mb_strlen($headingText)));
                    }

                    if (! empty($text)) {
                        $result[$key] = $text;
                    }
                    break;
                }
            }
        }

        return $result;
    }

    public function toEmbeddingText(string $html, string $category): string
    {
        $sections = $this->parse($html);

        if (empty($sections)) {
            return "Category: {$category}\n\n".trim(preg_replace('/\s+/', ' ', strip_tags($html)));
        }

        $parts = ["Category: {$category}"];

        foreach (['name', 'summary', 'highlights', 'skills', 'accomplishments', 'experience', 'education', 'certifications'] as $key) {
            if (! empty($sections[$key])) {
                $label = ucfirst($key);
                $parts[] = "{$label}: {$sections[$key]}";
            }
        }

        return implode("\n\n", $parts);
    }
}
