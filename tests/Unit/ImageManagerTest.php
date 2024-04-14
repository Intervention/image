<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Decoders\BinaryImageDecoder;
use Intervention\Image\Decoders\FilePathImageDecoder;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Tests\BaseTestCase;

#[CoversClass(\Intervention\Image\ImageManager::class)]
final class ImageManagerTest extends BaseTestCase
{
    public function testConstructor(): void
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

    public function testDriver(): void
    {
        $driver = new GdDriver();
        $manager = ImageManager::withDriver($driver);
        $this->assertEquals($driver, $manager->driver());
    }

    public function testDriverStatics(): void
    {
        $manager = ImageManager::gd();
        $this->assertInstanceOf(ImageManager::class, $manager);

        $manager = ImageManager::imagick();
        $this->assertInstanceOf(ImageManager::class, $manager);
    }

    #[RequiresPhpExtension('gd')]
    public function testCreateGd(): void
    {
        $manager = new ImageManager(GdDriver::class);
        $image = $manager->create(5, 4);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    #[RequiresPhpExtension('gd')]
    public function testAnimateGd(): void
    {
        $manager = new ImageManager(GdDriver::class);
        $image = $manager->animate(function ($animation) {
            $animation->add($this->getTestResourcePath('red.gif'), .25);
        });
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    #[RequiresPhpExtension('gd')]
    public function testReadGd(): void
    {
        $manager = new ImageManager(GdDriver::class);
        $image = $manager->read($this->getTestResourcePath('red.gif'));
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    #[RequiresPhpExtension('gd')]
    public function testReadGdWithDecoderClassname(): void
    {
        $manager = new ImageManager(GdDriver::class);
        $image = $manager->read($this->getTestResourcePath('red.gif'), FilePathImageDecoder::class);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    #[RequiresPhpExtension('gd')]
    public function testReadGdWithDecoderInstance(): void
    {
        $manager = new ImageManager(GdDriver::class);
        $image = $manager->read($this->getTestResourcePath('red.gif'), new FilePathImageDecoder());
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    #[RequiresPhpExtension('gd')]
    public function testReadGdWithDecoderClassnameArray(): void
    {
        $manager = new ImageManager(GdDriver::class);
        $image = $manager->read($this->getTestResourcePath('red.gif'), [FilePathImageDecoder::class]);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    #[RequiresPhpExtension('gd')]
    public function testReadGdWithDecoderInstanceArray(): void
    {
        $manager = new ImageManager(GdDriver::class);
        $image = $manager->read($this->getTestResourcePath('red.gif'), [new FilePathImageDecoder()]);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    #[RequiresPhpExtension('gd')]
    public function testReadGdWithDecoderInstanceArrayMultiple(): void
    {
        $manager = new ImageManager(GdDriver::class);
        $image = $manager->read($this->getTestResourcePath('red.gif'), [
            new BinaryImageDecoder(),
            new FilePathImageDecoder(),
        ]);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    #[RequiresPhpExtension('gd')]
    public function testReadGdWithRotationAdjustment(): void
    {
        $manager = new ImageManager(GdDriver::class);
        $image = $manager->read($this->getTestResourcePath('orientation.jpg'));
        $this->assertColor(255, 255, 255, 255, $image->pickColor(0, 24));
    }

    #[RequiresPhpExtension('imagick')]
    public function testCreateImagick(): void
    {
        $manager = new ImageManager(ImagickDriver::class);
        $image = $manager->create(5, 4);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    #[RequiresPhpExtension('imagick')]
    public function testAnimateImagick(): void
    {
        $manager = new ImageManager(ImagickDriver::class);
        $image = $manager->animate(function ($animation) {
            $animation->add($this->getTestResourcePath('red.gif'), .25);
        });
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    #[RequiresPhpExtension('imagick')]
    public function testReadImagick(): void
    {
        $manager = new ImageManager(ImagickDriver::class);
        $image = $manager->read($this->getTestResourcePath('red.gif'));
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    #[RequiresPhpExtension('imagick')]
    public function testReadImagickWithDecoderClassname(): void
    {
        $manager = new ImageManager(ImagickDriver::class);
        $image = $manager->read($this->getTestResourcePath('red.gif'), FilePathImageDecoder::class);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    #[RequiresPhpExtension('imagick')]
    public function testReadImagickWithDecoderInstance(): void
    {
        $manager = new ImageManager(ImagickDriver::class);
        $image = $manager->read($this->getTestResourcePath('red.gif'), new FilePathImageDecoder());
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    #[RequiresPhpExtension('imagick')]
    public function testReadImagickWithDecoderClassnameArray(): void
    {
        $manager = new ImageManager(ImagickDriver::class);
        $image = $manager->read($this->getTestResourcePath('red.gif'), [FilePathImageDecoder::class]);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    #[RequiresPhpExtension('imagick')]
    public function testReadImagickWithDecoderInstanceArray(): void
    {
        $manager = new ImageManager(ImagickDriver::class);
        $image = $manager->read($this->getTestResourcePath('red.gif'), [new FilePathImageDecoder()]);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    #[RequiresPhpExtension('imagick')]
    public function testReadImagickWithDecoderInstanceArrayMultiple(): void
    {
        $manager = new ImageManager(ImagickDriver::class);
        $image = $manager->read($this->getTestResourcePath('red.gif'), [
            new BinaryImageDecoder(),
            new FilePathImageDecoder(),
        ]);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    #[RequiresPhpExtension('imagick')]
    public function testReadImagickWithRotationAdjustment(): void
    {
        $manager = new ImageManager(ImagickDriver::class);
        $image = $manager->read($this->getTestResourcePath('orientation.jpg'));
        $this->assertColor(255, 255, 255, 255, $image->pickColor(0, 24));
    }
}
