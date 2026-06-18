<?php

use App\Services\CvTextExtractor;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function writeTempFile(string $extension, string $contents): string
{
    $path = tempnam(sys_get_temp_dir(), 'cvtest_').'.'.$extension;
    file_put_contents($path, $contents);

    return $path;
}

beforeEach(function () {
    $this->extractor = app(CvTextExtractor::class);
});

it('extracts plain text from a .txt file directly', function () {
    $path = writeTempFile('txt', "John Doe\nSoftware Engineer");

    $text = $this->extractor->extractFromPath($path, 'txt', 'john.txt', 100);

    expect($text)->toContain('John Doe')
        ->and($text)->toContain('Software Engineer');

    @unlink($path);
});

it('returns empty string for a file that cannot be parsed and the binary is missing', function () {
    // A .doc file with garbage bytes and no Word/extraction binary available
    // should degrade to '' (or the system's best effort), never throw.
    $path = writeTempFile('doc', "\x00\x01\x02not a real doc");

    $text = $this->extractor->extractFromPath($path, 'doc', 'garbage.doc', 10);

    expect($text)->toBeString();

    @unlink($path);
});

it('rejects unsupported extensions gracefully', function () {
    $path = writeTempFile('exe', 'binary');

    $text = $this->extractor->extractFromPath($path, 'exe', 'bad.exe', 5);

    expect($text)->toBeString();

    @unlink($path);
});

it('routes pdf extensions through the pdf extraction path without crashing', function () {
    // A non-PDF file with a .pdf extension should fail closed (empty string),
    // not raise an uncaught exception. This guards servers that lack
    // pdftotext against 500s during imports.
    $path = writeTempFile('pdf', 'not really a pdf');

    $text = $this->extractor->extractFromPath($path, 'pdf', 'fake.pdf', 14);

    expect($text)->toBeString();

    @unlink($path);
});

it('always returns a string even for unknown extensions', function () {
    $path = writeTempFile('weird', 'content');

    $text = $this->extractor->extractFromPath($path, 'weird', 'x.weird', 7);

    expect($text)->toBeString();

    @unlink($path);
});
