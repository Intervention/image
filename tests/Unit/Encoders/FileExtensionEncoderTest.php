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
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\FileExtension;
use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Origin;
use Intervention\Image\Tests\BaseTestCase;
use Mockery;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;

#[CoversClass(FileExtensionEncoder::class)]
final class FileExtensionEncoderTest extends BaseTestCase
{
    /**
     * @param $options array<string, int>
     */
    private function testEncoder(string|FileExtension $extension, array $options = []): EncoderInterface
    {
        $encoder = new class ($extension, ...$options) extends FileExtensionEncoder
        {
            public function __construct(string|FileExtension $extension, mixed ...$options)
            {
                parent::__construct($extension, ...$options);
            }

            public function test(string|FileExtension $extension): EncoderInterface
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
        yield ['WEBP', WebpEncoder::class];
        yield ['AVIF', AvifEncoder::class];
        yield ['JPEG', JpegEncoder::class];
        yield ['JPG', JpegEncoder::class];
        yield ['BMP', BmpEncoder::class];
        yield ['GIF', GifEncoder::class];
        yield ['PNG', PngEncoder::class];
        yield ['TIFF', TiffEncoder::class];
        yield ['TIF', TiffEncoder::class];
        yield ['JP2', Jpeg2000Encoder::class];
        yield ['HEIC', HeicEncoder::class];
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
        $this->expectException(NotSupportedException::class);
        $this->testEncoder('test');
    }

    public function testEncoderByFileExtensionEmpty(): void
    {
        $this->expectException(NotSupportedException::class);
        $this->testEncoder('');
    }

    public function testEncodeWithExplicitExtension(): void
    {
        $encoder = new FileExtensionEncoder('png');
        $encodedImage = Mockery::mock(EncodedImageInterface::class);

        $image = Mockery::mock(ImageInterface::class);
        $image->shouldReceive('encode')->once()->andReturn($encodedImage);

        $result = $encoder->encode($image);
        $this->assertSame($encodedImage, $result);
    }

    public function testEncodeWithNullExtensionFromOrigin(): void
    {
        $encoder = new FileExtensionEncoder();
        $encodedImage = Mockery::mock(EncodedImageInterface::class);

        $image = Mockery::mock(ImageInterface::class);
        $image->shouldReceive('origin')->andReturn(new Origin('image/png', '/path/to/image.png'));
        $image->shouldReceive('encode')->once()->andReturn($encodedImage);

        $result = $encoder->encode($image);
        $this->assertSame($encodedImage, $result);
    }

    public function testEncodeWithNullExtensionAndNoOriginExtension(): void
    {
        $encoder = new FileExtensionEncoder();

        $image = Mockery::mock(ImageInterface::class);
        $image->shouldReceive('origin')->andReturn(new Origin('application/octet-stream'));

        $this->expectException(NotSupportedException::class);
        $encoder->encode($image);
    }
}
