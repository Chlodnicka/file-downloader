<?php

namespace FileDownloader\Tests;

use FileDownloader\Downloader;
use FileDownloader\FileDownloaderService;
use FileDownloader\SharedKernel\DateTimeHelper;
use FileDownloader\Tests\Fixture\ConfigurationPayloadFixture;
use FileDownloader\Tests\Infrastructure\ConnectionConfigurationsishInMemory;
use FileDownloader\Tests\Infrastructure\Connector\TestConnector;
use FileDownloader\Tests\Infrastructure\Connector\ThrowingErrorConnector;
use FileDownloader\Tests\Infrastructure\ContentStorageInMemory;
use PHPUnit\Framework\TestCase;

class FileDownloaderServiceTest extends TestCase
{
    private const SUPPLIER_ID = 1;

    public function testShouldDownloadFileThatHasNeverBeenDownloaded(): void
    {
        // Given
        $configurationish = ConfigurationPayloadFixture::aBlahThatHasNeverBeenDownloaded();
        $fileDownloaderService = new FileDownloaderService(
            new ConnectionConfigurationsishInMemory([$configurationish['id'] => $configurationish]),
            new Downloader(new TestConnector('some content')),
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
        $configurationish = ConfigurationPayloadFixture::aBlahThatHasBeenDownloaded();
        $fileDownloaderService = new FileDownloaderService(
            new ConnectionConfigurationsishInMemory([$configurationish['id'] => $configurationish]),
            new Downloader(new TestConnector('some content')),
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
        $configurationish = ConfigurationPayloadFixture::aBlahThatHasBeenDownloaded();
        $fileDownloaderService = new FileDownloaderService(
            new ConnectionConfigurationsishInMemory([$configurationish['id'] => $configurationish]),
            new Downloader(new ThrowingErrorConnector()),
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
        $configurationish = ConfigurationPayloadFixture::aBlahThatHasBeenDownloaded();
        $fileDownloaderService = new FileDownloaderService(
            new ConnectionConfigurationsishInMemory([$configurationish['id'] => $configurationish]),
            new Downloader(new TestConnector('same content')),
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
        $configurationish = ConfigurationPayloadFixture::aBlahThatHasBeenDownloaded();
        $congifurationishRepo = new ConnectionConfigurationsishInMemory([$configurationish['id'] => $configurationish]);
        $fileDownloaderService = new FileDownloaderService(
            $congifurationishRepo,
            new Downloader(new TestConnector('same content')),
            new ContentStorageInMemory()
        );

        // When
        $result = $fileDownloaderService->download(self::SUPPLIER_ID);

        // Then
        self::assertFalse($result);
        self::assertSame(
            DateTimeHelper::now()->format('Y-m-d H:i'),
            $congifurationishRepo->get($configurationish['id'])->getLastDownload()->getLastDownloadedAt()->format(
                'Y-m-d H:i'
            )
        );
    }
}
