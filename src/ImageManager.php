<?php

declare(strict_types=1);

namespace Intervention\Image;

use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageManagerInterface;

final class ImageManager implements ImageManagerInterface
{
    protected DriverInterface $driver;

    /**
     * @link https://image.intervention.io/v3/basics/image-manager#create-a-new-image-manager-instance
     * @param string|DriverInterface $driver
     */
    public function __construct(string|DriverInterface $driver)
    {
        $this->driver = $this->resolveDriver($driver);
    }

    /**
     * Create image manager with given driver
     *
     * @link https://image.intervention.io/v3/basics/image-manager
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
     * @link https://image.intervention.io/v3/basics/image-manager#static-gd-driver-constructor
     * @return ImageManager
     */
    public static function gd(): self
    {
        return self::withDriver(GdDriver::class);
    }

    /**
     * Create image manager with Imagick driver
     *
     * @link https://image.intervention.io/v3/basics/image-manager#static-imagick-driver-constructor
     * @return ImageManager
     */
    public static function imagick(): self
    {
        return self::withDriver(ImagickDriver::class);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::create()
     */
    public function create(int $width, int $height): ImageInterface
    {
        return $this->driver->createImage($width, $height);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::read()
     */
    public function read(mixed $input, string|array|DecoderInterface $decoders = []): ImageInterface
    {
        return $this->driver->handleInput(
            $input,
            match (true) {
                is_string($decoders), is_a($decoders, DecoderInterface::class) => [$decoders],
                default => $decoders,
            }
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::animate()
     */
    public function animate(callable $init): ImageInterface
    {
        return $this->driver->createAnimation($init);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::driver()
     */
    public function driver(): DriverInterface
    {
        return $this->driver;
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
