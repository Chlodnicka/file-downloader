<?php

declare(strict_types=1);

namespace FileDownloader\SharedKernel;

use DateTimeImmutable;
use DateTimeZone;

final class DateTimeHelper
{
    public const DEFAULT_TIMEZONE = 'Europe/Warsaw';
    public const DEFAULT_DATETIME_FORMAT = 'Y-m-d H:i:s';

    public static function now(): DateTimeImmutable
    {
        return new DateTimeImmutable('now', self::defaultTimeZone());
    }

    public static function defaultTimeZone(): DateTimeZone
    {
        return new DateTimeZone(self::DEFAULT_TIMEZONE);
    }

    public static function create(string $dateTime): DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat(self::DEFAULT_DATETIME_FORMAT, $dateTime, self::defaultTimeZone());
    }
}
