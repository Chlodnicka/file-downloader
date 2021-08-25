<?php

declare(strict_types=1);

namespace FileDownloader\Tests\Fixture;

use FileDownloader\ConnectorStatusish;

final class ConfigurationPayloadFixture
{
    public const SUPPLIER_ID = 1;
    public const ID = 1;
    public const FILE_EXTENSION = 'csv';

    public static function aBlahThatHasNeverBeenDownloaded(): array
    {
        return [
            'id'                       => self::ID,
            'supplier_id'              => self::SUPPLIER_ID,
            'connection_configuration' => [],
            'file_extension'           => self::FILE_EXTENSION,
            'failure_counter'          => 0,
            'status'                   => ConnectorStatusish::OK()->getValue(),
            'checksum'                 => null,
            'last_downloaded_at'       => null
        ];
    }

    public static function aBlahThatHasBeenDownloaded(): array
    {
        return [
            'id'                       => self::ID,
            'supplier_id'              => self::SUPPLIER_ID,
            'connection_configuration' => [],
            'file_extension'           => self::FILE_EXTENSION,
            'failure_counter'          => 0,
            'status'                   => ConnectorStatusish::OK()->getValue(),
            'checksum'                 => '793953ee398d864ec40252df9554c3e6',
            'last_downloaded_at'       => '2021-05-21 11:33:54'
        ];
    }

    public static function aBlahThatHasWarning(): array
    {
        return [
            'id'                       => self::ID,
            'supplier_id'              => self::SUPPLIER_ID,
            'connection_configuration' => [],
            'file_extension'           => self::FILE_EXTENSION,
            'failure_counter'          => 4,
            'status'                   => ConnectorStatusish::WARNING()->getValue(),
            'checksum'                 => '793953ee398d864ec40252df9554c3e6',
            'last_downloaded_at'       => '2021-05-21 11:33:54'
        ];
    }

    public static function aBlahThatIsBlocked(): array
    {
        return [
            'id'                       => self::ID,
            'supplier_id'              => self::SUPPLIER_ID,
            'connection_configuration' => [],
            'file_extension'           => self::FILE_EXTENSION,
            'failure_counter'          => 5,
            'status'                   => ConnectorStatusish::BLOCKED()->getValue(),
            'checksum'                 => '793953ee398d864ec40252df9554c3e6',
            'last_downloaded_at'       => '2021-05-21 11:33:54'
        ];
    }

}
