<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers;

use Intervention\Image\Drivers\Gd\Driver as DefaultDriver;
use Intervention\Image\Image;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Providers\DriverProvider;
use Intervention\Image\Tests\Providers\Gd\ImageSourceProvider as ImageSourceProviderGd;
use Intervention\Image\Tests\Providers\Imagick\ImageSourceProvider as ImageSourceProviderImagick;
use PHPUnit\Framework\Attributes\DataProviderExternal;

class ImageTest extends BaseTestCase
{
    public function testDriverDefault(): void
    {
        $this->assertInstanceOf(DefaultDriver::class, Image::create(3, 2)->driver());
    }

    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    #[DataProviderExternal(DriverProvider::class, 'driverClassnames')]
    public function testDriver(string|DriverInterface $driver): void
    {
        $this->assertInstanceOf(
            is_string($driver) ? $driver : $driver::class,
            Image::usingDriver($driver)->driver(),
        );
    }

    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    #[DataProviderExternal(DriverProvider::class, 'driverClassnames')]
    public function testUsingDriver(string|DriverInterface $driver): void
    {
        $this->assertInstanceOf(ImageInterface::class, Image::usingDriver($driver));
    }

    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    #[DataProviderExternal(DriverProvider::class, 'driverClassnames')]
    public function testCreate(string|DriverInterface $driver): void
    {
        $image = Image::usingDriver($driver)->create(3, 2);
        $this->assertInstanceOf(ImageInterface::class, $image);
        $this->assertEquals(3, $image->width());
        $this->assertEquals(2, $image->height());
    }

    #[DataProviderExternal(ImageSourceProviderGd::class, 'filePaths')]
    #[DataProviderExternal(ImageSourceProviderImagick::class, 'filePaths')]
    public function testFromPath(DriverInterface $driver, string $path): void
    {
        $this->assertInstanceOf(ImageInterface::class, Image::usingDriver($driver)->fromPath($path));
    }
}
