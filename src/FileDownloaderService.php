<?php

declare(strict_types=1);

namespace FileDownloader;

use Exception;

final class FileDownloaderService
{
    private Connectors $connectors;
    private Downloader $downloader;
    private ContentStorage $contentStorage;

    public function __construct(
        Connectors $connectionConfigurations,
        Downloader $downloader,
        ContentStorage $contentStorage
    ) {
        $this->connectors = $connectionConfigurations;
        $this->downloader = $downloader;
        $this->contentStorage = $contentStorage;
    }

    public function download(int $id): bool
    {
        try {
            $connector = $this->connectors->get($id);
            $download = $this->downloader->download($connector);
            if ($download->isBlocked()) {
                $this->contentStorage->remove($connector);
            }
            if ($download->getContent()) {
                $this->contentStorage->save($connector, $download->getContent());
            }
            $this->connectors->updateLastDownload($id, $download);
            return $download->hasChanged();
        } catch (Exception $e) {
            echo $e->getMessage();//todo log
            return false;
        }
    }
}