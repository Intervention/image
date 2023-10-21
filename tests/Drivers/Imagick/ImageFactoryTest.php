<?php

namespace Intervention\Image\Tests\Drivers\Imagick;

use Imagick;
use Intervention\Image\Drivers\Imagick\Image;
use Intervention\Image\Drivers\Imagick\ImageFactory;
use Intervention\Image\Tests\TestCase;

/**
 * @requires extension imagick
 * @covers \Intervention\Image\Drivers\Imagick\ImageFactory
 */
class ImageFactoryTest extends TestCase
{
    public function testNewImage(): void
    {
        $factory = new ImageFactory();
        $image = $factory->newImage(3, 2);
        $this->assertInstanceOf(Image::class, $image);
    }

    public function testNewAnimation(): void
    {
        $factory = new ImageFactory();
        $image = $factory->newAnimation(function ($animation) {
            $animation->add($this->getTestImagePath('blue.gif'), 1.2);
            $animation->add($this->getTestImagePath('red.gif'), 1.2);
        });
        $this->assertInstanceOf(Image::class, $image);
        $this->assertEquals(2, $image->count());
    }

    protected function testNewCore(): void
    {
        $factory = new ImageFactory();
        $core = $factory->newCore(3, 2);
        $this->assertInstanceOf(Imagick::class, $core);
    }
}
