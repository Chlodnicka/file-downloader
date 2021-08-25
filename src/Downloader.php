<?php

declare(strict_types=1);

namespace FileDownloader;

use Exception;
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

    public function download(ConnectionConfigurationish $connectionConfiguration): DownloadOutput
    {
        $downloadOutput = new DownloadOutput(
            $connectionConfiguration->getFailureCounter(),
            $connectionConfiguration->getStatusish()
        );

        foreach ($this->connectors as $connector) {
            if ($connector->supports($connectionConfiguration)) {
                try {
                    $downloadOutput->setContent($connector->download($connectionConfiguration));
                } catch (Exception $e) {
                    $downloadOutput->addFailure();
                }
                return $downloadOutput;
            }
        }

        throw new RuntimeException('Cannot find connector');
    }
}
