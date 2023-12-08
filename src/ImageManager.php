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

    /**
     * Create image mangager with given driver
     *
     * @param string|DriverInterface $driver
     * @return ImageManager
     */
    public static function withDriver(string|DriverInterface $driver): self
    {
        return new self(self::resolveDriver($driver));
    }

    /**
     * Create image manager with GD driver
     *
     * @return ImageManager
     */
    public static function gd(): self
    {
        return self::withDriver(GdDriver::class);
    }

    /**
     * Create image manager with Imagick driver
     *
     * @return ImageManager
     */
    public static function imagick(): self
    {
        return self::withDriver(ImagickDriver::class);
    }

    /**
     * Create new image instance with given width & height
     *
     * @param int $width
     * @param int $height
     * @return ImageInterface
     */
    public function create(int $width, int $height): ImageInterface
    {
        return $this->driver->createImage($width, $height);
    }

    /**
     * Create new image instance from given source which can be one of the following
     *
     * - Path in filesystem
     * - File Pointer resource
     * - SplFileInfo object
     * - Raw binary image data
     * - Base64 encoded image data
     * - Data Uri
     * - Intervention\Image\Image Instance
     *
     * @param mixed $input
     * @return ImageInterface
     */
    public function read(mixed $input): ImageInterface
    {
        return $this->driver->handleInput($input);
    }

    /**
     * Create new animated image by given callback
     *
     * @param callable $init
     * @return ImageInterface
     */
    public function animate(callable $init): ImageInterface
    {
        return $this->driver->createAnimation($init);
    }

    /**
     * Return driver object
     *
     * @param string|DriverInterface $driver
     * @return DriverInterface
     */
    private static function resolveDriver(string|DriverInterface $driver): DriverInterface
    {
        if (is_object($driver)) {
            return $driver;
        }

        return new $driver();
    }
}
