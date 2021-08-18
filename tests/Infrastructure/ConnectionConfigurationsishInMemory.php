<?php

declare(strict_types=1);

namespace FileDownloader\Tests\Infrastructure;

use FileDownloader\ConnectionConfigurations;
use FileDownloader\SharedKernel\DateTimeHelper;
use FileDownloader\ValueObject\ConnectionConfigurationish;
use FileDownloader\ValueObject\Download;
use RuntimeException;

final class ConnectionConfigurationsishInMemory implements ConnectionConfigurations
{
    private array $memory;

    public function __construct(array $memory)
    {
        $this->memory = $memory;
    }

    public function get(int $id): ConnectionConfigurationish
    {
        if (!isset($this->memory[$id])) {
            throw new RuntimeException('No configuration provided');
        }
        $payload = $this->memory[$id];
        return new ConnectionConfigurationish(
            $payload['id'],
            $payload['supplier_id'],
            $payload['connection_configuration'],
            $payload['file_extension'],
            $payload['checksum'],
            $payload['last_downloaded_at']
        );
    }

    public function updateLastDownload(int $id, Download $lastDownload): void
    {
        $this->memory[$id]['checksum'] = $lastDownload->getChecksum();
        $this->memory[$id]['last_downloaded_at'] = $lastDownload->getLastDownloadedAt()->format(
            DateTimeHelper::DEFAULT_DATETIME_FORMAT
        );
    }
}
