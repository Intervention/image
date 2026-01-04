<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use SplFileInfo;
use Stringable;

interface ImageManagerInterface
{
    /**
     * Create image manager with given driver.
     *
     * @link https://image.intervention.io/v3/basics/configuration-drivers#static-constructor
     */
    public static function withDriver(string|DriverInterface $driver, mixed ...$options): self;

    /**
     * Create new image instance with given width & height.
     *
     * @link https://image.intervention.io/v3/basics/instantiation#create-new-images
     *
     * @param int<1, max> $width
     * @param int<1, max> $height
     */
    public function createImage(
        int $width,
        int $height,
        null|callable|AnimationFactoryInterface $animation = null,
    ): ImageInterface;

    /**
     * Create new image instance from given image source which can be one of the following:
     *
     * - Path in filesystem
     * - File Pointer resource
     * - SplFileInfo object
     * - Raw binary image data
     * - Base64 encoded image data
     * - Data Uri string or instance of DataUriInterface
     * - Instance of EncodedImageInterface
     * - Instance of ImageInterface
     *
     * To decode the image source, you can optionally specify a decoding strategy
     * with the second parameter. This can be an array of class names or objects
     * of decoders to be processed in sequence. In this case, the source must be
     * decodedable with one of the decoders passed. It is also possible to pass
     * a single object or class name of a decoder.
     *
     * All decoders that implement the `DecoderInterface::class` can be passed. Usually
     * a selection of classes of the namespace `Intervention\Image\Decoders`.
     *
     * If the second parameter is not set, an attempt to decode the source is made
     * with all available decoders of the driver.
     *
     * @link https://image.intervention.io/v3/basics/instantiation#read-image-sources
     *
     * @param string|array<string|DecoderInterface>|DecoderInterface $decoders
     */
    public function decode(mixed $source, null|string|array|DecoderInterface $decoders = null): ImageInterface;

    /**
     * Create a new image instance by passing one of the following image sources:
     *
     * - Path in filesystem
     * - File Pointer resource
     * - SplFileInfo object
     * - Raw binary image data
     * - Base64 encoded image data
     * - Data Uri
     *
     * @link https://image.intervention.io/v3/basics/instantiation#read-image-sources
     */
    public function decodeFrom(
        null|string|Stringable $path = null,
        null|string|Stringable $binary = null,
        null|string|Stringable $base64 = null,
        null|string|Stringable|DataUriInterface $dataUri = null,
        null|SplFileInfo $splFileInfo = null,
        mixed $stream = null,
    ): ImageInterface;

    /**
     * Return currently used driver.
     */
    public function driver(): DriverInterface;
}
