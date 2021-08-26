<?php

namespace FileDownloader\Tests;

use FileDownloader\ConnectorStatus;
use FileDownloader\Download;
use FileDownloader\SharedKernel\DateTimeHelper;
use FileDownloader\ValueObject\Content;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FileDownloader\Download
 */
class DownloadTest extends TestCase
{
    public function testShouldMarkConnectorThatNeverBeenDownloadedAsSuccess(): void
    {
        // Given
        $downloadOutput = new Download(ConnectorStatus::OK(), 0, null, null);

        // When
        $downloadOutput->markAsSuccess(new Content('test'));

        // Then
        $this->assertThatContentChanged($downloadOutput);
    }

    private function assertThatContentChanged(Download $downloadOutput): void
    {
        self::assertTrue($downloadOutput->hasChanged());
        self::assertNotNull($downloadOutput->getContent());
        self::assertFalse($downloadOutput->isBlocked());

        self::assertSame(0, $downloadOutput->toArray()['failure_counter']);
        self::assertSame('OK', $downloadOutput->toArray()['status']);
        self::assertNull($downloadOutput->toArray()['blocked_at']);
    }

    public function testShouldMarkConnectorThatHasBeenDownloadedAsSuccess(): void
    {
        // Given
        $downloadOutput = new Download(
            ConnectorStatus::OK(),
            0,
            'somePreviousChecksum',
            DateTimeHelper::create('2021-08-12 23:00:34')
        );

        // When
        $downloadOutput->markAsSuccess(new Content('test'));

        // Then
        $this->assertThatContentChanged($downloadOutput);
    }

    public function testShouldMarkConnectorWithWarningAsSuccess(): void
    {
        // Given
        $downloadOutput = new Download(
            ConnectorStatus::WARNING(),
            2,
            'somePreviousChecksum',
            DateTimeHelper::create('2021-08-12 23:00:34')
        );

        // When
        $downloadOutput->markAsSuccess(new Content('test'));

        // Then
        $this->assertThatContentChanged($downloadOutput);
    }

    public function testShouldMarkConnectorThatHasNotChangedAsSuccess(): void
    {
        // Given
        $downloadOutput = new Download(
            ConnectorStatus::OK(),
            0,
            '098f6bcd4621d373cade4e832627b4f6',
            DateTimeHelper::create('2021-08-12 23:00:34')
        );

        // When
        $downloadOutput->markAsSuccess(new Content('test'));

        // Then
        self::assertFalse($downloadOutput->hasChanged());
        self::assertNull($downloadOutput->getContent());
        self::assertFalse($downloadOutput->isBlocked());
    }

    public function testShouldMarkConnectorAsWarning(): void
    {
        // Given
        $downloadOutput = new Download(
            ConnectorStatus::OK(),
            0,
            '098f6bcd4621d373cade4e832627b4f6',
            DateTimeHelper::create('2021-08-12 23:00:34')
        );

        // When
        $downloadOutput->addFailure();

        // Then
        self::assertFalse($downloadOutput->hasChanged());
        self::assertNull($downloadOutput->getContent());
        self::assertFalse($downloadOutput->isBlocked());

        self::assertSame(1, $downloadOutput->toArray()['failure_counter']);
        self::assertNull($downloadOutput->toArray()['blocked_at']);
        self::assertNotNull($downloadOutput->toArray()['last_downloaded_at']);
        self::assertNotNull($downloadOutput->toArray()['checksum']);
    }

    public function testShouldMarkConnectorThatHasBeenDownloadedAsBlocked(): void
    {
        // Given
        $downloadOutput = new Download(
            ConnectorStatus::WARNING(),
            4,
            '098f6bcd4621d373cade4e832627b4f6',
            DateTimeHelper::create('2021-08-12 23:00:34')
        );

        // When
        $downloadOutput->addFailure();

        // Then
        self::assertFalse($downloadOutput->hasChanged());
        self::assertNull($downloadOutput->getContent());
        self::assertTrue($downloadOutput->isBlocked());

        self::assertSame('BLOCKED', $downloadOutput->toArray()['status']);
        self::assertSame(5, $downloadOutput->toArray()['failure_counter']);
        self::assertNotNull($downloadOutput->toArray()['blocked_at']);
        self::assertNotNull($downloadOutput->toArray()['last_downloaded_at']);
        self::assertNotNull($downloadOutput->toArray()['checksum']);
    }

    public function testShouldMarkConnectorThatHasNotBeenDownloadedAsBlocked(): void
    {
        // Given
        $downloadOutput = new Download(ConnectorStatus::WARNING(), 4, null, null);

        // When
        $downloadOutput->addFailure();

        // Then
        self::assertFalse($downloadOutput->hasChanged());
        self::assertNull($downloadOutput->getContent());
        self::assertTrue($downloadOutput->isBlocked());

        self::assertSame('BLOCKED', $downloadOutput->toArray()['status']);
        self::assertSame(5, $downloadOutput->toArray()['failure_counter']);
        self::assertNotNull($downloadOutput->toArray()['blocked_at']);
        self::assertNull($downloadOutput->toArray()['last_downloaded_at']);
        self::assertNull($downloadOutput->toArray()['checksum']);
    }
}
