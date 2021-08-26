<?php

declare(strict_types=1);

namespace FileDownloader;

use DateTimeImmutable;
use FileDownloader\SharedKernel\DateTimeHelper;
use FileDownloader\ValueObject\Content;

final class Download
{
    private const MAX_FAILURE_NUMBER = 5;

    private ConnectorStatus $status;
    private int $failureCounter;
    private ?Content $content;
    private ?string $previousChecksum;
    private ?DateTimeImmutable $lastDownloadedAt;
    private ?DateTimeImmutable $blockedAt;

    public function __construct(
        ConnectorStatus $status,
        int $failureCounter,
        ?string $previousChecksum,
        ?DateTimeImmutable $lastDownloadedAt
    ) {
        $this->status = $status;
        $this->failureCounter = $failureCounter;
        $this->previousChecksum = $previousChecksum;
        $this->lastDownloadedAt = $lastDownloadedAt;
        $this->content = null;
        $this->blockedAt = null;
    }

    public function addFailure(): void
    {
        $this->failureCounter++;
        $this->status = $this->failureCounter < self::MAX_FAILURE_NUMBER ?
            ConnectorStatus::WARNING() : ConnectorStatus::BLOCKED();
        if (ConnectorStatus::BLOCKED()->equals($this->status)) {
            $this->blockedAt = DateTimeHelper::now();
        }
    }

    public function markAsSuccess(Content $content): void
    {
        $this->content = !$content->isEqual($this->previousChecksum) ? $content : null;
        $this->failureCounter = 0;
        $this->status = ConnectorStatus::OK();
        $this->lastDownloadedAt = DateTimeHelper::now();
    }

    public function isBlocked(): bool
    {
        return ConnectorStatus::BLOCKED()->equals($this->status);
    }

    public function getContent(): ?Content
    {
        return $this->content;
    }

    public function hasChanged(): bool
    {
        return $this->content !== null;
    }

    public function toArray(): array
    {
        return [
            'status'             => $this->status->getValue(),
            'failure_counter'    => $this->failureCounter,
            'checksum'           => $this->content ? $this->content->getChecksum() : $this->previousChecksum,
            'last_downloaded_at' => $this->content ?
                DateTimeHelper::now()->format(DateTimeHelper::DEFAULT_DATETIME_FORMAT) : $this->lastDownloadedAt,
            'blocked_at'         => $this->blockedAt ?
                $this->blockedAt->format(DateTimeHelper::DEFAULT_DATETIME_FORMAT) : null
        ];
    }
}
