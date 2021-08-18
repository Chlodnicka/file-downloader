<?php

declare(strict_types=1);

namespace FileDownloader\Tests\Infrastructure;

use FileDownloader\ContentStorage;
use FileDownloader\ValueObject\ConnectionConfigurationish;

final class ContentStorageInMemory implements ContentStorage
{
    private array $memory = [];
    private ?string $lastSavedId;

    public function save(ConnectionConfigurationish $configurationish, string $content): void
    {
        $id = $configurationish->getSupplierId() . '_' . $configurationish->getId();
        $this->lastSavedId = $id;
        $this->memory[$id] = [
            'content'   => $content,
            'extension' => $configurationish->getFileExtension()
        ];
    }

    public function getLastSavedId(): ?string
    {
        return $this->lastSavedId;
    }

    public function getLastSaved(): ?array
    {
        return $this->memory[$this->lastSavedId] ?? null;
    }
}
