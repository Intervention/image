<?php

declare(strict_types=1);

namespace Intervention\Image;

use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\ImageDecoderException;
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
    use Traits\CanResolveDriver;

    public DriverInterface $driver;

    /**
     * Create new image manager instance.
     *
     * @link https://image.intervention.io/v4/basics/configuration-drivers#create-a-new-image-manager-instance
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string|DriverInterface $driver, mixed ...$options)
    {
        $this->driver = $this->resolveDriver($driver, ...$options);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::usingDriver()
     *
     * @throws InvalidArgumentException
     */
    public static function usingDriver(string|DriverInterface $driver, mixed ...$options): ImageManagerInterface
    {
        return new self(self::resolveDriver($driver, ...$options));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::createImage()
     *
     * @throws InvalidArgumentException
     * @throws DriverException
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
     *
     * @throws InvalidArgumentException
     * @throws ImageDecoderException
     * @throws DriverException
     */
    public function decode(mixed $source, null|string|array|DecoderInterface $decoders = null): ImageInterface
    {
        return $this->driver->decodeImage(
            $source,
            in_array(gettype($decoders), ['string', 'object']) ? [$decoders] : $decoders,
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::decodePath()
     *
     * @throws InvalidArgumentException
     * @throws ImageDecoderException
     * @throws DriverException
     */
    public function decodePath(string|Stringable $path): ImageInterface
    {
        return $this->decode($path, Decoders\FilePathImageDecoder::class);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::decodeBinary()
     *
     * @throws InvalidArgumentException
     * @throws ImageDecoderException
     * @throws DriverException
     */
    public function decodeBinary(string|Stringable $binary): ImageInterface
    {
        return $this->decode($binary, Decoders\BinaryImageDecoder::class);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::decodeSplFileInfo()
     *
     * @throws InvalidArgumentException
     * @throws ImageDecoderException
     * @throws DriverException
     */
    public function decodeSplFileInfo(SplFileInfo $splFileInfo): ImageInterface
    {
        return $this->decode($splFileInfo, Decoders\SplFileInfoImageDecoder::class);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::decodeBase64()
     *
     * @throws InvalidArgumentException
     * @throws ImageDecoderException
     * @throws DriverException
     */
    public function decodeBase64(string|Stringable $base64): ImageInterface
    {
        return $this->decode($base64, Decoders\Base64ImageDecoder::class);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::decodeDataUri()
     *
     * @throws InvalidArgumentException
     * @throws ImageDecoderException
     * @throws DriverException
     */
    public function decodeDataUri(string|Stringable|DataUriInterface $dataUri): ImageInterface
    {
        return $this->decode($dataUri, Decoders\DataUriImageDecoder::class);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::decodeStream()
     *
     * @throws InvalidArgumentException
     * @throws ImageDecoderException
     * @throws DriverException
     */
    public function decodeStream(mixed $stream): ImageInterface
    {
        return $this->decode($stream, [Decoders\StreamImageDecoder::class, Decoders\Psr7StreamImageDecoder::class]);
    }

    /**
     * Decode an image from the contents of the HTTP response.
     *
     * @throws InvalidArgumentException
     * @throws ImageDecoderException
     * @throws DriverException
     */
    public function decodeHttpResponse(mixed $response): ImageInterface
    {
        return $this->decode($response, Decoders\HttpResponseImageDecoder::class);
    }
}
