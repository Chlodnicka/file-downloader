<?php

namespace FileDownloader;

use FileDownloader\ValueObject\Connector;

interface Connectors
{
    public function get(int $id): Connector;

    public function updateLastDownload(int $id, Download $lastDownload): void;
}
