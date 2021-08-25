<?php

declare(strict_types=1);

namespace FileDownloader;

use Exception;
use FileDownloader\ValueObject\Download;

final class FileDownloaderService
{
    private ConnectionConfigurations $connectionConfigurations;
    private Downloader $downloader;
    private ContentStorage $contentStorage;

    public function __construct(
        ConnectionConfigurations $connectionConfigurations,
        Downloader $downloader,
        ContentStorage $contentStorage
    ) {
        $this->connectionConfigurations = $connectionConfigurations;
        $this->downloader = $downloader;
        $this->contentStorage = $contentStorage;
    }

    public function download(int $id): bool
    {
        try {
            $connectorConfiguration = $this->connectionConfigurations->get($id);
            $downloadOutput = $this->downloader->download($connectorConfiguration);
            if ($downloadOutput->hasFailed()) {
                //if warning
                //then update failure counter and status
                //if blocked
                //then update failure counter, status, remove checksum, last downloaded and file from storage
                return false;
            }
            $content = $downloadOutput->getContent();
            $download = Download::createFromContent($content);
            $contentHasChanged = !$download->isEqual($connectorConfiguration->getLastDownload());
            if ($contentHasChanged) {
                $this->contentStorage->save($connectorConfiguration, $content);
            }
            $this->connectionConfigurations->updateLastDownload($id, $download);
            return $contentHasChanged;
        } catch (Exception $e) {
            echo $e->getMessage();//todo log
            return false;
        }
    }
}
