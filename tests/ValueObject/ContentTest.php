<?php

namespace FileDownloader\Tests\ValueObject;

use FileDownloader\ValueObject\Content;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FileDownloader\ValueObject\Content
 */
class ContentTest extends TestCase
{
    public function testShouldCreateContent(): void
    {
        // Given
        $contentPayload = 'some content';
        $expectedChecksum = '9893532233caff98cd083a116b013c0b';

        // When
        $content = new Content($contentPayload);

        // Then
        self::assertSame($contentPayload, $content->getValue());
        self::assertSame($expectedChecksum, $content->getChecksum());
    }

    public function testShouldNotCreateContentFromEmptyString(): void
    {
        // Given
        $contentPayload = '';

        // Expect
        $this->expectException(InvalidArgumentException::class);

        // When
        new Content($contentPayload);
    }

    public function testShouldCheckIfContentIsEqual(): void
    {
        // Given
        $contentPayload = 'some content';
        $expectedChecksum = '9893532233caff98cd083a116b013c0b';

        // When
        $content = new Content($contentPayload);

        // Then
        self::assertTrue($content->isEqual($expectedChecksum));
    }

    public function testShouldCheckIfContentIsNotEqualWhenChecksumIsNull(): void
    {
        // Given
        $contentPayload = 'some content';

        // When
        $content = new Content($contentPayload);

        // Then
        self::assertFalse($content->isEqual(null));
    }

    public function testShouldCheckIfContentIsNotEqualWhenChecksumIsDifferent(): void
    {
        // Given
        $contentPayload = 'some content';
        $checksum = '2345234523452';

        // When
        $content = new Content($contentPayload);

        // Then
        self::assertFalse($content->isEqual($checksum));
    }
}
