<?php

declare(strict_types=1);

namespace FileDownloader;

use FileDownloader\ValueObject\Connector;
use FileDownloader\ValueObject\Content;

interface ConnectorHandler
{
    public function supports(Connector $connectionConfiguration): bool;

    public function download(Connector $connectionConfiguration): Content;
}
