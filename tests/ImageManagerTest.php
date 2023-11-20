<?php

namespace Intervention\Image\Tests;

use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;

/**
 * @covers \Intervention\Image\ImageManager
 */
class ImageManagerTest extends TestCase
{
    public function testConstructor()
    {
        $manager = new ImageManager(new GdDriver());
        $this->assertInstanceOf(ImageManager::class, $manager);

        $manager = new ImageManager(GdDriver::class);
        $this->assertInstanceOf(ImageManager::class, $manager);
    }

    public function testWithDriver(): void
    {
        $manager = ImageManager::withDriver(new GdDriver());
        $this->assertInstanceOf(ImageManager::class, $manager);

        $manager = ImageManager::withDriver(GdDriver::class);
        $this->assertInstanceOf(ImageManager::class, $manager);
    }

    public function testDriverStatics(): void
    {
        $manager = ImageManager::gd();
        $this->assertInstanceOf(ImageManager::class, $manager);

        $manager = ImageManager::imagick();
        $this->assertInstanceOf(ImageManager::class, $manager);
    }

    /** @requires extension gd */
    public function testCreateGd()
    {
        $manager = new ImageManager(GdDriver::class);
        $image = $manager->create(5, 4);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    /** @requires extension gd */
    public function testReadGd()
    {
        $manager = new ImageManager(GdDriver::class);
        $image = $manager->read(__DIR__ . '/images/red.gif');
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    /** @requires extension imagick */
    public function testCreateImagick()
    {
        $manager = new ImageManager(ImagickDriver::class);
        $image = $manager->create(5, 4);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    /** @requires extension imagick */
    public function testReadImagick()
    {
        $manager = new ImageManager(ImagickDriver::class);
        $image = $manager->read(__DIR__ . '/images/red.gif');
        $this->assertInstanceOf(ImageInterface::class, $image);
    }
}
