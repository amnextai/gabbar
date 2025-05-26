<?php

declare(strict_types=1);

namespace Ai\Infrastructure\Services\DocumentReader\Readers;

use Ai\Infrastructure\Services\DocumentReader\DocumentReaderInterface;
use Override;

class PlainTextDocumentReader implements DocumentReaderInterface
{
    private const SUPPORTED_IDENTIFIERS = [
        'text/plain',
        'txt',
        'text',
        'log'
    ];

    #[Override]
    public function supports(string $identifier): bool
    {
        $identifier = strtolower(trim($identifier));
        return in_array($identifier, self::SUPPORTED_IDENTIFIERS, true)
            || $this->isPlainText($identifier);
    }

    #[Override]
    public function read(string $contents, ?int $max = null): string
    {
        return is_null($max) ? $contents : mb_substr($contents, 0, $max);
    }

    private function isPlainText(string $content): bool
    {
        return ctype_print(str_replace(["\n", "\r", "\t"], '', $content));
    }
}
