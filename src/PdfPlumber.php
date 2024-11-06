<?php

declare(strict_types=1);

namespace Chaungoclong\PdfplumberPhp;

use Exception;
use RuntimeException;
use Symfony\Component\Process\Process;
use Throwable;

class PdfPlumber
{
    private string $pythonScript = __DIR__ . '/../bin/pdf.py';

    /**
     * @throws Exception
     */
    private function runProcess(string $command, string $pdfFilePath, ?int $pageNumber = null): array
    {
        $args = ['python3', $this->pythonScript, $command, $pdfFilePath];

        if ($pageNumber !== null) {
            $args[] = (string)$pageNumber;
        }

        $process = new Process($args);

        try {
            $process->mustRun();
            $output = $process->getOutput();
            $data = json_decode($output, true, 512, JSON_THROW_ON_ERROR);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new RuntimeException("Error decoding JSON response.");
            }

            return $data;
        } catch (Throwable $throwable) {
            throw new RuntimeException("Process failed: " . $throwable->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function extractText(string $pdfFilePath, ?int $pageNumber = null): array
    {
        return $this->runProcess('text', $pdfFilePath, $pageNumber);
    }

    /**
     * @throws Exception
     */
    public function extractTables(string $pdfFilePath, ?int $pageNumber = null): array
    {
        return $this->runProcess('tables', $pdfFilePath, $pageNumber);
    }

    /**
     * @throws Exception
     */
    public function extractMetadata(string $pdfFilePath): array
    {
        return $this->runProcess('metadata', $pdfFilePath);
    }

    /**
     * @throws Exception
     */
    public function extractImages(string $pdfFilePath, ?int $pageNumber = null): array
    {
        return $this->runProcess('images', $pdfFilePath, $pageNumber);
    }
}