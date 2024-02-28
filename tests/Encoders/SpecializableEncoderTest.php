<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Encoders;

use Intervention\Image\EncodedImage;
use Intervention\Image\Encoders\SpecializableEncoder;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Tests\BaseTestCase;
use Mockery;

final class SpecializableEncoderTest extends BaseTestCase
{
    public function testConstructorDefault(): void
    {
        $encoder = new class () extends SpecializableEncoder
        {
        };

        $this->assertEquals(75, $encoder->quality);
    }

    public function testConstructorList(): void
    {
        $encoder = new class (1) extends SpecializableEncoder
        {
        };

        $this->assertEquals(1, $encoder->quality);
    }

    public function testConstructorNamed(): void
    {
        $encoder = new class (quality: 1) extends SpecializableEncoder
        {
        };

        $this->assertEquals(1, $encoder->quality);
    }

    public function testEncode(): void
    {
        $encoder = Mockery::mock(SpecializableEncoder::class)->makePartial();
        $image = Mockery::mock(ImageInterface::class);
        $encoded = Mockery::mock(EncodedImage::class);
        $image->shouldReceive('encode')->andReturn($encoded);

        $result = $encoder->encode($image);
        $this->assertInstanceOf(EncodedImage::class, $result);
    }
}
