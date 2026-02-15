<?php

declare(strict_types=1);

namespace Intervention\Image;

use Intervention\Image\Decoders\Base64ImageDecoder;
use Intervention\Image\Decoders\BinaryImageDecoder;
use Intervention\Image\Decoders\DataUriImageDecoder;
use Intervention\Image\Decoders\FilePathImageDecoder;
use Intervention\Image\Decoders\FilePointerImageDecoder;
use Intervention\Image\Decoders\SplFileInfoImageDecoder;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\AnimationFactoryInterface;
use Intervention\Image\Interfaces\DataUriInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ImageManagerInterface;
use SplFileInfo;
use Stringable;

class ImageManager implements ImageManagerInterface
{
    public DriverInterface $driver;

    /**
     * Create new image manager instance.
     *
     * @link https://image.intervention.io/v3/basics/configuration-drivers#create-a-new-image-manager-instance
     */
    public function __construct(string|DriverInterface $driver, mixed ...$options)
    {
        $this->driver = $this->resolveDriver($driver, ...$options);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::usingDriver()
     */
    public static function usingDriver(string|DriverInterface $driver, mixed ...$options): ImageManagerInterface
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
        if ($animation instanceof AnimationFactoryInterface) {
            return $animation->image($this->driver);
        }

        if (is_callable($animation)) {
            return AnimationFactory::build($width, $height, $animation, $this->driver);
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
        return $this->driver->handleImageInput(
            $source,
            in_array(gettype($decoders), ['string', 'object']) ? [$decoders] : $decoders,
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::decodePath()
     */
    public function decodePath(string|Stringable $path): ImageInterface
    {
        return $this->decode($path, FilePathImageDecoder::class);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::decodeBinary()
     */
    public function decodeBinary(string|Stringable $binary): ImageInterface
    {
        return $this->decode($binary, BinaryImageDecoder::class);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::decodeSplFileInfo()
     */
    public function decodeSplFileInfo(SplFileInfo $splFileInfo): ImageInterface
    {
        return $this->decode($splFileInfo, SplFileInfoImageDecoder::class);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::decodeBase64()
     */
    public function decodeBase64(string|Stringable $base64): ImageInterface
    {
        return $this->decode($base64, Base64ImageDecoder::class);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::decodeDataUri()
     */
    public function decodeDataUri(string|Stringable|DataUriInterface $dataUri): ImageInterface
    {
        return $this->decode($dataUri, DataUriImageDecoder::class);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::decodeStream()
     */
    public function decodeStream(mixed $stream): ImageInterface
    {
        return $this->decode($stream, FilePointerImageDecoder::class);
    }

    /**
     * Resolve given string or driver to a driver instance with given options.
     */
    private static function resolveDriver(string|DriverInterface $driver, mixed ...$options): DriverInterface
    {
        if (is_string($driver) && !class_exists($driver)) {
            throw new InvalidArgumentException(
                'Argument $driver must be existing class name or instance of ' . DriverInterface::class,
            );
        }

        if (is_string($driver) && !is_subclass_of($driver, DriverInterface::class)) {
            throw new InvalidArgumentException(
                'Argument $driver must be existing class name or instance of ' . DriverInterface::class,
            );
        }

        $driver = is_string($driver) ? new $driver() : $driver;
        $driver->config()->setOptions(...$options);

        return $driver;
    }
}
