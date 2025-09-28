<?php

declare(strict_types=1);

namespace Intervention\Image;

use Intervention\Image\Decoders\Base64ImageDecoder;
use Intervention\Image\Decoders\BinaryImageDecoder;
use Intervention\Image\Decoders\DataUriImageDecoder;
use Intervention\Image\Decoders\FilePathImageDecoder;
use Intervention\Image\Decoders\FilePointerImageDecoder;
use Intervention\Image\Decoders\SplFileInfoImageDecoder;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\InputException;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageManagerInterface;
use SplFileInfo;

final class ImageManager implements ImageManagerInterface
{
    private DriverInterface $driver;

    /**
     * @link https://image.intervention.io/v3/basics/configuration-drivers#create-a-new-image-manager-instance
     *
     * @throws DriverException
     * @throws InputException
     */
    public function __construct(string|DriverInterface $driver, mixed ...$options)
    {
        $this->driver = $this->resolveDriver($driver, ...$options);
    }

    /**
     * Create image manager with given driver
     *
     * @link https://image.intervention.io/v3/basics/configuration-drivers#static-constructor
     *
     * @throws DriverException
     * @throws InputException
     */
    public static function withDriver(string|DriverInterface $driver, mixed ...$options): self
    {
        return new self(self::resolveDriver($driver, ...$options));
    }

    /**
     * Create image manager with GD driver
     *
     * @link https://image.intervention.io/v3/basics/configuration-drivers#static-gd-driver-constructor
     *
     * @throws DriverException
     * @throws InputException
     */
    public static function gd(mixed ...$options): self
    {
        return self::withDriver(new GdDriver(), ...$options);
    }

    /**
     * Create image manager with Imagick driver
     *
     * @link https://image.intervention.io/v3/basics/configuration-drivers#static-imagick-driver-constructor
     *
     * @throws DriverException
     * @throws InputException
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
     * @see ImageManagerInterface::createFromPath()
     */
    public function createFromPath(string $path): ImageInterface
    {
        return $this->driver->handleImageInput($path, [FilePathImageDecoder::class]);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::createFromBinary()
     */
    public function createFromBinary(string $data): ImageInterface
    {
        return $this->driver->handleImageInput($data, [BinaryImageDecoder::class]);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::createFromBase64()
     */
    public function createFromBase64(string $data): ImageInterface
    {
        return $this->driver->handleImageInput($data, [Base64ImageDecoder::class]);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::createFromDataUri()
     */
    public function createFromDataUri(string $uri): ImageInterface
    {
        return $this->driver->handleImageInput($uri, [DataUriImageDecoder::class]);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::createFromStream()
     */
    public function createFromStream(mixed $stream): ImageInterface
    {
        return $this->driver->handleImageInput($stream, [FilePointerImageDecoder::class]);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::createFromSplFileInfo()
     */
    public function createFromSplFileInfo(SplFileInfo $file): ImageInterface
    {
        return $this->driver->handleImageInput($file, [SplFileInfoImageDecoder::class]);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::createFrom()
     */
    public function createFrom(mixed $input, string|array|DecoderInterface $decoders = []): ImageInterface
    {
        return $this->driver->handleImageInput($input, is_array($decoders) ? $decoders : [$decoders]);
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
     * Return driver object from given input which might be driver classname or instance of DriverInterface
     *
     * @throws DriverException
     * @throws InputException
     */
    private static function resolveDriver(string|DriverInterface $driver, mixed ...$options): DriverInterface
    {
        $driver = match (true) {
            $driver instanceof DriverInterface => $driver,
            class_exists($driver) => new $driver(),
            default => throw new DriverException(
                'Unable to resolve driver. Argment must be either an instance of ' .
                    DriverInterface::class . '::class or a qualified namespaced name of the driver class.',
            ),
        };

        if (!$driver instanceof DriverInterface) {
            throw new DriverException(
                'Unable to resolve driver. Driver object must implement ' . DriverInterface::class . '.',
            );
        }

        $driver->config()->setOptions(...$options);

        return $driver;
    }
}
