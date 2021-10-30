<?php

namespace Intervention\Image\Tests\Drivers\Gd;

use Intervention\Image\Collection;
use Intervention\Image\Drivers\Gd\Frame;
use Intervention\Image\Drivers\Gd\Image;
use Intervention\Image\Geometry\Size;
use Intervention\Image\Tests\TestCase;

class ImageTest extends TestCase
{
    protected Image $image;

    protected function setUp(): void
    {
        $this->image = new Image(new Collection([new Frame(imagecreatetruecolor(3, 2))]));
    }

    public function testConstructor(): void
    {
        $this->assertInstanceOf(Image::class, $this->image);
    }

    public function testIterator(): void
    {
        foreach ($this->image as $frame) {
            $this->assertInstanceOf(Frame::class, $frame);
        }
    }

    public function testWidth(): void
    {
        $this->assertEquals(3, $this->image->width());
    }

    public function testHeight(): void
    {
        $this->assertEquals(2, $this->image->height());
    }

    public function testSize(): void
    {
        $this->assertInstanceOf(Size::class, $this->image->size());
    }

    public function testResize(): void
    {
        $this->assertInstanceOf(Image::class, $this->image->resize(function ($size) {
            $size->width(300);
        }));
    }
}
