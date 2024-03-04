<?php

declare(strict_types=1);

namespace Intervention\Image;

use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\Interfaces\DecoderInterface;

final class ImageManager
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
     * Create image mangager with given driver
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
     * Create new image instance with given width & height
     *
     * @link https://image.intervention.io/v3/basics/instantiation#creating-new-images
     * @param int $width
     * @param int $height
     * @throws RuntimeException
     * @return ImageInterface
     */
    public function create(int $width, int $height): ImageInterface
    {
        return $this->driver->createImage($width, $height);
    }

    /**
     * Create new image instance from given input which can be one of the following
     *
     * - Path in filesystem
     * - File Pointer resource
     * - SplFileInfo object
     * - Raw binary image data
     * - Base64 encoded image data
     * - Data Uri
     * - Intervention\Image\Image Instance
     *
     * To decode the raw input data, you can optionally specify a decoding strategy
     * with the second parameter. This can be an array of class names or objects
     * of decoders to be processed in sequence. In this case, the input must be
     * decodedable with one of the decoders passed. It is also possible to pass
     * a single object or class name of a decoder.
     *
     * All decoders that implement the `DecoderInterface::class` can be passed. Usually
     * a selection of classes of the namespace `Intervention\Image\Decoders`
     *
     * If the second parameter is not set, an attempt to decode the input is made
     * with all available decoders of the driver.
     *
     * @link https://image.intervention.io/v3/basics/instantiation#reading-images
     * @param mixed $input
     * @param string|array|DecoderInterface $decoders
     * @throws RuntimeException
     * @return ImageInterface
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
     * Create new animated image by given callback
     *
     * @link https://image.intervention.io/v3/basics/instantiation#creating-animations
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
