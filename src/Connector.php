<?php

declare(strict_types=1);

namespace FileDownloader;

use FileDownloader\ValueObject\ConnectionConfigurationish;

interface Connector
{
    public function supports(ConnectionConfigurationish $connectionConfiguration): bool;

    public function download(ConnectionConfigurationish $connectionConfiguration): string;
}
