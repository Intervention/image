<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Encoders;

use Intervention\Image\Encoders\AvifEncoder;
use Intervention\Image\Encoders\BmpEncoder;
use Intervention\Image\Encoders\FileExtensionEncoder;
use Intervention\Image\Encoders\GifEncoder;
use Intervention\Image\Encoders\HeicEncoder;
use Intervention\Image\Encoders\Jpeg2000Encoder;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Encoders\TiffEncoder;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\Exceptions\EncoderException;
use Intervention\Image\Tests\BaseTestCase;
use Mockery;

final class FileExtensionEncoderTest extends BaseTestCase
{
    public function testEncoderByFileExtension(): void
    {
        $encoder = Mockery::mock(FileExtensionEncoder::class);

        $this->assertInstanceOf(
            WebpEncoder::class,
            $encoder->encoderByFileExtension('webp')
        );

        $this->assertInstanceOf(
            AvifEncoder::class,
            $encoder->encoderByFileExtension('avif')
        );

        $this->assertInstanceOf(
            JpegEncoder::class,
            $encoder->encoderByFileExtension('jpeg')
        );

        $this->assertInstanceOf(
            BmpEncoder::class,
            $encoder->encoderByFileExtension('bmp')
        );

        $this->assertInstanceOf(
            GifEncoder::class,
            $encoder->encoderByFileExtension('gif')
        );

        $this->assertInstanceOf(
            PngEncoder::class,
            $encoder->encoderByFileExtension('png')
        );

        $this->assertInstanceOf(
            TiffEncoder::class,
            $encoder->encoderByFileExtension('tiff')
        );

        $this->assertInstanceOf(
            Jpeg2000Encoder::class,
            $encoder->encoderByFileExtension('jp2')
        );

        $this->assertInstanceOf(
            HeicEncoder::class,
            $encoder->encoderByFileExtension('heic')
        );
    }

    public function testEncoderByFileExtensionUnknown(): void
    {
        $encoder = Mockery::mock(FileExtensionEncoder::class);
        $this->expectException(EncoderException::class);
        $encoder->encoderByFileExtension('test');
    }
}
