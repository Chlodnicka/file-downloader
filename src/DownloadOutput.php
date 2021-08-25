<?php

declare(strict_types=1);

namespace FileDownloader;

final class DownloadOutput
{
    private const MAX_FAILURE_NUMBER = 5;

    private int $failureCounter;
    private ConnectorStatusish $connectorStatusish;
    private ?string $content;
    private ?string $checksum;

    public function __construct(int $failureCounter, ConnectorStatusish $connectorStatusish)
    {
        $this->failureCounter = $failureCounter;
        $this->connectorStatusish = $connectorStatusish;
        $this->content = null;
    }

    public function addFailure(): void
    {
        $this->failureCounter++;
        $this->connectorStatusish = $this->failureCounter < self::MAX_FAILURE_NUMBER ?
            ConnectorStatusish::WARNING() : ConnectorStatusish::BLOCKED();
    }

    public function hasFailed(): bool
    {
        return !$this->connectorStatusish->equals(ConnectorStatusish::OK());
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
        $this->checksum = md5($content);
        $this->failureCounter = 0;
        $this->connectorStatusish = ConnectorStatusish::OK();
    }

    public function getFailureCounter(): int
    {
        return $this->failureCounter;
    }

    public function getConnectorStatusish(): ConnectorStatusish
    {
        return $this->connectorStatusish;
    }
}
