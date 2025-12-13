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
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Exceptions\InputException;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageManagerInterface;
use SplFileInfo;
use Stringable;

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
     * @see ImageManagerInterface::decodeFrom()
     *
     * @throws RuntimeException
     */
    public function decodeFrom(
        null|string|Stringable $path = null,
        null|string|Stringable $binary = null,
        null|string|Stringable $base64 = null,
        null|string|Stringable $dataUri = null,
        ?SplFileInfo $splFileInfo = null,
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

        if (count($param) !== 1) {
            throw new InputException(
                'Method ImageManagerInterface::readFrom() expects either ' .
                    '$path, $binary, $base64, $dataUri, $splFileInfo or $stream as an argument',
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
     * @see ImageManagerInterface::createFromPath()
     */
    public function readFromPath(string|Stringable $path): ImageInterface
    {
        return $this->driver->handleImageInput($path, [FilePathImageDecoder::class]);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::createFromBinary()
     */
    public function readFromBinary(string|Stringable $data): ImageInterface
    {
        return $this->driver->handleImageInput($data, [BinaryImageDecoder::class]);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::createFromBase64()
     */
    public function readFromBase64(string|Stringable $data): ImageInterface
    {
        return $this->driver->handleImageInput($data, [Base64ImageDecoder::class]);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::createFromDataUri()
     */
    public function readFromDataUri(string|Stringable $uri): ImageInterface
    {
        return $this->driver->handleImageInput($uri, [DataUriImageDecoder::class]);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::createFromStream()
     */
    public function readFromStream(mixed $stream): ImageInterface
    {
        return $this->driver->handleImageInput($stream, [FilePointerImageDecoder::class]);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::createFromSplFileInfo()
     */
    public function readFromSplFileInfo(SplFileInfo $file): ImageInterface
    {
        return $this->driver->handleImageInput($file, [SplFileInfoImageDecoder::class]);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::decode()
     */
    public function decode(mixed $input, null|string|array|DecoderInterface $decoders = null): ImageInterface
    {
        return $this->driver->handleImageInput($input, match (gettype($decoders)) {
            'NULL', 'array' => $decoders,
            'string', 'object' => [$decoders],
        });
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
                    DriverInterface::class . '::class or a qualified namespaced name of the driver class',
            ),
        };

        if (!$driver instanceof DriverInterface) {
            throw new DriverException(
                'Unable to resolve driver. Driver object must implement ' . DriverInterface::class
            );
        }

        $driver->config()->setOptions(...$options);

        return $driver;
    }
}
