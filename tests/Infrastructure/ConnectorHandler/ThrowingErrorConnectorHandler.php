<?php

declare(strict_types=1);

namespace FileDownloader\Tests\Infrastructure\ConnectorHandler;

use Exception;
use FileDownloader\ConnectorHandler;
use FileDownloader\ValueObject\Connector;
use FileDownloader\ValueObject\Content;

final class ThrowingErrorConnectorHandler implements ConnectorHandler
{
    public function supports(Connector $connectionConfiguration): bool
    {
        return true;
    }

    public function download(Connector $connectionConfiguration): Content
    {
        throw new Exception('Some exception!');
    }
}
