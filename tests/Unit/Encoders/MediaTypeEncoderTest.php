<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Encoders;

use Intervention\Image\Encoders\AvifEncoder;
use Intervention\Image\Encoders\BmpEncoder;
use Intervention\Image\Encoders\GifEncoder;
use Intervention\Image\Encoders\HeicEncoder;
use Intervention\Image\Encoders\Jpeg2000Encoder;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\MediaTypeEncoder;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Encoders\TiffEncoder;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\Exceptions\EncoderException;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\MediaType;
use Intervention\Image\Tests\BaseTestCase;

final class MediaTypeEncoderTest extends BaseTestCase
{
    private function testEncoder(string|MediaType $mediaType): EncoderInterface
    {
        $encoder = new class () extends MediaTypeEncoder
        {
            public function test($mediaType)
            {
                return $this->encoderByMediaType($mediaType);
            }
        };

        return $encoder->test($mediaType);
    }

    public function testEncoderByFileExtensionString(): void
    {
        $this->assertInstanceOf(
            WebpEncoder::class,
            $this->testEncoder('image/webp')
        );

        $this->assertInstanceOf(
            AvifEncoder::class,
            $this->testEncoder('image/avif')
        );

        $this->assertInstanceOf(
            JpegEncoder::class,
            $this->testEncoder('image/jpeg')
        );

        $this->assertInstanceOf(
            BmpEncoder::class,
            $this->testEncoder('image/bmp')
        );

        $this->assertInstanceOf(
            GifEncoder::class,
            $this->testEncoder('image/gif')
        );

        $this->assertInstanceOf(
            PngEncoder::class,
            $this->testEncoder('image/png')
        );

        $this->assertInstanceOf(
            TiffEncoder::class,
            $this->testEncoder('image/tiff')
        );

        $this->assertInstanceOf(
            Jpeg2000Encoder::class,
            $this->testEncoder('image/jp2')
        );

        $this->assertInstanceOf(
            HeicEncoder::class,
            $this->testEncoder('image/heic')
        );
    }

    public function testEncoderByFileExtensionEnumMember(): void
    {
        $this->assertInstanceOf(
            WebpEncoder::class,
            $this->testEncoder(MediaType::IMAGE_WEBP)
        );

        $this->assertInstanceOf(
            AvifEncoder::class,
            $this->testEncoder(MediaType::IMAGE_AVIF)
        );

        $this->assertInstanceOf(
            JpegEncoder::class,
            $this->testEncoder(MediaType::IMAGE_JPG)
        );

        $this->assertInstanceOf(
            BmpEncoder::class,
            $this->testEncoder(MediaType::IMAGE_BMP)
        );

        $this->assertInstanceOf(
            GifEncoder::class,
            $this->testEncoder(MediaType::IMAGE_GIF)
        );

        $this->assertInstanceOf(
            PngEncoder::class,
            $this->testEncoder(MediaType::IMAGE_PNG)
        );

        $this->assertInstanceOf(
            TiffEncoder::class,
            $this->testEncoder(MediaType::IMAGE_TIFF)
        );

        $this->assertInstanceOf(
            Jpeg2000Encoder::class,
            $this->testEncoder(MediaType::IMAGE_JP2)
        );

        $this->assertInstanceOf(
            HeicEncoder::class,
            $this->testEncoder(MediaType::IMAGE_HEIC)
        );
    }

    public function testEncoderByFileExtensionUnknown(): void
    {
        $this->expectException(EncoderException::class);
        $this->testEncoder('test');
    }
}
