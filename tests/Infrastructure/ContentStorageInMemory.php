<?php

declare(strict_types=1);

namespace FileDownloader\Tests\Infrastructure;

use FileDownloader\ContentStorage;
use FileDownloader\ValueObject\Connector;
use FileDownloader\ValueObject\Content;

final class ContentStorageInMemory implements ContentStorage
{
    private array $memory = [];
    private ?string $lastSavedId;

    public function save(Connector $connector, Content $content): void
    {
        $id = $connector->getSupplierId() . '_' . $connector->getId();
        $this->lastSavedId = $id;
        $this->memory[$id] = [
            'content'   => $content->getValue(),
            'extension' => $connector->getFileExtension()
        ];
    }

    public function remove(Connector $connector): void
    {
        $id = $connector->getSupplierId() . '_' . $connector->getId();
        unset($this->memory[$id]);
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
