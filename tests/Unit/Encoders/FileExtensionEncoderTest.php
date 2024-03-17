<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Encoders;

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
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Tests\BaseTestCase;

final class FileExtensionEncoderTest extends BaseTestCase
{
    private function testEncoder(string $extension): EncoderInterface
    {
        $encoder = new class () extends FileExtensionEncoder
        {
            public function test($extension)
            {
                return $this->encoderByFileExtension($extension);
            }
        };

        return $encoder->test($extension);
    }

    public function testEncoderByFileExtension(): void
    {

        $this->assertInstanceOf(
            WebpEncoder::class,
            $this->testEncoder('webp')
        );

        $this->assertInstanceOf(
            AvifEncoder::class,
            $this->testEncoder('avif')
        );

        $this->assertInstanceOf(
            JpegEncoder::class,
            $this->testEncoder('jpeg')
        );

        $this->assertInstanceOf(
            BmpEncoder::class,
            $this->testEncoder('bmp')
        );

        $this->assertInstanceOf(
            GifEncoder::class,
            $this->testEncoder('gif')
        );

        $this->assertInstanceOf(
            PngEncoder::class,
            $this->testEncoder('png')
        );

        $this->assertInstanceOf(
            TiffEncoder::class,
            $this->testEncoder('tiff')
        );

        $this->assertInstanceOf(
            Jpeg2000Encoder::class,
            $this->testEncoder('jp2')
        );

        $this->assertInstanceOf(
            HeicEncoder::class,
            $this->testEncoder('heic')
        );
    }

    public function testEncoderByFileExtensionUnknown(): void
    {
        $this->expectException(EncoderException::class);
        $this->testEncoder('test');
    }
}
