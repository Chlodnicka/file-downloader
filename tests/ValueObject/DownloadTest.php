<?php

namespace FileDownloader\Tests\ValueObject;

use FileDownloader\SharedKernel\DateTimeHelper;
use FileDownloader\ValueObject\Download;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FileDownloader\ValueObject\Download
 */
class DownloadTest extends TestCase
{
    public function testShouldCheckThatDownloadsAreNotEqual(): void
    {
        // Given
        $lastDownload = new Download('some_checksum', DateTimeHelper::create('2021-01-22 23:23:25'));
        $currentDownload = new Download('some_other_checksum', DateTimeHelper::now());

        // When
        $result = $currentDownload->isEqual($lastDownload);

        // Then
        self::assertFalse($result);
    }

    public function testShouldCheckThatDownloadsAreNotEqualBecauseThereIsNoLastDownload(): void
    {
        // Given
        $currentDownload = new Download('some_other_checksum', DateTimeHelper::now());

        // When
        $result = $currentDownload->isEqual(null);

        // Then
        self::assertFalse($result);
    }

    public function testShouldCheckThatDownloadsAreEqual(): void
    {
        // Given
        $lastDownload = new Download('some_checksum', DateTimeHelper::create('2021-01-22 23:23:25'));
        $currentDownload = new Download('some_checksum', DateTimeHelper::now());

        // When
        $result = $currentDownload->isEqual($lastDownload);

        // Then
        self::assertTrue($result);
    }
}
