<?php

declare(strict_types=1);

namespace Intervention\Image;

use Intervention\Image\Decoders\Base64ImageDecoder;
use Intervention\Image\Decoders\BinaryImageDecoder;
use Intervention\Image\Decoders\DataUriImageDecoder;
use Intervention\Image\Decoders\EncodedImageObjectDecoder;
use Intervention\Image\Decoders\FilePathImageDecoder;
use Intervention\Image\Decoders\FilePointerImageDecoder;
use Intervention\Image\Decoders\NativeObjectDecoder;
use Intervention\Image\Decoders\SplFileInfoImageDecoder;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\MissingDependencyException;
use Intervention\Image\Interfaces\AnimationFactoryInterface;
use Intervention\Image\Interfaces\DataUriInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageManagerInterface;
use SplFileInfo;
use Stringable;

final class ImageManager implements ImageManagerInterface
{
    private readonly DriverInterface $driver;

    /**
     * @link https://image.intervention.io/v3/basics/configuration-drivers#create-a-new-image-manager-instance
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string|DriverInterface $driver, mixed ...$options)
    {
        $this->driver = $this->resolveDriver($driver, ...$options);
    }

    /**
     * Create image manager with GD driver.
     *
     * @link https://image.intervention.io/v3/basics/configuration-drivers#static-gd-driver-constructor
     *
     * @throws InvalidArgumentException
     * @throws MissingDependencyException
     */
    public static function gd(mixed ...$options): ImageManagerInterface
    {
        return self::withDriver(new GdDriver(), ...$options);
    }

    /**
     * Create image manager with Imagick driver.
     *
     * @link https://image.intervention.io/v3/basics/configuration-drivers#static-imagick-driver-constructor
     *
     * @throws InvalidArgumentException
     * @throws MissingDependencyException
     */
    public static function imagick(mixed ...$options): ImageManagerInterface
    {
        return self::withDriver(new ImagickDriver(), ...$options);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::withDriver()
     *
     * @throws InvalidArgumentException
     */
    public static function withDriver(string|DriverInterface $driver, mixed ...$options): ImageManagerInterface
    {
        return new self(self::resolveDriver($driver, ...$options));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::createImage()
     */
    public function createImage(
        int $width,
        int $height,
        null|callable|AnimationFactoryInterface $animation = null,
    ): ImageInterface {
        if (is_callable($animation)) {
            return AnimationFactory::build($this->driver, $width, $height, $animation);
        }

        if ($animation instanceof AnimationFactoryInterface) {
            return $animation->animation();
        }

        return $this->driver->createImage($width, $height);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::decode()
     */
    public function decode(mixed $source, null|string|array|DecoderInterface $decoders = null): ImageInterface
    {
        return $this->driver->handleImageInput($source, match (gettype($decoders)) {
            "NULL" => [
                NativeObjectDecoder::class,
                FilePointerImageDecoder::class,
                SplFileInfoImageDecoder::class,
                EncodedImageObjectDecoder::class,
                Base64ImageDecoder::class,
                DataUriImageDecoder::class,
                BinaryImageDecoder::class,
                FilePathImageDecoder::class,
            ],
            "string", "object" => [$decoders],
            default => $decoders,
        });
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::decodePath()
     */
    public function decodePath(string|Stringable $path): ImageInterface
    {
        return $this->driver->handleImageInput($path, [
            FilePathImageDecoder::class,
        ]);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::decodeSplFileInfo()
     */
    public function decodeSplFileInfo(SplFileInfo $splFileInfo): ImageInterface
    {
        return $this->driver->handleImageInput($splFileInfo, [
            SplFileInfoImageDecoder::class,
        ]);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::decodeBinary()
     */
    public function decodeBinary(string|Stringable $binary): ImageInterface
    {
        return $this->driver->handleImageInput($binary, [
            BinaryImageDecoder::class,
        ]);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::decodeBase64()
     */
    public function decodeBase64(string|Stringable $base64): ImageInterface
    {
        return $this->driver->handleImageInput($base64, [
            Base64ImageDecoder::class,
        ]);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::decodeDataUri()
     */
    public function decodeDataUri(string|Stringable|DataUriInterface $dataUri): ImageInterface
    {
        return $this->driver->handleImageInput($dataUri, [
            DataUriImageDecoder::class,
        ]);
    }


    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::decodeStream()
     */
    public function decodeStream(mixed $stream): ImageInterface
    {
        return $this->driver->handleImageInput($stream, [
            FilePointerImageDecoder::class,
        ]);
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
     * Return driver object from given input which might be driver classname or instance of DriverInterface.
     *
     * @throws InvalidArgumentException
     */
    private static function resolveDriver(string|DriverInterface $driver, mixed ...$options): DriverInterface
    {
        $driver = match (true) {
            $driver instanceof DriverInterface => $driver,
            class_exists($driver) => new $driver(),
            default => throw new InvalidArgumentException(
                'Unable to resolve driver. Argment must be either an instance of ' .
                    DriverInterface::class . '::class or a qualified namespaced name of the driver class',
            ),
        };

        if (!$driver instanceof DriverInterface) {
            throw new InvalidArgumentException(
                'Unable to resolve driver. Driver object must implement ' . DriverInterface::class
            );
        }

        $driver->config()->setOptions(...$options);

        return $driver;
    }
}
