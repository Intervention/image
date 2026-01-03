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
     * @see ImageManagerInterface::create()
     */
    public function create(int $width, int $height): ImageInterface
    {
        return $this->driver->createImage($width, $height);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::decode()
     */
    public function decode(mixed $input, null|string|array|DecoderInterface $decoders = null): ImageInterface
    {
        return $this->driver->handleImageInput($input, match (gettype($decoders)) {
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
     * @see ImageManagerInterface::decodeUsing()
     *
     * @throws InvalidArgumentException
     */
    public function decodeUsing(
        null|string|Stringable $path = null,
        null|string|Stringable $binary = null,
        null|string|Stringable $base64 = null,
        null|string|Stringable|DataUriInterface $dataUri = null,
        null|SplFileInfo $splFileInfo = null,
        mixed $stream = null,
    ): ImageInterface {
        $param = array_filter([
            'path' => $path,
            'binary' => $binary,
            'base64' => $base64,
            'dataUri' => $dataUri,
            'splFileInfo' => $splFileInfo,
            'stream' => $stream,
        ], fn(mixed $value): bool => $value !== null);

        if (count($param) === 0) {
            throw new InvalidArgumentException(
                'Method ImageManagerInterface::decodeFrom() expects at least 1 argument, 0 given'
            );
        }

        if (count($param) !== 1) {
            throw new InvalidArgumentException(
                'Method ImageManagerInterface::decodeFrom() expects either ' .
                    '$path, $binary, $base64, $dataUri, $splFileInfo or $stream as an argument'
            );
        }

        $decoderKey = array_key_first($param);
        $using = $param[$decoderKey];

        return match ($decoderKey) {
            'path' => $this->driver->handleImageInput($using, [FilePathImageDecoder::class]),
            'binary' => $this->driver->handleImageInput($using, [BinaryImageDecoder::class]),
            'base64' => $this->driver->handleImageInput($using, [Base64ImageDecoder::class]),
            'dataUri' => $this->driver->handleImageInput($using, [DataUriImageDecoder::class]),
            'splFileInfo' => $this->driver->handleImageInput($using, [SplFileInfoImageDecoder::class]),
            'stream' => $this->driver->handleImageInput($using, [FilePointerImageDecoder::class]),
        };
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::animate()
     */
    public function animate(callable $animation): ImageInterface
    {
        return $this->driver->createAnimation($animation);
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
