<?php

declare(strict_types=1);

namespace FileDownloader\ValueObject;

use Webmozart\Assert\Assert;

final class Content
{
    private string $value;
    private string $checksum;

    public function __construct(string $value)
    {
        Assert::stringNotEmpty($value);
        $this->value = $value;
        $this->checksum = md5($value);
    }

    public function isEqual(?string $checksum): bool
    {
        return $checksum && $checksum === $this->checksum;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getChecksum(): string
    {
        return $this->checksum;
    }

}
