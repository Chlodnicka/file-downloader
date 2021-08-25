<?php

declare(strict_types=1);

namespace FileDownloader\ValueObject;

use DateTimeImmutable;
use FileDownloader\ConnectorStatusish;
use FileDownloader\SharedKernel\DateTimeHelper;

final class ConnectionConfigurationish
{
    private int $id;
    private int $supplierId;
    private array $connectionConfiguration;
    private string $fileExtension;
    private ConnectorStatusish $statusish;
    private int $failureCounter;
    private ?string $checksum;
    private ?DateTimeImmutable $lastDownloadedAt;

    public function __construct(
        int $id,
        int $supplierId,
        array $connectionConfiguration,
        string $fileExtension,
        int $failureCounter,
        string $statusish,
        ?string $checksum,
        ?string $lastDownloadedAt
    ) {
        $this->id = $id;
        $this->supplierId = $supplierId;
        $this->connectionConfiguration = $connectionConfiguration;
        $this->fileExtension = $fileExtension;
        $this->statusish = ConnectorStatusish::from($statusish);
        $this->failureCounter = $failureCounter;
        $this->checksum = $checksum;
        $this->lastDownloadedAt = $lastDownloadedAt ? DateTimeHelper::create($lastDownloadedAt) : null;
    }

    public static function createFromArray(array $payload): ConnectionConfigurationish
    {
        return new ConnectionConfigurationish(
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

    public function getConnectionConfiguration(): array
    {
        return $this->connectionConfiguration;
    }

    public function getStatusish(): ConnectorStatusish
    {
        return $this->statusish;
    }

    public function getFailureCounter(): int
    {
        return $this->failureCounter;
    }

    public function getLastDownload(): ?Download
    {
        return $this->checksum && $this->lastDownloadedAt ?
            new Download($this->checksum, $this->lastDownloadedAt) : null;
    }
}
