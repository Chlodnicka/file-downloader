<?php

declare(strict_types=1);

namespace FileDownloader\Tests\Infrastructure\Connector;

use Exception;
use FileDownloader\Connector;
use FileDownloader\ValueObject\ConnectionConfigurationish;

final class ThrowingErrorConnector implements Connector
{
    public function supports(ConnectionConfigurationish $connectionConfiguration): bool
    {
        return true;
    }

    public function download(ConnectionConfigurationish $connectionConfiguration): string
    {
        throw new Exception('Some exception!');
    }
}
