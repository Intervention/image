<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Gd\Decoders;

use Intervention\Image\Drivers\Gd\Decoders\ImageObjectDecoder;
use Intervention\Image\Image;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Drivers\Gd\Decoders\ImageObjectDecoder
 */
class ImageObjectDecoderTest extends TestCase
{
    use CanCreateGdTestImage;

    public function testDecode(): void
    {
        $decoder = new ImageObjectDecoder();
        $result = $decoder->decode($this->readTestImage('blue.gif'));
        $this->assertInstanceOf(Image::class, $result);
    }
}
