<?php

namespace App\Services;

use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Spatie\PdfToText\Pdf;
use thiagoalessio\TesseractOCR\TesseractOCR;

class CvTextExtractor
{
    public function extract(TemporaryUploadedFile $file): string
    {
        $path = $file->getRealPath();
        $extension = strtolower($file->getClientOriginalExtension());

        return match ($extension) {
            'txt' => $this->extractTxt($path),
            'pdf' => $this->extractPdf($path),
            'doc', 'docx' => $this->extractDoc($path),
            default => '',
        };
    }

    private function extractTxt(string $path): string
    {
        return file_get_contents($path) ?: '';
    }

    private function extractPdf(string $path): string
    {
        try {
            $pdfText = Pdf::getText($path);

            if (strlen(trim($pdfText)) > 100) {
                return $pdfText;
            }
        } catch (\Throwable $e) {
            \Log::warning('CvTextExtractor: PDF text extraction failed', ['error' => $e->getMessage()]);
        }

        if (extension_loaded('imagick')) {
            try {
                $imagick = new \Imagick;
                $imagick->setResolution(300, 300);
                $imagick->readImage($path.'[0]');
                $imagick->setImageFormat('png');

                $tempImage = sys_get_temp_dir().'/'.uniqid('cv_ocr_', true).'.png';
                $imagick->writeImage($tempImage);
                $imagick->clear();

                $ocrText = (new TesseractOCR($tempImage))
                    ->lang('eng')
                    ->run();
                @unlink($tempImage);

                if (strlen(trim($ocrText)) > 50) {
                    return $ocrText;
                }
            } catch (\Throwable $e) {
                \Log::error('CvTextExtractor: OCR extraction failed', ['error' => $e->getMessage()]);
            }
        }

        return '';
    }

    private function extractDoc(string $path): string
    {
        $raw = file_get_contents($path) ?: '';

        return preg_replace('/[^\x20-\x7E\n\r\t]/', ' ', $raw) ?? '';
    }
}
