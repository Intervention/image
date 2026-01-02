<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Decoders\BinaryImageDecoder;
use Intervention\Image\Decoders\FilePathImageDecoder;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Resource;

#[CoversClass(ImageManager::class)]
#[RequiresPhpExtension('imagick')]
final class ImageManagerTestImagick extends BaseTestCase
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
        $manager = ImageManager::imagick();
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

    public function testDecodeUsingDecoderClassname(): void
    {
        $manager = new ImageManager(Driver::class);
        $image = $manager->decode(Resource::create('red.gif')->path(), FilePathImageDecoder::class);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    public function testDecodeUsingDecoderInstance(): void
    {
        $manager = new ImageManager(Driver::class);
        $image = $manager->decode(Resource::create('red.gif')->path(), new FilePathImageDecoder());
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    public function testDecodeUsingDecoderClassnameArray(): void
    {
        $manager = new ImageManager(Driver::class);
        $image = $manager->decode(Resource::create('red.gif')->path(), [FilePathImageDecoder::class]);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    public function testDecodeUsingDecoderInstanceArray(): void
    {
        $manager = new ImageManager(Driver::class);
        $image = $manager->decode(Resource::create('red.gif')->path(), [new FilePathImageDecoder()]);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    public function testDecodeUsingDecoderInstanceArrayMultiple(): void
    {
        $manager = new ImageManager(Driver::class);
        $image = $manager->decode(Resource::create('red.gif')->path(), [
            new BinaryImageDecoder(),
            new FilePathImageDecoder(),
        ]);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    public function testDecodeRotationAdjustment(): void
    {
        $manager = new ImageManager(Driver::class);
        $image = $manager->decodeFrom(path: Resource::create('orientation.jpg')->path());
        $this->assertColor(1, 0, 254, 1, $image->colorAt(3, 3));
    }

    public function testDecodeWithoutRotationAdjustment(): void
    {
        $manager = new ImageManager(Driver::class, autoOrientation: false);
        $image = $manager->decodeFrom(path: Resource::create('orientation.jpg')->path());
        $this->assertColor(250, 2, 3, 1, $image->colorAt(3, 3));
    }

    public function testDecodeAnimation(): void
    {
        $manager = new ImageManager(Driver::class);
        $image = $manager->decodeFrom(path: Resource::create('animation.gif')->path());
        $this->assertTrue($image->isAnimated());
    }

    public function testDecodeAnimationDiscarded(): void
    {
        $manager = new ImageManager(Driver::class, decodeAnimation: false);
        $image = $manager->decodeFrom(path: Resource::create('animation.gif')->path());
        $this->assertFalse($image->isAnimated());
    }

    public function testApplyBackgroundColor(): void
    {
        $manager = new ImageManager(Driver::class);
        $image = $manager->decodeFrom(path: Resource::create('blocks.png')->path());
        $result = $image->background();
        $this->assertColor(255, 255, 255, 1, $image->colorAt(530, 0));
        $this->assertColor(255, 255, 255, 1, $result->colorAt(530, 0));
    }

    public function testApplyBackgroundColorConfigured(): void
    {
        $manager = new ImageManager(Driver::class, backgroundColor: 'ff5500');
        $image = $manager->decodeFrom(path: Resource::create('blocks.png')->path());
        $result = $image->background();
        $this->assertColor(255, 85, 0, 1, $image->colorAt(530, 0));
        $this->assertColor(255, 85, 0, 1, $result->colorAt(530, 0));
    }
}
