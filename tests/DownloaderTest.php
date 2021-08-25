<?php

namespace FileDownloader\Tests;

use FileDownloader\ConnectorStatusish;
use FileDownloader\Downloader;
use FileDownloader\Tests\Fixture\ConfigurationPayloadFixture;
use FileDownloader\Tests\Infrastructure\Connector\TestConnector;
use FileDownloader\Tests\Infrastructure\Connector\ThrowingErrorConnector;
use FileDownloader\ValueObject\ConnectionConfigurationish;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FileDownloader\Downloader
 */
class DownloaderTest extends TestCase
{
    public function testShouldDownloadFileContent(): void
    {
        // Given
        $connectionConfigurationPayload = ConfigurationPayloadFixture::aBlahThatHasBeenDownloaded();
        $connectionConfiguration = ConnectionConfigurationish::createFromArray($connectionConfigurationPayload);
        $expectedContent = 'content';
        $downloader = new Downloader(new TestConnector($expectedContent));

        // When
        $downloaderOutput = $downloader->download($connectionConfiguration);

        // Then
        self::assertSame($expectedContent, $downloaderOutput->getContent());
    }

    public function testShouldIncrementFailureCountAndMarkConnectorAsWarningWhenFileCannotBeDownloaded(): void
    {
        // Given
        $connectionConfigurationPayload = ConfigurationPayloadFixture::aBlahThatHasBeenDownloaded();
        $connectionConfiguration = ConnectionConfigurationish::createFromArray($connectionConfigurationPayload);
        $downloader = new Downloader(new ThrowingErrorConnector());

        // When
        $downloaderOutput = $downloader->download($connectionConfiguration);

        // Then
        self::assertTrue($downloaderOutput->hasFailed());
        self::assertSame(1, $downloaderOutput->getFailureCounter());
        self::assertTrue(ConnectorStatusish::WARNING()->equals($downloaderOutput->getConnectorStatusish()));
    }

    public function testShouldIncrementFailureCountAndMarkConnectorAsBlockedWhenFileCannotBeDownloaded(): void
    {
        $connectionConfigurationPayload = ConfigurationPayloadFixture::aBlahThatHasWarning();
        $connectionConfiguration = ConnectionConfigurationish::createFromArray($connectionConfigurationPayload);
        $downloader = new Downloader(new ThrowingErrorConnector());

        // When
        $downloaderOutput = $downloader->download($connectionConfiguration);

        // Then
        self::assertTrue($downloaderOutput->hasFailed());
        self::assertSame(5, $downloaderOutput->getFailureCounter());
        self::assertTrue(ConnectorStatusish::BLOCKED()->equals($downloaderOutput->getConnectorStatusish()));
    }
}
