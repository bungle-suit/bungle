<?php

declare(strict_types=1);

namespace Bungle\Framework\Export;

use RuntimeException;
use Symfony\Component\ErrorHandler\ErrorHandler;

class FS implements FSInterface
{
    private string $temDir;

    public function __construct(string $temDir = null)
    {
        $this->temDir = $temDir ?? sys_get_temp_dir();
    }

    public function createTempFile(string $prefix, ?string $content = null): string
    {
        if (($r = tempnam($this->temDir, $prefix)) === false) {
            throw new RuntimeException('Failed create tmpFile');
        }
        if ($content !== null) {
            ErrorHandler::call(fn() => file_put_contents($r, $content, LOCK_EX));
        }

        return $r;
    }

    public function removeFile(string $path): void
    {
        unlink($path);
    }

    public function filesize(string $path): int
    {
        return ErrorHandler::call(fn() => filesize($path));
    }

    public function readFile(string $path, string $charset = ''): string
    {
        $r = file_get_contents($path);
        if ($r === false) {
            throw new RuntimeException("Read file %path failed");
        }

        if ($charset !== '') {
            $r = mb_convert_encoding($r, 'UTF-8', $charset);
        }

        return $r;
    }
}
