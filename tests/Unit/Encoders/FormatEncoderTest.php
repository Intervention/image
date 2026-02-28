<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Encoders;

use Intervention\Image\Encoders\FormatEncoder;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Format;
use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Origin;
use Intervention\Image\Tests\BaseTestCase;
use Mockery;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(FormatEncoder::class)]
final class FormatEncoderTest extends BaseTestCase
{
    public function testConstructorWithFormat(): void
    {
        $encoder = new FormatEncoder(Format::JPEG, quality: 90);
        $this->assertInstanceOf(FormatEncoder::class, $encoder);
    }

    public function testConstructorWithNull(): void
    {
        $encoder = new FormatEncoder();
        $this->assertInstanceOf(FormatEncoder::class, $encoder);
    }

    public function testEncodeWithFormat(): void
    {
        $encoder = new FormatEncoder(Format::JPEG, quality: 50);
        $encodedImage = Mockery::mock(EncodedImageInterface::class);

        $image = Mockery::mock(ImageInterface::class);
        $image->shouldReceive('encode')->once()->andReturn($encodedImage);

        $result = $encoder->encode($image);
        $this->assertSame($encodedImage, $result);
    }

    public function testEncodeWithNullFormatFromOrigin(): void
    {
        $encoder = new FormatEncoder();
        $encodedImage = Mockery::mock(EncodedImageInterface::class);

        $image = Mockery::mock(ImageInterface::class);
        $image->shouldReceive('origin')->andReturn(new Origin('image/png'));
        $image->shouldReceive('encode')->once()->andReturn($encodedImage);

        $result = $encoder->encode($image);
        $this->assertSame($encodedImage, $result);
    }

    public function testEncodeWithNullFormatAndUnsupportedOrigin(): void
    {
        $encoder = new FormatEncoder();

        $image = Mockery::mock(ImageInterface::class);
        $image->shouldReceive('origin')->andReturn(new Origin('application/octet-stream'));

        $this->expectException(NotSupportedException::class);
        $encoder->encode($image);
    }
}
