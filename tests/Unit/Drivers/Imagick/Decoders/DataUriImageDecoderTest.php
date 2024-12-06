<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Decoders;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Drivers\Imagick\Decoders\DataUriImageDecoder;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Image;
use Intervention\Image\Tests\BaseTestCase;
use stdClass;

#[RequiresPhpExtension('imagick')]
#[CoversClass(DataUriImageDecoder::class)]
final class DataUriImageDecoderTest extends BaseTestCase
{
    protected DataUriImageDecoder $decoder;

    protected function setUp(): void
    {
        $this->decoder = new DataUriImageDecoder();
        $this->decoder->setDriver(new Driver());
    }

    public function testDecode(): void
    {
        $result = $this->decoder->decode(
            sprintf('data:image/jpeg;base64,%s', base64_encode($this->getTestResourceData('blue.gif')))
        );

        $this->assertInstanceOf(Image::class, $result);
    }

    public function testDecoderNonString(): void
    {
        $this->expectException(DecoderException::class);
        $this->decoder->decode(new stdClass());
    }

    public function testDecoderInvalid(): void
    {
        $this->expectException(DecoderException::class);
        $this->decoder->decode('invalid');
    }

    public function testDecoderNonImage(): void
    {
        $this->expectException(DecoderException::class);
        $this->decoder->decode('data:text/plain;charset=utf-8,test');
    }
}
