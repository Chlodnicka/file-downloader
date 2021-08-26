<?php

namespace FileDownloader\Tests;

use FileDownloader\Downloader;
use FileDownloader\FileDownloaderService;
use FileDownloader\Tests\Fixture\ConnectorPayloadFixture;
use FileDownloader\Tests\Infrastructure\ConnectorHandler\TestConnectorHandler;
use FileDownloader\Tests\Infrastructure\ConnectorHandler\ThrowingErrorConnectorHandler;
use FileDownloader\Tests\Infrastructure\ConnectorsInMemory;
use FileDownloader\Tests\Infrastructure\ContentStorageInMemory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FileDownloader\FileDownloaderService
 */
class FileDownloaderServiceTest extends TestCase
{
    private const SUPPLIER_ID = 1;

    public function testShouldDownloadFileThatHasNeverBeenDownloaded(): void
    {
        // Given
        $connectorPayload = ConnectorPayloadFixture::aConnectorThatHasNeverBeenDownloaded();
        $fileDownloaderService = new FileDownloaderService(
            new ConnectorsInMemory([$connectorPayload['id'] => $connectorPayload]),
            new Downloader(new TestConnectorHandler('some content')),
            new ContentStorageInMemory()
        );

        // When
        $result = $fileDownloaderService->download(self::SUPPLIER_ID);

        // Then
        self::assertTrue($result);
    }

    public function testShouldDownloadFileThatHasBeenDownloaded(): void
    {
        // Given
        $connectorPayload = ConnectorPayloadFixture::aConnectorThatHasBeenDownloaded();
        $fileDownloaderService = new FileDownloaderService(
            new ConnectorsInMemory([$connectorPayload['id'] => $connectorPayload]),
            new Downloader(new TestConnectorHandler('some content')),
            new ContentStorageInMemory()
        );

        // When
        $result = $fileDownloaderService->download(self::SUPPLIER_ID);

        // Then
        self::assertTrue($result);
    }

    public function testShouldNotDownloadFileBecauseCannotConnectToEndpoint(): void
    {
        // Given
        $connectorPayload = ConnectorPayloadFixture::aConnectorThatHasBeenDownloaded();
        $fileDownloaderService = new FileDownloaderService(
            new ConnectorsInMemory([$connectorPayload['id'] => $connectorPayload]),
            new Downloader(new ThrowingErrorConnectorHandler()),
            new ContentStorageInMemory()
        );

        // When
        $result = $fileDownloaderService->download(self::SUPPLIER_ID);

        // Then
        self::assertFalse($result);
    }

    public function testShouldNotDownloadFileBecauseFileHasNotBeenChanged(): void
    {
        // Given
        $connectorPayload = ConnectorPayloadFixture::aConnectorThatHasBeenDownloaded();
        $fileDownloaderService = new FileDownloaderService(
            new ConnectorsInMemory([$connectorPayload['id'] => $connectorPayload]),
            new Downloader(new TestConnectorHandler('same content')),
            new ContentStorageInMemory()
        );

        // When
        $result = $fileDownloaderService->download(self::SUPPLIER_ID);

        // Then
        self::assertFalse($result);
    }

    public function testShouldNotDownloadFileBecauseFileHasNotBeenChangedButShouldUpdateLastCheckedTimestamp(): void
    {
        // Given
        $connectorPayload = ConnectorPayloadFixture::aConnectorThatHasBeenDownloaded();
        $connectorRepository = new ConnectorsInMemory([$connectorPayload['id'] => $connectorPayload]);
        $fileDownloaderService = new FileDownloaderService(
            $connectorRepository,
            new Downloader(new TestConnectorHandler('same content')),
            new ContentStorageInMemory()
        );

        // When
        $result = $fileDownloaderService->download(self::SUPPLIER_ID);

        // Then
        self::assertFalse($result);
    }
}
