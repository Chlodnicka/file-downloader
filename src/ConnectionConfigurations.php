<?php

namespace FileDownloader;

use FileDownloader\ValueObject\ConnectionConfigurationish;
use FileDownloader\ValueObject\Download;

interface ConnectionConfigurations
{
    public function get(int $id): ConnectionConfigurationish;

    public function updateLastDownload(int $id, Download $lastDownload): void;
}
