<?php

declare(strict_types=1);

namespace FileDownloader;

use MyCLabs\Enum\Enum;

final class ConnectorStatus extends Enum
{
    private const OK = 'OK';
    private const WARNING = 'WARNING';
    private const BLOCKED = 'BLOCKED';

    public static function OK(): self
    {
        return new ConnectorStatus(self::OK);
    }

    public static function WARNING(): self
    {
        return new ConnectorStatus(self::WARNING);
    }

    public static function BLOCKED(): self
    {
        return new ConnectorStatus(self::BLOCKED);
    }
}