<?php

namespace Intervention\Image\Tests;

use Imagick;
use ImagickPixel;
use Intervention\Image\Drivers\Gd\Core as GdCore;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Gd\Frame as GdFrame;
use Intervention\Image\Drivers\Imagick\Core as ImagickCore;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\Image;

class ImageTest extends TestCase
{
    public function testCloneImageGd(): void
    {
        $image = new Image(
            new GdDriver(),
            new GdCore([
                new GdFrame(imagecreatetruecolor(3, 3))
            ])
        );

        $clone = clone $image;

        $this->assertEquals(3, $image->width());
        $this->assertEquals(3, $clone->width());
        $result = $clone->resize(1);
        $this->assertEquals(3, $image->width());
        $this->assertEquals(1, $clone->width());
        $this->assertEquals(1, $result->width());
    }

    public function testCloneImageImagick(): void
    {
        $imagick = new Imagick();
        $imagick->newImage(3, 2, new ImagickPixel('red'), 'png');
        $image = new Image(
            new ImagickDriver(),
            new ImagickCore($imagick)
        );

        $clone = clone $image;

        $this->assertEquals(3, $image->width());
        $this->assertEquals(3, $clone->width());
        $result = $clone->resize(1);
        $this->assertEquals(3, $image->width());
        $this->assertEquals(1, $clone->width());
        $this->assertEquals(1, $result->width());
    }
}
