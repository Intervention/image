<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Encoders;

use Generator;
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
use Intervention\Image\FileExtension;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

final class FileExtensionEncoderTest extends BaseTestCase
{
    private function testEncoder(string|FileExtension $extension, array $options = []): EncoderInterface
    {
        $encoder = new class ($extension, ...$options) extends FileExtensionEncoder
        {
            public function __construct($mediaType, ...$options)
            {
                parent::__construct($mediaType, ...$options);
            }

            public function test($extension)
            {
                return $this->encoderByFileExtension($extension);
            }
        };

        return $encoder->test($extension);
    }

    #[DataProvider('targetEncoderProvider')]
    public function testEncoderByFileExtensionString(
        string|FileExtension $fileExtension,
        string $targetEncoderClassname,
    ): void {
        $this->assertInstanceOf(
            $targetEncoderClassname,
            $this->testEncoder($fileExtension),
        );
    }

    public static function targetEncoderProvider(): Generator
    {
        yield ['webp', WebpEncoder::class];
        yield ['avif', AvifEncoder::class];
        yield ['jpeg', JpegEncoder::class];
        yield ['jpg', JpegEncoder::class];
        yield ['bmp', BmpEncoder::class];
        yield ['gif', GifEncoder::class];
        yield ['png', PngEncoder::class];
        yield ['tiff', TiffEncoder::class];
        yield ['tif', TiffEncoder::class];
        yield ['jp2', Jpeg2000Encoder::class];
        yield ['heic', HeicEncoder::class];
        yield [FileExtension::WEBP, WebpEncoder::class];
        yield [FileExtension::AVIF, AvifEncoder::class];
        yield [FileExtension::JPG, JpegEncoder::class];
        yield [FileExtension::BMP, BmpEncoder::class];
        yield [FileExtension::GIF, GifEncoder::class];
        yield [FileExtension::PNG, PngEncoder::class];
        yield [FileExtension::TIF, TiffEncoder::class];
        yield [FileExtension::TIFF, TiffEncoder::class];
        yield [FileExtension::JP2, Jpeg2000Encoder::class];
        yield [FileExtension::HEIC, HeicEncoder::class];
    }

    public function testArgumentsNotSupportedByTargetEncoder(): void
    {
        $encoder = $this->testEncoder(
            'png',
            [
                'interlaced' => true, // is not ignored
                'quality' => 10, // is ignored because png encoder has no quality argument
            ],
        );

        $this->assertInstanceOf(PngEncoder::class, $encoder);
        $this->assertTrue($encoder->interlaced);
    }

    public function testEncoderByFileExtensionUnknown(): void
    {
        $this->expectException(EncoderException::class);
        $this->testEncoder('test');
    }
}
