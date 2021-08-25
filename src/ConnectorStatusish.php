<?php

declare(strict_types=1);

namespace FileDownloader;

use MyCLabs\Enum\Enum;

final class ConnectorStatusish extends Enum
{
    private const OK = 'OK';
    private const WARNING = 'WARNING';
    private const BLOCKED = 'BLOCKED';

    public static function OK(): self
    {
        return new ConnectorStatusish(self::OK);
    }

    public static function WARNING(): self
    {
        return new ConnectorStatusish(self::WARNING);
    }

    public static function BLOCKED(): self
    {
        return new ConnectorStatusish(self::BLOCKED);
    }
}