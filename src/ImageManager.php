<?php

declare(strict_types=1);

namespace Intervention\Image;

use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageManagerInterface;

final class ImageManager implements ImageManagerInterface
{
    protected DriverInterface $driver;

    /**
     * @link https://image.intervention.io/v3/basics/image-manager#create-a-new-image-manager-instance
     * @param string|DriverInterface $driver
     * @param mixed $options
     */
    public function __construct(string|DriverInterface $driver, mixed ...$options)
    {
        $this->driver = $this->resolveDriver($driver, ...$options);
    }

    /**
     * Create image manager with given driver
     *
     * @link https://image.intervention.io/v3/basics/image-manager
     * @param string|DriverInterface $driver
     * @param mixed $options
     * @return ImageManager
     */
    public static function withDriver(string|DriverInterface $driver, mixed ...$options): self
    {
        return new self(self::resolveDriver($driver, ...$options));
    }

    /**
     * Create image manager with GD driver
     *
     * @link https://image.intervention.io/v3/basics/image-manager#static-gd-driver-constructor
     * @param mixed $options
     * @throws DriverException
     * @return ImageManager
     */
    public static function gd(mixed ...$options): self
    {
        return self::withDriver(new GdDriver(), ...$options);
    }

    /**
     * Create image manager with Imagick driver
     *
     * @link https://image.intervention.io/v3/basics/image-manager#static-imagick-driver-constructor
     * @param mixed $options
     * @throws DriverException
     * @return ImageManager
     */
    public static function imagick(mixed ...$options): self
    {
        return self::withDriver(new ImagickDriver(), ...$options);
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
     * @param mixed $options
     * @return DriverInterface
     */
    private static function resolveDriver(string|DriverInterface $driver, mixed ...$options): DriverInterface
    {
        $driver = is_string($driver) ? new $driver() : $driver;
        $driver->config()->setOptions(...$options);

        return $driver;
    }
}
