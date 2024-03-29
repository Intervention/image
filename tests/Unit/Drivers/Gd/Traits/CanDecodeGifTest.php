<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Traits;

use Intervention\Image\Drivers\Gd\Decoders\Traits\CanDecodeGif;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Tests\BaseTestCase;
use Mockery;

final class CanDecodeGifTest extends BaseTestCase
{
    public function testDecodeGifFromBinaryAnimation(): void
    {
        $decoder = Mockery::mock(new class () {
            use CanDecodeGif;
        })->makePartial();

        $result = $decoder->decodeGif($this->getTestResourceData('animation.gif'));
        $this->assertInstanceOf(ImageInterface::class, $result);
        $this->assertEquals('image/gif', $result->origin()->mediaType());
    }

    public function testDecodeGifFromBinaryStatic(): void
    {
        $decoder = Mockery::mock(new class () {
            use CanDecodeGif;
        })->makePartial();

        $result = $decoder->decodeGif($this->getTestResourceData('red.gif'));
        $this->assertInstanceOf(ImageInterface::class, $result);
        $this->assertEquals('image/gif', $result->origin()->mediaType());
    }

    public function testDecodeGifFromPathAnimation(): void
    {
        $decoder = Mockery::mock(new class () {
            use CanDecodeGif;
        })->makePartial();

        $result = $decoder->decodeGif($this->getTestResourcePath('animation.gif'));
        $this->assertInstanceOf(ImageInterface::class, $result);
        $this->assertEquals('image/gif', $result->origin()->mediaType());
    }

    public function testDecodeGifFromPathStatic(): void
    {
        $decoder = Mockery::mock(new class () {
            use CanDecodeGif;
        })->makePartial();

        $result = $decoder->decodeGif($this->getTestResourcePath('red.gif'));
        $this->assertInstanceOf(ImageInterface::class, $result);
        $this->assertEquals('image/gif', $result->origin()->mediaType());
    }
}
