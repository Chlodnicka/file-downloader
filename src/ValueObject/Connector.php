<?php

declare(strict_types=1);

namespace FileDownloader\ValueObject;

use DateTimeImmutable;
use FileDownloader\ConnectorStatus;
use FileDownloader\Download;
use FileDownloader\SharedKernel\DateTimeHelper;

final class Connector
{
    private int $id;
    private int $supplierId;
    private array $configuration;
    private string $fileExtension;
    private ConnectorStatus $status;
    private int $failureCounter;
    private ?string $checksum;
    private ?DateTimeImmutable $lastDownloadedAt;

    public function __construct(
        int $id,
        int $supplierId,
        array $connectionConfiguration,
        string $fileExtension,
        int $failureCounter,
        string $status,
        ?string $checksum,
        ?string $lastDownloadedAt
    ) {
        $this->id = $id;
        $this->supplierId = $supplierId;
        $this->configuration = $connectionConfiguration;
        $this->fileExtension = $fileExtension;
        $this->status = ConnectorStatus::from($status);
        $this->failureCounter = $failureCounter;
        $this->checksum = $checksum;
        $this->lastDownloadedAt = $lastDownloadedAt ? DateTimeHelper::create($lastDownloadedAt) : null;
    }

    public static function createFromArray(array $payload): Connector
    {
        return new Connector(
            $payload['id'],
            $payload['supplier_id'],
            $payload['connection_configuration'],
            $payload['file_extension'],
            $payload['failure_counter'],
            $payload['status'],
            $payload['checksum'],
            $payload['last_downloaded_at']
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getSupplierId(): int
    {
        return $this->supplierId;
    }

    public function getFileExtension(): string
    {
        return $this->fileExtension;
    }

    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    public function initializeDownload(): Download
    {
        return new Download(
            $this->status,
            $this->failureCounter,
            $this->checksum,
            $this->lastDownloadedAt
        );
    }
}
