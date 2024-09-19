<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers;

use Intervention\Image\EncodedImage;
use Intervention\Image\Drivers\AbstractEncoder;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Tests\BaseTestCase;
use Mockery;
use PHPUnit\Metadata\CoversClass;

#[CoversClass(\Intervention\Image\Drivers\AbstractEncoder::class)]
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
}
