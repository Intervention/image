<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit;

use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Decoders\BinaryImageDecoder;
use Intervention\Image\Decoders\FilePathImageDecoder;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Tests\Providers\DriverProvider;
use Intervention\Image\Tests\Resource;
use PHPUnit\Framework\Attributes\DataProviderExternal;

#[CoversClass(ImageManager::class)]
#[RequiresPhpExtension('gd')]
#[RequiresPhpExtension('imagick')]
class ImageManagerTest extends BaseTestCase
{
    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    public function testConstructor(DriverInterface $driver): void
    {
        $manager = new ImageManager($driver);
        $this->assertInstanceOf(ImageManager::class, $manager);

        $manager = new ImageManager($driver::class);
        $this->assertInstanceOf(ImageManager::class, $manager);
    }

    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    public function testWithDriver(DriverInterface $driver): void
    {
        $manager = ImageManager::withDriver($driver);
        $this->assertInstanceOf(ImageManager::class, $manager);

        $manager = ImageManager::withDriver($driver::class);
        $this->assertInstanceOf(ImageManager::class, $manager);
    }

    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    public function testDriver(DriverInterface $driver): void
    {
        $manager = ImageManager::withDriver($driver);
        $this->assertEquals($driver, $manager->driver());
    }

    public function testDriverStatic(): void
    {
        $manager = ImageManager::gd();
        $this->assertInstanceOf(ImageManager::class, $manager);
        $this->assertInstanceOf(GdDriver::class, $manager->driver());

        $manager = ImageManager::imagick();
        $this->assertInstanceOf(ImageManager::class, $manager);
        $this->assertInstanceOf(ImagickDriver::class, $manager->driver());
    }

    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    public function testCreate(DriverInterface $driver): void
    {
        $manager = new ImageManager($driver);
        $image = $manager->createImage(5, 4);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    public function testCreateAnimated(DriverInterface $driver): void
    {
        $manager = new ImageManager($driver);
        $image = $manager->createImage(12, 8, function ($animation): void {
            $animation->add(Resource::create('red.gif')->path(), .25);
            $animation->add(Resource::create('green.gif')->path(), .25);
            $animation->add(Resource::create('blue.gif')->path(), .25);
        })->setLoops(1);

        $this->assertInstanceOf(ImageInterface::class, $image);
        $this->assertEquals(12, $image->width());
        $this->assertEquals(8, $image->height());
        $this->assertEquals(3, $image->count());
        $this->assertEquals(1, $image->loops());
        $this->assertEquals([.25, .25, .25], array_map(
            fn(FrameInterface $frame): float => $frame->delay(),
            $image->core()->toArray(),
        ));
    }

    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    public function testDecodeUsingDecoderClassname(DriverInterface $driver): void
    {
        $manager = new ImageManager($driver);
        $image = $manager->decode(Resource::create('red.gif')->path(), FilePathImageDecoder::class);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    public function testDecodeUsingDecoderInstance(DriverInterface $driver): void
    {
        $manager = new ImageManager($driver);
        $image = $manager->decode(Resource::create('red.gif')->path(), new FilePathImageDecoder());
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    public function testDecodeUsingDecoderClassnameArray(DriverInterface $driver): void
    {
        $manager = new ImageManager($driver);
        $image = $manager->decode(Resource::create('red.gif')->path(), [FilePathImageDecoder::class]);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    public function testDecodeUsingDecoderInstanceArray(DriverInterface $driver): void
    {
        $manager = new ImageManager($driver);
        $image = $manager->decode(Resource::create('red.gif')->path(), [new FilePathImageDecoder()]);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    public function testDecodeUsingDecoderInstanceArrayMultiple(DriverInterface $driver): void
    {
        $manager = new ImageManager($driver);
        $image = $manager->decode(Resource::create('red.gif')->path(), [
            new BinaryImageDecoder(),
            new FilePathImageDecoder(),
        ]);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    public function testDecodeRotationAdjustment(DriverInterface $driver): void
    {
        $manager = new ImageManager($driver);
        $image = $manager->decodeFrom(path: Resource::create('orientation.jpg')->path());
        $this->assertColor(1, 0, 254, 255, $image->colorAt(3, 3));
    }

    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    public function testDecodeWithoutRotationAdjustment(DriverInterface $driver): void
    {
        $manager = new ImageManager($driver, autoOrientation: false);
        $image = $manager->decodeFrom(path: Resource::create('orientation.jpg')->path());
        $this->assertColor(250, 2, 3, 255, $image->colorAt(3, 3));
    }

    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    public function testDecodeAnimation(DriverInterface $driver): void
    {
        $manager = new ImageManager($driver);
        $image = $manager->decodeFrom(path: Resource::create('animation.gif')->path());
        $this->assertTrue($image->isAnimated());
    }

    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    public function testDecodeAnimationDiscarded(DriverInterface $driver): void
    {
        $manager = new ImageManager($driver, decodeAnimation: false);
        $image = $manager->decodeFrom(path: Resource::create('animation.gif')->path());
        $this->assertFalse($image->isAnimated());
    }

    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    public function testApplyBackgroundColorDefault(DriverInterface $driver): void
    {
        $manager = new ImageManager($driver);
        $image = $manager->decodeFrom(path: Resource::create('blocks.png')->path());
        $result = $image->background();
        $this->assertColor(255, 255, 255, 255, $image->colorAt(530, 0));
        $this->assertColor(255, 255, 255, 255, $result->colorAt(530, 0));
    }

    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    public function testApplyBackgroundColorConfigured(DriverInterface $driver): void
    {
        $manager = new ImageManager($driver, backgroundColor: 'ff5500');
        $image = $manager->decodeFrom(path: Resource::create('blocks.png')->path());
        $result = $image->background();
        $this->assertColor(255, 85, 0, 255, $image->colorAt(530, 0));
        $this->assertColor(255, 85, 0, 255, $result->colorAt(530, 0));
    }
}
