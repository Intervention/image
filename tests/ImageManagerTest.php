<?php

declare(strict_types=1);

namespace Intervention\Image\Tests;

use Intervention\Image\Decoders\BinaryImageDecoder;
use Intervention\Image\Decoders\FilePathImageDecoder;
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
    public function testAnimateGd(): void
    {
        $manager = new ImageManager(GdDriver::class);
        $image = $manager->animate(function ($animation) {
            $animation->add($this->getTestImagePath('red.gif'), .25);
        });
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    /** @requires extension gd */
    public function testReadGd()
    {
        $manager = new ImageManager(GdDriver::class);
        $image = $manager->read(__DIR__ . '/images/red.gif');
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    /** @requires extension gd */
    public function testReadGdWithDecoderClassname(): void
    {
        $manager = new ImageManager(GdDriver::class);
        $image = $manager->read(__DIR__ . '/images/red.gif', FilePathImageDecoder::class);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    /** @requires extension gd */
    public function testReadGdWithDecoderInstance(): void
    {
        $manager = new ImageManager(GdDriver::class);
        $image = $manager->read(__DIR__ . '/images/red.gif', new FilePathImageDecoder());
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    /** @requires extension gd */
    public function testReadGdWithDecoderClassnameArray(): void
    {
        $manager = new ImageManager(GdDriver::class);
        $image = $manager->read(__DIR__ . '/images/red.gif', [FilePathImageDecoder::class]);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    /** @requires extension gd */
    public function testReadGdWithDecoderInstanceArray(): void
    {
        $manager = new ImageManager(GdDriver::class);
        $image = $manager->read(__DIR__ . '/images/red.gif', [new FilePathImageDecoder()]);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    /** @requires extension gd */
    public function testReadGdWithDecoderInstanceArrayMultiple(): void
    {
        $manager = new ImageManager(GdDriver::class);
        $image = $manager->read(__DIR__ . '/images/red.gif', [new BinaryImageDecoder(), new FilePathImageDecoder()]);
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
    public function testAnimateImagick(): void
    {
        $manager = new ImageManager(ImagickDriver::class);
        $image = $manager->animate(function ($animation) {
            $animation->add($this->getTestImagePath('red.gif'), .25);
        });
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    /** @requires extension imagick */
    public function testReadImagick()
    {
        $manager = new ImageManager(ImagickDriver::class);
        $image = $manager->read(__DIR__ . '/images/red.gif');
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    /** @requires extension imagick */
    public function testReadImagickWithDecoderClassname(): void
    {
        $manager = new ImageManager(ImagickDriver::class);
        $image = $manager->read(__DIR__ . '/images/red.gif', FilePathImageDecoder::class);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    /** @requires extension imagick */
    public function testReadImagickWithDecoderInstance(): void
    {
        $manager = new ImageManager(ImagickDriver::class);
        $image = $manager->read(__DIR__ . '/images/red.gif', new FilePathImageDecoder());
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    /** @requires extension imagick */
    public function testReadImagickWithDecoderClassnameArray(): void
    {
        $manager = new ImageManager(ImagickDriver::class);
        $image = $manager->read(__DIR__ . '/images/red.gif', [FilePathImageDecoder::class]);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    /** @requires extension imagick */
    public function testReadImagickWithDecoderInstanceArray(): void
    {
        $manager = new ImageManager(ImagickDriver::class);
        $image = $manager->read(__DIR__ . '/images/red.gif', [new FilePathImageDecoder()]);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    /** @requires extension imagick */
    public function testReadImagickWithDecoderInstanceArrayMultiple(): void
    {
        $manager = new ImageManager(ImagickDriver::class);
        $image = $manager->read(__DIR__ . '/images/red.gif', [new BinaryImageDecoder(), new FilePathImageDecoder()]);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }
}
