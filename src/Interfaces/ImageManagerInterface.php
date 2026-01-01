<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Intervention\Image\DataUri;
use SplFileInfo;
use Stringable;

interface ImageManagerInterface
{
    /**
     * Create new image instance with given width & height.
     *
     * @link https://image.intervention.io/v3/basics/instantiation#create-new-images
     *
     * @param int<1, max> $width
     * @param int<1, max> $height
     */
    public function create(int $width, int $height): ImageInterface;

    /**
     * Create new image instance from given input which can be one of the following.
     *
     * - Path in filesystem
     * - File Pointer resource
     * - SplFileInfo object
     * - Raw binary image data
     * - Base64 encoded image data
     * - Data Uri
     *
     * To decode the raw input data, you can optionally specify a decoding strategy
     * with the second parameter. This can be an array of class names or objects
     * of decoders to be processed in sequence. In this case, the input must be
     * decodedable with one of the decoders passed. It is also possible to pass
     * a single object or class name of a decoder.
     *
     * All decoders that implement the `DecoderInterface::class` can be passed. Usually
     * a selection of classes of the namespace `Intervention\Image\Decoders`.
     *
     * If the second parameter is not set, an attempt to decode the input is made
     * with all available decoders of the driver.
     *
     * @link https://image.intervention.io/v3/basics/instantiation#read-image-sources
     *
     * @param string|array<string|DecoderInterface>|DecoderInterface $decoders
     */
    public function decode(mixed $input, string|array|DecoderInterface $decoders): ImageInterface;

    /**
     * Create new image instance by passing one of the named arguments.
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
        null|string|Stringable|DataUri $dataUri = null,
        null|SplFileInfo $splFileInfo = null,
        mixed $stream = null,
    ): ImageInterface;

    /**
     * Create new image instance from path in filesystem.
     *
     * @link https://image.intervention.io/v3/basics/instantiation#read-image-sources
     */
    public function decodeFromPath(string|Stringable $path): ImageInterface;

    /**
     * Create new image instance from raw binary image data.
     *
     * @link https://image.intervention.io/v3/basics/instantiation#read-image-sources
     */
    public function decodeFromBinary(string|Stringable $binary): ImageInterface;

    /**
     * Create new image instance from base64 encoded image data.
     *
     * @link https://image.intervention.io/v3/basics/instantiation#read-image-sources
     */
    public function decodeFromBase64(string|Stringable $base64): ImageInterface;

    /**
     * Create new image instance from data uri encoded image data.
     *
     * @link https://image.intervention.io/v3/basics/instantiation#read-image-sources
     */
    public function decodeFromDataUri(string|Stringable|DataUriInterface $dataUri): ImageInterface;

    /**
     * Create new image instance from file referenced in splFileInfo object.
     *
     * @link https://image.intervention.io/v3/basics/instantiation#read-image-sources
     */
    public function decodeFromSplFileInfo(string|SplFileInfo $splFileInfo): ImageInterface;

    /**
     * Create new image instance from given file pointer.
     *
     * @link https://image.intervention.io/v3/basics/instantiation#read-image-sources
     */
    public function decodeFromStream(mixed $stream): ImageInterface;

    /**
     * Create new animated image by given callback.
     *
     * @link https://image.intervention.io/v3/basics/instantiation#create-animations
     */
    public function animate(callable $init): ImageInterface;

    /**
     * Return currently used driver.
     */
    public function driver(): DriverInterface;
}
