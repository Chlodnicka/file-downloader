<?php

declare(strict_types=1);

namespace FileDownloader\Tests\Infrastructure\ConnectorHandler;

use FileDownloader\ConnectorHandler;
use FileDownloader\ValueObject\Connector;
use FileDownloader\ValueObject\Content;

final class TestConnectorHandler implements ConnectorHandler
{
    private string $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function supports(Connector $connectionConfiguration): bool
    {
        return true;
    }

    public function download(Connector $connectionConfiguration): Content
    {
        return new Content($this->content);
    }
}
