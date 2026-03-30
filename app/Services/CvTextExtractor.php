<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Spatie\PdfToText\Pdf;
use thiagoalessio\TesseractOCR\TesseractOCR;
use ZipArchive;

class CvTextExtractor
{
    public function extract(TemporaryUploadedFile $file): string
    {
        $path = $file->getRealPath();
        $extension = strtolower($file->getClientOriginalExtension());

        $text = match ($extension) {
            'txt' => $this->extractTxt($path),
            'pdf' => $this->extractPdf($path),
            'doc', 'docx' => $this->extractDoc($path),
            default => '',
        };

        Log::info('CvTextExtractor: Extraction result', [
            'file' => $file->getClientOriginalName(),
            'extension' => $extension,
            'size_kb' => round($file->getSize() / 1024, 1),
            'chars_extracted' => strlen(trim($text)),
            'text_preview' => strlen(trim($text)) > 200 ? substr(trim($text), 0, 200).'...' : trim($text),
        ]);

        return $text;
    }

    private function extractTxt(string $path): string
    {
        return file_get_contents($path) ?: '';
    }

    private function extractPdf(string $path): string
    {
        // Step 1: Try text-layer extraction (fast, accurate for digital PDFs)
        try {
            $pdfText = Pdf::getText($path);

            if (strlen(trim($pdfText)) > 100) {
                return $pdfText;
            }

            Log::info('CvTextExtractor: Text layer too short, falling back to OCR');
        } catch (\Throwable $e) {
            Log::warning('CvTextExtractor: PDF text extraction failed', ['error' => $e->getMessage()]);
        }

        // Step 2: OCR all pages as fallback
        if (extension_loaded('imagick')) {
            return $this->ocrPdf($path);
        }

        Log::warning('CvTextExtractor: Imagick not available, cannot OCR');

        return '';
    }

    private function ocrPdf(string $path): string
    {
        try {
            $imagick = new \Imagick;
            $imagick->setResolution(300, 300);
            $imagick->readImage($path);

            $allText = [];
            $pageCount = $imagick->getNumberImages();

            Log::info('CvTextExtractor: OCR starting', ['pages' => $pageCount]);

            foreach ($imagick as $i => $page) {
                $page->setImageFormat('png');

                $tempImage = sys_get_temp_dir().'/'.uniqid("cv_ocr_p{$i}_", true).'.png';
                $page->writeImage($tempImage);

                $ocrText = (new TesseractOCR($tempImage))
                    ->lang('eng')
                    ->run();

                @unlink($tempImage);

                if (strlen(trim($ocrText)) > 20) {
                    $allText[] = trim($ocrText);
                }
            }

            $imagick->clear();

            $combined = implode("\n\n", $allText);

            Log::info('CvTextExtractor: OCR completed', [
                'pages_processed' => count($allText),
                'total_chars' => strlen($combined),
            ]);

            return $combined;
        } catch (\Throwable $e) {
            Log::error('CvTextExtractor: OCR extraction failed', ['error' => $e->getMessage()]);

            return '';
        }
    }

    private function extractDoc(string $path): string
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        // DOCX: parse the XML inside the ZIP
        if ($extension === 'docx' && class_exists(ZipArchive::class)) {
            return $this->extractDocx($path);
        }

        // DOC: strip non-printable bytes as fallback
        $raw = file_get_contents($path) ?: '';

        return preg_replace('/[^\x20-\x7E\n\r\t]/', ' ', $raw) ?? '';
    }

    private function extractDocx(string $path): string
    {
        try {
            $zip = new ZipArchive;

            if ($zip->open($path) !== true) {
                return '';
            }

            $xml = $zip->getFromName('word/document.xml');
            $zip->close();

            if (! $xml) {
                return '';
            }

            // Parse the XML and extract text from <w:t> elements
            $dom = new \DOMDocument;
            $dom->loadXML($xml);

            $paragraphs = $dom->getElementsByTagName('p');
            $lines = [];

            foreach ($paragraphs as $paragraph) {
                $texts = $paragraph->getElementsByTagName('t');
                $lineText = '';

                foreach ($texts as $text) {
                    $lineText .= $text->nodeValue;
                }

                if (strlen(trim($lineText)) > 0) {
                    $lines[] = trim($lineText);
                }
            }

            return implode("\n", $lines);
        } catch (\Throwable $e) {
            Log::error('CvTextExtractor: DOCX extraction failed', ['error' => $e->getMessage()]);

            return '';
        }
    }
}
