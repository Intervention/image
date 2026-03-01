<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit;

use Intervention\Image\AnimationFactory;
use Intervention\Image\Config;
use Intervention\Image\DataUri;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\AnimationFactoryInterface;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DataUriInterface;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ImageManagerInterface;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Providers\DriverProvider;
use Intervention\Image\Tests\Providers\ImageSourceProvider;
use Intervention\Image\Tests\Resource;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use SplFileInfo;

class ImageManagerTest extends BaseTestCase
{
    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    public function testConstructor(DriverInterface $driver): void
    {
        $manager = new ImageManager($driver);
        $this->assertInstanceOf(ImageManagerInterface::class, $manager);
        $this->assertInstanceOf(DriverInterface::class, $manager->driver);
    }

    #[DataProviderExternal(DriverProvider::class, 'driverClassnames')]
    public function testConstructorString(string $driver): void
    {
        $manager = new ImageManager($driver);
        $this->assertInstanceOf(ImageManagerInterface::class, $manager);
        $this->assertInstanceOf(DriverInterface::class, $manager->driver);
    }

    public function testConstructorUnkownClass(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new ImageManager('foobar');
    }

    public function testConstructorNonDriverClass(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new ImageManager(DataUri::class);
    }

    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    #[DataProviderExternal(DriverProvider::class, 'driverClassnames')]
    public function testConstructorWithOptions(string|DriverInterface $driver): void
    {
        $manager = new ImageManager($driver, backgroundColor: 'ff5500');
        $this->assertInstanceOf(ImageManagerInterface::class, $manager);
        $this->assertInstanceOf(DriverInterface::class, $manager->driver);
        $this->assertEquals('ff5500', $manager->driver->config()->backgroundColor);
    }

    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    #[DataProviderExternal(DriverProvider::class, 'driverClassnames')]
    public function testUsingDriver(string|DriverInterface $driver): void
    {
        $manager = ImageManager::usingDriver($driver);
        $this->assertInstanceOf(ImageManagerInterface::class, $manager);
        $this->assertInstanceOf(DriverInterface::class, $manager->driver);
    }

    #[DataProviderExternal(DriverProvider::class, 'driverClassnames')]
    public function testUsingDriverOptions(string $driverClassname): void
    {
        $manager = ImageManager::usingDriver(new $driverClassname(new Config(strip: true)), strip: false);
        $this->assertInstanceOf(ImageManagerInterface::class, $manager);
        $this->assertInstanceOf(DriverInterface::class, $manager->driver);
        $this->assertFalse($manager->driver->config()->strip);
    }

    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    #[DataProviderExternal(DriverProvider::class, 'driverClassnames')]
    public function testCreateImage(string|DriverInterface $driver): void
    {
        $manager = new ImageManager($driver);
        $image = $manager->createImage(3, 2);
        $this->assertEquals(3, $image->width());
        $this->assertEquals(2, $image->height());
        $this->assertColor(255, 255, 255, 0, $image->colorAt(0, 0));
    }

    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    #[DataProviderExternal(DriverProvider::class, 'driverClassnames')]
    public function testCreateImageAnimated(string|DriverInterface $driver): void
    {
        $manager = new ImageManager($driver);
        $image = $manager->createImage(3, 2, function (AnimationFactoryInterface $animation): void {
            $animation->add(Resource::create('red.gif')->path());
            $animation->add(Resource::create('green.gif')->path());
            $animation->add(Resource::create('blue.gif')->path());
        });
        $this->assertEquals(3, $image->width());
        $this->assertEquals(2, $image->height());
        $this->assertEquals(3, $image->count());
        $this->assertEquals(
            ['ff6464', '64ff64', '6464ff'],
            $image->colorsAt(1, 1)->map(fn(ColorInterface $color): string => $color->toHex())->toArray(),
        );
    }

    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    #[DataProviderExternal(DriverProvider::class, 'driverClassnames')]
    public function testCreateImageWithAnimationFactory(string|DriverInterface $driver): void
    {
        $manager = new ImageManager($driver);
        $factory = new AnimationFactory(3, 2, function (AnimationFactoryInterface $animation): void {
            $animation->add(Resource::create('red.gif')->path());
        });
        $image = $manager->createImage(3, 2, $factory);
        $this->assertInstanceOf(ImageInterface::class, $image);
        $this->assertEquals(3, $image->width());
        $this->assertEquals(2, $image->height());
    }

    #[DataProviderExternal(ImageSourceProvider::class, 'filePaths')]
    #[DataProviderExternal(ImageSourceProvider::class, 'binaryData')]
    #[DataProviderExternal(ImageSourceProvider::class, 'splFileInfoObjects')]
    #[DataProviderExternal(ImageSourceProvider::class, 'base64Data')]
    public function testDecode(mixed $source): void
    {
        $this->assertInstanceOf(
            ImageInterface::class,
            ImageManager::usingDriver(Driver::class)->decode($source),
        );
    }

    #[DataProviderExternal(ImageSourceProvider::class, 'filePaths')]
    public function testDecodePath(string $path): void
    {
        $this->assertInstanceOf(
            ImageInterface::class,
            ImageManager::usingDriver(Driver::class)->decodePath($path),
        );
    }

    #[DataProviderExternal(ImageSourceProvider::class, 'binaryData')]
    public function testDecodeBinary(string $binary): void
    {
        $this->assertInstanceOf(
            ImageInterface::class,
            ImageManager::usingDriver(Driver::class)->decodeBinary($binary),
        );
    }

    #[DataProviderExternal(ImageSourceProvider::class, 'splFileInfoObjects')]
    public function testDecodeSplFileInfo(SplFileInfo $splFileInfo): void
    {
        $this->assertInstanceOf(
            ImageInterface::class,
            ImageManager::usingDriver(Driver::class)->decodeSplFileInfo($splFileInfo),
        );
    }

    #[DataProviderExternal(ImageSourceProvider::class, 'base64Data')]
    public function testDecodeBase64(string $base64Data): void
    {
        $this->assertInstanceOf(
            ImageInterface::class,
            ImageManager::usingDriver(Driver::class)->decodeBase64($base64Data),
        );
    }

    #[DataProviderExternal(ImageSourceProvider::class, 'dataUriStrings')]
    #[DataProviderExternal(ImageSourceProvider::class, 'dataUriObjects')]
    public function testDecodeDataUri(string|DataUriInterface $datauri): void
    {
        $this->assertInstanceOf(
            ImageInterface::class,
            ImageManager::usingDriver(Driver::class)->decodeDataUri($datauri),
        );
    }

    #[DataProviderExternal(ImageSourceProvider::class, 'streams')]
    public function testDecodeStream(mixed $stream): void
    {
        $this->assertInstanceOf(
            ImageInterface::class,
            ImageManager::usingDriver(Driver::class)->decodeStream($stream),
        );
    }
}
