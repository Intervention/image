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
use PHPUnit\Framework\Attributes\DataProvider;

final class MediaTypeEncoderTest extends BaseTestCase
{
    private function testEncoder(string|MediaType $mediaType, array $options = []): EncoderInterface
    {
        $encoder = new class ($mediaType, ...$options) extends MediaTypeEncoder
        {
            public function __construct($mediaType, ...$options)
            {
                parent::__construct($mediaType, ...$options);
            }

            public function test($mediaType)
            {
                return $this->encoderByMediaType($mediaType);
            }
        };

        return $encoder->test($mediaType);
    }

    #[DataProvider('targetEncoderProvider')]
    public function testEncoderByMediaType(
        string $mediaType,
        string $targetEncoderClassname,
    ): void {
        $this->assertInstanceOf(
            $targetEncoderClassname,
            $this->testEncoder($mediaType)
        );
    }

    public static function targetEncoderProvider(): array
    {
        return [
            ['image/webp', WebpEncoder::class],
            ['image/avif', AvifEncoder::class],
            ['image/jpeg', JpegEncoder::class],
            ['image/bmp', BmpEncoder::class],
            ['image/gif', GifEncoder::class],
            ['image/png', PngEncoder::class],
            ['image/png', PngEncoder::class],
            ['image/tiff', TiffEncoder::class],
            ['image/jp2', Jpeg2000Encoder::class],
            ['image/heic', HeicEncoder::class],
        ];
    }

    public function testArgumentsNotSupportedByTargetEncoder(): void
    {
        $encoder = $this->testEncoder(
            'image/png',
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
