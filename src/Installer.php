<?php

declare(strict_types=1);

namespace Chaungoclong\PdfplumberPhp;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class Installer
{
    public static function install(): void
    {
        if (!self::isPythonInstalled()) {
            echo "Python không được cài đặt. Đang tiến hành cài đặt...\n";
            self::installPython();
        } else {
            echo "Python đã được cài đặt.\n";
        }

        if (!self::isPipInstalled()) {
            echo "pip không được cài đặt. Đang tiến hành cài đặt...\n";
            self::installPip();
        } else {
            echo "pip đã được cài đặt.\n";
        }

        if (!self::isPdfplumberInstalled()) {
            echo "Thư viện pdfplumber không được cài đặt. Đang tiến hành cài đặt...\n";
            self::installPdfplumber();
        } else {
            echo "Thư viện pdfplumber đã được cài đặt.\n";
        }
    }

    private static function getPythonCommand(): string
    {
        return PHP_OS_FAMILY === 'Windows' ? 'python' : 'python3';
    }

    private static function isPythonInstalled(): bool
    {
        $process = new Process([self::getPythonCommand(), '--version']);
        $process->run();

        return $process->isSuccessful();
    }

    private static function isPipInstalled(): bool
    {
        $process = new Process([self::getPythonCommand(), '-m', 'pip', '--version']);
        $process->run();

        return $process->isSuccessful();
    }

    private static function isPdfplumberInstalled(): bool
    {
        $process = new Process([self::getPythonCommand(), '-m', 'pip', 'show', 'pdfplumber']);
        $process->run();

        return $process->isSuccessful();
    }

    private static function installPython(): void
    {
        if (PHP_OS_FAMILY === 'Windows') {
            echo "Vui lòng cài đặt Python thủ công trên Windows: https://www.python.org/downloads/\n";
        } else {
            $process = new Process(['sudo', 'apt-get', 'install', '-y', 'python3']);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            echo "Python đã được cài đặt thành công.\n";
        }
    }

    private static function installPip(): void
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $getPipUrl = 'https://bootstrap.pypa.io/get-pip.py';
            $getPipPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'get-pip.py';

            // Tải file get-pip.py
            file_put_contents($getPipPath, file_get_contents($getPipUrl));
            echo "Đã tải get-pip.py về: $getPipPath\n";

            // Chạy file get-pip.py
            $process = new Process([self::getPythonCommand(), $getPipPath]);
        } else {
            $process = new Process([self::getPythonCommand(), 'atp', 'install', 'python3-pip ']);
        }

        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        echo "pip đã được cài đặt thành công.\n";
    }

    /**
     * Cài đặt pdfplumber
     */
    private static function installPdfplumber(): void
    {
        $process = new Process([self::getPythonCommand(), '-m', 'pip', 'install', 'pdfplumber']);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        echo "Thư viện pdfplumber đã được cài đặt thành công.\n";
    }
}