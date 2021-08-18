<?php

declare(strict_types=1);

namespace FileDownloader\ValueObject;

use DateTimeImmutable;
use FileDownloader\SharedKernel\DateTimeHelper;

final class Download
{
    private string $checksum;
    private DateTimeImmutable $lastDownloadedAt;

    public function __construct(string $checksum, DateTimeImmutable $lastDownloadedAt)
    {
        $this->checksum = $checksum;
        $this->lastDownloadedAt = $lastDownloadedAt;
    }

    public static function createFromContent(string $content): Download
    {
        return new self(md5($content), DateTimeHelper::now());
    }

    public function isEqual(?Download $download): bool
    {
        return $download && $download->checksum === $this->checksum;
    }

    public function getChecksum(): string
    {
        return $this->checksum;
    }

    public function getLastDownloadedAt(): DateTimeImmutable
    {
        return $this->lastDownloadedAt;
    }
}
