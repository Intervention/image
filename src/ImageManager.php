<?php

namespace Intervention\Image;

use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;

final class ImageManager
{
    protected DriverInterface $driver;

    public function __construct(string|DriverInterface $driver)
    {
        $this->driver = $this->resolveDriver($driver);
    }

    public static function withDriver(string|DriverInterface $driver): self
    {
        return new self(self::resolveDriver($driver));
    }

    public static function gd(): self
    {
        return self::withDriver(GdDriver::class);
    }

    public static function imagick(): self
    {
        return self::withDriver(ImagickDriver::class);
    }

    public function create(int $width, int $height): ImageInterface
    {
        return $this->driver->createImage($width, $height);
    }

    public function read(mixed $input): ImageInterface
    {
        return $this->driver->handleInput($input);
    }

    public function animate(callable $init): ImageInterface
    {
        return $this->driver->createAnimation($init);
    }

    private static function resolveDriver(string|DriverInterface $driver): DriverInterface
    {
        if (is_object($driver)) {
            return $driver;
        }

        return new $driver();
    }
}
