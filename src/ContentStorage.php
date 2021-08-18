<?php

namespace FileDownloader;

use FileDownloader\ValueObject\ConnectionConfigurationish;

interface ContentStorage
{
    public function save(ConnectionConfigurationish $configurationish, string $content): void;
}
