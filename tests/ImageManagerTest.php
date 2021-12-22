<?php

namespace Intervention\Image\Tests;

use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;

class ImageManagerTest extends TestCase
{
    public function testConstructor()
    {
        $manager = new ImageManager('foo');
        $this->assertInstanceOf(ImageManager::class, $manager);
    }

    /** @requires extension gd */
    public function testCreateGd()
    {
        $manager = new ImageManager('gd');
        $image = $manager->create(5, 4);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    /** @requires extension gd */
    public function testMakeGd()
    {
        $manager = new ImageManager('gd');
        $image = $manager->make(__DIR__ . '/images/red.gif');
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    /** @requires extension imagick */
    public function testCreateImagick()
    {
        $manager = new ImageManager('imagick');
        $image = $manager->create(5, 4);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    /** @requires extension imagick */
    public function testMakeImagick()
    {
        $manager = new ImageManager('imagick');
        $image = $manager->make(__DIR__ . '/images/red.gif');
        $this->assertInstanceOf(ImageInterface::class, $image);
    }
}
