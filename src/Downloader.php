<?php

declare(strict_types=1);

namespace FileDownloader;

use Exception;
use FileDownloader\ValueObject\Connector;
use RuntimeException;

final class Downloader
{
    /** @var ConnectorHandler[] */
    private array $connectorHandlers;

    public function __construct(ConnectorHandler ...$connectorHandlers)
    {
        $this->connectorHandlers = $connectorHandlers;
    }

    public function download(Connector $connector): Download
    {
        foreach ($this->connectorHandlers as $connectorHandler) {
            if ($connectorHandler->supports($connector)) {
                $download = $connector->initializeDownload();
                try {
                    $download->markAsSuccess($connectorHandler->download($connector));
                } catch (Exception $e) {
                    $download->addFailure();
                }
                return $download;
            }
        }

        throw new RuntimeException('Cannot find connector');
    }
}
