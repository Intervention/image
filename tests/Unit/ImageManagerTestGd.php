<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Decoders\BinaryImageDecoder;
use Intervention\Image\Decoders\FilePathImageDecoder;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Resource;

#[CoversClass(ImageManager::class)]
#[RequiresPhpExtension('gd')]
final class ImageManagerTestGd extends BaseTestCase
{
    public function testConstructor(): void
    {
        $manager = new ImageManager(new Driver());
        $this->assertInstanceOf(ImageManager::class, $manager);

        $manager = new ImageManager(Driver::class);
        $this->assertInstanceOf(ImageManager::class, $manager);
    }

    public function testWithDriver(): void
    {
        $manager = ImageManager::withDriver(new Driver());
        $this->assertInstanceOf(ImageManager::class, $manager);

        $manager = ImageManager::withDriver(Driver::class);
        $this->assertInstanceOf(ImageManager::class, $manager);
    }

    public function testDriver(): void
    {
        $driver = new Driver();
        $manager = ImageManager::withDriver($driver);
        $this->assertEquals($driver, $manager->driver());
    }

    public function testDriverStatic(): void
    {
        $manager = ImageManager::gd();
        $this->assertInstanceOf(ImageManager::class, $manager);
    }

    public function testCreate(): void
    {
        $manager = new ImageManager(Driver::class);
        $image = $manager->create(5, 4);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    public function testAnimate(): void
    {
        $manager = new ImageManager(Driver::class);
        $image = $manager->animate(function ($animation): void {
            $animation->add(Resource::create('red.gif')->path(), .25);
        });
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    public function testDecode(): void
    {
        $manager = new ImageManager(Driver::class);
        $image = $manager->decode(Resource::create('red.gif')->path());
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    public function testDecodeWithDecoderClassname(): void
    {
        $manager = new ImageManager(Driver::class);
        $image = $manager->decode(Resource::create('red.gif')->path(), FilePathImageDecoder::class);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    public function testDecodeWithDecoderInstance(): void
    {
        $manager = new ImageManager(Driver::class);
        $image = $manager->decode(Resource::create('red.gif')->path(), new FilePathImageDecoder());
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    public function testDecodeWithDecoderClassnameArray(): void
    {
        $manager = new ImageManager(Driver::class);
        $image = $manager->decode(Resource::create('red.gif')->path(), [FilePathImageDecoder::class]);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    public function testDecodeWithDecoderInstanceArray(): void
    {
        $manager = new ImageManager(Driver::class);
        $image = $manager->decode(Resource::create('red.gif')->path(), [new FilePathImageDecoder()]);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    public function testDecodeWithDecoderInstanceArrayMultiple(): void
    {
        $manager = new ImageManager(Driver::class);
        $image = $manager->decode(Resource::create('red.gif')->path(), [
            new BinaryImageDecoder(),
            new FilePathImageDecoder(),
        ]);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    public function testDecodeWithRotationAdjustment(): void
    {
        $manager = new ImageManager(Driver::class);
        $image = $manager->decode(Resource::create('orientation.jpg')->path());
        $this->assertColor(1, 0, 254, 255, $image->pickColor(3, 3));
    }

    public function testDecodeWithoutRotationAdjustment(): void
    {
        $manager = new ImageManager(Driver::class, autoOrientation: false);
        $image = $manager->decode(Resource::create('orientation.jpg')->path());
        $this->assertColor(250, 2, 3, 255, $image->pickColor(3, 3));
    }

    public function testDecodeAnimation(): void
    {
        $manager = new ImageManager(Driver::class);
        $image = $manager->decode(Resource::create('animation.gif')->path());
        $this->assertTrue($image->isAnimated());
    }

    public function testDecodeAnimationDiscarded(): void
    {
        $manager = new ImageManager(Driver::class, decodeAnimation: false);
        $image = $manager->decode(Resource::create('animation.gif')->path());
        $this->assertFalse($image->isAnimated());
    }

    public function testApplyBackgroundColorDefault(): void
    {
        $manager = new ImageManager(Driver::class);
        $image = $manager->decode(Resource::create('blocks.png')->path());
        $result = $image->background();
        $this->assertColor(255, 255, 255, 255, $image->pickColor(530, 0));
        $this->assertColor(255, 255, 255, 255, $result->pickColor(530, 0));
    }

    public function testApplyBackgroundColorConfigured(): void
    {
        $manager = new ImageManager(Driver::class, backgroundColor: 'ff5500');
        $image = $manager->decode(Resource::create('blocks.png')->path());
        $result = $image->background();
        $this->assertColor(255, 85, 0, 255, $image->pickColor(530, 0));
        $this->assertColor(255, 85, 0, 255, $result->pickColor(530, 0));
    }
}
