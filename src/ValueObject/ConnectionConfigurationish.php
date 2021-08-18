<?php

declare(strict_types=1);

namespace FileDownloader\ValueObject;

use FileDownloader\SharedKernel\DateTimeHelper;

final class ConnectionConfigurationish
{
    private int $id;
    private int $supplierId;
    private array $connectionConfiguration;
    private string $fileExtension;
    private ?Download $lastDownload;

    public function __construct(
        int $id,
        int $supplierId,
        array $connectionConfiguration,
        string $fileExtension,
        ?string $checksum,
        ?string $lastDownloadedAt
    ) {
        $this->id = $id;
        $this->supplierId = $supplierId;
        $this->connectionConfiguration = $connectionConfiguration;
        $this->fileExtension = $fileExtension;
        $this->lastDownload = $checksum && $lastDownloadedAt ?
            new Download($checksum, DateTimeHelper::create($lastDownloadedAt)) : null;
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

    public function getLastDownload(): ?Download
    {
        return $this->lastDownload;
    }
}
