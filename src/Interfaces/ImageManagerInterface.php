<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use SplFileInfo;
use Stringable;

interface ImageManagerInterface
{
    /**
     * Create a new image image instance by using the given driver instance or classname.
     */
    public static function usingDriver(string|DriverInterface $driver, mixed ...$options): self;

    /**
     * Create a new image with the specified width and height.
     */
    public function createImage(
        int $width,
        int $height,
        null|callable|AnimationFactoryInterface $animation = null,
    ): ImageInterface;

    /**
     * Create an image instance by decoding the given image source which can be one of the following:
     *
     * - Path in filesystem
     * - Raw binary image data
     * - SplFileInfo object
     * - Base64 encoded image data
     * - Data Uri string or instance of DataUriInterface
     * - File Pointer resource
     * - Instance of ImageInterface
     * - Instance of EncodedImageInterface
     *
     * To decode the source, you can optionally specify a decoding strategy
     * with the second parameter. This can be an array of class names or objects
     * of decoders to be processed in sequence. In this case, the source must be
     * decodedable with one of the decoders passed. It is also possible to pass
     * a single object or class name of a decoder.
     *
     * If the second parameter is not set, all available images decoders will be tried.
     *
     * @link https://image.intervention.io/v3/basics/instantiation#read-image-sources
     *
     * @param null|string|array<string|DecoderInterface>|DecoderInterface $decoders
     */
    public function decode(mixed $source, null|string|array|DecoderInterface $decoders = null): ImageInterface;

    /**
     * Decode an image instance by decoding a given path in filesystem.
     */
    public function decodePath(string|Stringable $path): ImageInterface;

    /**
     * Decode an image instance by decoding the given raw image data.
     */
    public function decodeBinary(string|Stringable $binary): ImageInterface;

    /**
     * Decode an image by decoding the image data of the given SplFileInfo instance.
     */
    public function decodeSplFileInfo(SplFileInfo $splFileInfo): ImageInterface;

    /**
     * Decode an image by decoding the given base64 encoded image data.
     */
    public function decodeBase64(string|Stringable $base64): ImageInterface;

    /**
     * Decode an image by decoding the given data uri scheme.
     */
    public function decodeDataUri(string|Stringable|DataUriInterface $dataUri): ImageInterface;

    /**
     * Decode an image by decoding the image data of the given file pointer resource.
     */
    public function decodeStream(mixed $stream): ImageInterface;
}
