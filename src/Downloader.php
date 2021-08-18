<?php

declare(strict_types=1);

namespace FileDownloader;

use FileDownloader\ValueObject\ConnectionConfigurationish;
use RuntimeException;

final class Downloader
{
    /** @var Connector[] */
    private array $connectors;

    public function __construct(Connector ...$connectors)
    {
        $this->connectors = $connectors;
    }

    public function download(ConnectionConfigurationish $connectionConfiguration): string
    {
        foreach ($this->connectors as $connector) {
            if ($connector->supports($connectionConfiguration)) {
                return $connector->download($connectionConfiguration);
            }
        }

        throw new RuntimeException('Cannot find connector');
    }
}
