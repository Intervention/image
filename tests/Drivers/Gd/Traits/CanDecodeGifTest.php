<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Gd\Traits;

use Intervention\Image\Drivers\Gd\Decoders\Traits\CanDecodeGif;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Tests\TestCase;
use Mockery;

final class CanDecodeGifTest extends TestCase
{
    public function testDecodeGifFromBinaryAnimation(): void
    {
        $decoder = Mockery::mock(new class () {
            use CanDecodeGif;
        })->makePartial();

        $result = $decoder->decodeGif($this->getTestImageData('animation.gif'));
        $this->assertInstanceOf(ImageInterface::class, $result);
        $this->assertEquals('image/gif', $result->origin()->mediaType());
    }

    public function testDecodeGifFromBinaryStatic(): void
    {
        $decoder = Mockery::mock(new class () {
            use CanDecodeGif;
        })->makePartial();

        $result = $decoder->decodeGif($this->getTestImageData('red.gif'));
        $this->assertInstanceOf(ImageInterface::class, $result);
        $this->assertEquals('image/gif', $result->origin()->mediaType());
    }

    public function testDecodeGifFromPathAnimation(): void
    {
        $decoder = Mockery::mock(new class () {
            use CanDecodeGif;
        })->makePartial();

        $result = $decoder->decodeGif($this->getTestImagePath('animation.gif'));
        $this->assertInstanceOf(ImageInterface::class, $result);
        $this->assertEquals('image/gif', $result->origin()->mediaType());
    }

    public function testDecodeGifFromPathStatic(): void
    {
        $decoder = Mockery::mock(new class () {
            use CanDecodeGif;
        })->makePartial();

        $result = $decoder->decodeGif($this->getTestImagePath('red.gif'));
        $this->assertInstanceOf(ImageInterface::class, $result);
        $this->assertEquals('image/gif', $result->origin()->mediaType());
    }
}
