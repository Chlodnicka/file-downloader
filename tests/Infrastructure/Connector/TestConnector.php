<?php

declare(strict_types=1);

namespace FileDownloader\Tests\Infrastructure\Connector;

use FileDownloader\Connector;
use FileDownloader\ValueObject\ConnectionConfigurationish;

final class TestConnector implements Connector
{
    private string $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function supports(ConnectionConfigurationish $connectionConfiguration): bool
    {
        return true;
    }

    public function download(ConnectionConfigurationish $connectionConfiguration): string
    {
        return $this->content;
    }
}
