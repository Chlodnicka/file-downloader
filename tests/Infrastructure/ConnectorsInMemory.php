<?php

declare(strict_types=1);

namespace FileDownloader\Tests\Infrastructure;

use FileDownloader\Connectors;
use FileDownloader\Download;
use FileDownloader\ValueObject\Connector;
use RuntimeException;

final class ConnectorsInMemory implements Connectors
{
    private array $memory;

    public function __construct(array $memory)
    {
        $this->memory = $memory;
    }

    public function get(int $id): Connector
    {
        if (!isset($this->memory[$id])) {
            throw new RuntimeException('No configuration provided');
        }
        return Connector::createFromArray($this->memory[$id]);
    }

    public function updateLastDownload(int $id, Download $lastDownload): void
    {
        $this->memory[$id] = array_merge($this->memory, $lastDownload->toArray());
    }
}
