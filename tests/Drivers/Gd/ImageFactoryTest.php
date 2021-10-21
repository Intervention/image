<?php

namespace Intervention\Image\Tests\Drivers\Gd;

use Intervention\Image\Drivers\Gd\Image;
use Intervention\Image\Drivers\Gd\ImageFactory;
use Intervention\Image\Tests\TestCase;

class ImageFactoryTest extends TestCase
{
    public function testNewImage(): void
    {
        $factory = new ImageFactory();
        $image = $factory->newImage(3, 2);
        $this->assertInstanceOf(Image::class, $image);
    }
}
