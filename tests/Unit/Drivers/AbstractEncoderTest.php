<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers;

use Intervention\Image\Drivers\AbstractEncoder;
use Intervention\Image\EncodedImage;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Tests\BaseTestCase;
use Mockery;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(AbstractEncoder::class)]
final class AbstractEncoderTest extends BaseTestCase
{
    public function testEncode(): void
    {
        $encoder = Mockery::mock(AbstractEncoder::class)->makePartial();
        $image = Mockery::mock(ImageInterface::class);
        $encoded = Mockery::mock(EncodedImage::class);
        $image->shouldReceive('encode')->andReturn($encoded);
        $result = $encoder->encode($image);
        $this->assertInstanceOf(EncodedImage::class, $result);
    }

    public function testSetOptions(): void
    {
        $encoder = new class () extends AbstractEncoder {
            public int $quality = 75;
            public bool $interlaced = false;

            public function encode(ImageInterface $image): EncodedImageInterface
            {
                return parent::encode($image);
            }
        };

        $result = $encoder->setOptions(quality: 90, interlaced: true);
        $this->assertSame($encoder, $result);
        $this->assertEquals(90, $encoder->quality);
        $this->assertTrue($encoder->interlaced);
    }

    public function testSetOptionsInvalidProperty(): void
    {
        $encoder = new class () extends AbstractEncoder {
            public int $quality = 75;

            public function encode(ImageInterface $image): EncodedImageInterface
            {
                return parent::encode($image);
            }
        };

        $this->expectException(InvalidArgumentException::class);
        $encoder->setOptions(nonExistentProperty: 42);
    }

    public function testCreateEncodedImage(): void
    {
        $encoder = new class () extends AbstractEncoder {
            public function encode(ImageInterface $image): EncodedImageInterface
            {
                return parent::encode($image);
            }

            public function testCreateEncodedImage(): EncodedImage
            {
                return $this->createEncodedImage(function ($pointer): void {
                    fwrite($pointer, 'test data');
                });
            }
        };

        $result = $encoder->testCreateEncodedImage();
        $this->assertInstanceOf(EncodedImage::class, $result);
        $this->assertEquals('test data', $result->toString());
    }

    public function testCreateEncodedImageWithMediaType(): void
    {
        $encoder = new class () extends AbstractEncoder {
            public function encode(ImageInterface $image): EncodedImageInterface
            {
                return parent::encode($image);
            }

            public function testCreateEncodedImage(): EncodedImage
            {
                return $this->createEncodedImage(function ($pointer): void {
                    fwrite($pointer, 'png data');
                }, 'image/png');
            }
        };

        $result = $encoder->testCreateEncodedImage();
        $this->assertInstanceOf(EncodedImage::class, $result);
        $this->assertEquals('png data', $result->toString());
        $this->assertEquals('image/png', $result->mediaType());
    }
}
