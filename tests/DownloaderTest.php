<?php

namespace FileDownloader\Tests;

use FileDownloader\Downloader;
use FileDownloader\Tests\Fixture\ConnectorPayloadFixture;
use FileDownloader\Tests\Infrastructure\ConnectorHandler\TestConnectorHandler;
use FileDownloader\Tests\Infrastructure\ConnectorHandler\ThrowingErrorConnectorHandler;
use FileDownloader\ValueObject\Connector;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @covers \FileDownloader\Downloader
 */
class DownloaderTest extends TestCase
{
    public function testShouldDownloadFileContent(): void
    {
        // Given
        $connectionConfigurationPayload = ConnectorPayloadFixture::aConnectorThatHasBeenDownloaded();
        $connectionConfiguration = Connector::createFromArray($connectionConfigurationPayload);
        $expectedContent = 'content';
        $downloader = new Downloader(new TestConnectorHandler($expectedContent));

        // When
        $downloaderOutput = $downloader->download($connectionConfiguration);

        // Then
        self::assertTrue($downloaderOutput->hasChanged());
        self::assertSame($expectedContent, $downloaderOutput->getContent()->getValue());
        self::assertFalse($downloaderOutput->isBlocked());
    }

    public function testShouldIncrementFailureCountAndMarkConnectorAsWarningWhenFileCannotBeDownloaded(): void
    {
        // Given
        $connectionConfigurationPayload = ConnectorPayloadFixture::aConnectorThatHasBeenDownloaded();
        $connectionConfiguration = Connector::createFromArray($connectionConfigurationPayload);
        $downloader = new Downloader(new ThrowingErrorConnectorHandler());

        // When
        $downloaderOutput = $downloader->download($connectionConfiguration);

        // Then
        self::assertFalse($downloaderOutput->hasChanged());
        self::assertFalse($downloaderOutput->isBlocked());
        self::assertNull($downloaderOutput->getContent());
    }

    public function testShouldIncrementFailureCountAndMarkConnectorAsBlockedWhenFileCannotBeDownloaded(): void
    {
        // Given
        $connectionConfigurationPayload = ConnectorPayloadFixture::aBlahThatHasWarning();
        $connectionConfiguration = Connector::createFromArray($connectionConfigurationPayload);
        $downloader = new Downloader(new ThrowingErrorConnectorHandler());

        // When
        $downloaderOutput = $downloader->download($connectionConfiguration);

        // Then
        self::assertFalse($downloaderOutput->hasChanged());
        self::assertTrue($downloaderOutput->isBlocked());
        self::assertNull($downloaderOutput->getContent());
    }

    public function testShouldThrowExceptionBecauseConnectorIsNotSupported(): void
    {
        // Given
        $connectionConfigurationPayload = ConnectorPayloadFixture::aConnectorThatHasBeenDownloaded();
        $connectionConfiguration = Connector::createFromArray($connectionConfigurationPayload);
        $downloader = new Downloader();

        // Expect
        $this->expectException(RuntimeException::class);

        // When
        $downloader->download($connectionConfiguration);
    }
}
