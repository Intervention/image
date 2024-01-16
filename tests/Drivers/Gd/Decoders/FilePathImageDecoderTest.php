<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Gd\Decoders;

use Intervention\Image\Drivers\Gd\Decoders\FilePathImageDecoder;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Image;
use Intervention\Image\Tests\TestCase;
use stdClass;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Drivers\Gd\Decoders\FilePathImageDecoder
 */
class FilePathImageDecoderTest extends TestCase
{
    protected FilePathImageDecoder $decoder;

    public function setUp(): void
    {
        $this->decoder = new FilePathImageDecoder();
    }

    public function testDecode(): void
    {
        $result = $this->decoder->decode(
            $this->getTestImagePath()
        );

        $this->assertInstanceOf(Image::class, $result);
    }

    public function testDecoderNonString(): void
    {
        $this->expectException(DecoderException::class);
        $this->decoder->decode(new stdClass());
    }

    public function testDecoderNoPath(): void
    {
        $this->expectException(DecoderException::class);
        $this->decoder->decode('no-path');
    }

    public function testDecoderTooLongPath(): void
    {
        $this->expectException(DecoderException::class);
        $this->decoder->decode(str_repeat('x', PHP_MAXPATHLEN + 1));
    }
}
