<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use SplFileInfo;
use Stringable;

interface ImageManagerInterface
{
    /**
     * Create a new image manager using the given driver.
     */
    public static function usingDriver(string|DriverInterface $driver, mixed ...$options): self;

    /**
     * Create a new image with the given width and height.
     */
    public function createImage(
        int $width,
        int $height,
        null|callable|AnimationFactoryInterface $animation = null,
    ): ImageInterface;

    /**
     * Decode an image from the given source which can be one of the following:
     *
     * - Path in filesystem
     * - Raw binary image data
     * - SplFileInfo object
     * - Base64 encoded image data
     * - Data URI string or instance of DataUriInterface
     * - File pointer resource
     * - Instance of ImageInterface
     * - Instance of EncodedImageInterface
     *
     * Optionally, one or more specific decoders can be provided. If no
     * decoders are specified, all available decoders will be tried.
     *
     * @link https://image.intervention.io/v3/basics/instantiation#read-image-sources
     *
     * @param null|string|array<string|DecoderInterface>|DecoderInterface $decoders
     */
    public function decode(mixed $source, null|string|array|DecoderInterface $decoders = null): ImageInterface;

    /**
     * Decode an image from the given file path.
     */
    public function decodePath(string|Stringable $path): ImageInterface;

    /**
     * Decode an image from the given raw binary data.
     */
    public function decodeBinary(string|Stringable $binary): ImageInterface;

    /**
     * Decode an image from the given SplFileInfo object.
     */
    public function decodeSplFileInfo(SplFileInfo $splFileInfo): ImageInterface;

    /**
     * Decode an image from the given base64 encoded data.
     */
    public function decodeBase64(string|Stringable $base64): ImageInterface;

    /**
     * Decode an image from the given data URI.
     */
    public function decodeDataUri(string|Stringable|DataUriInterface $dataUri): ImageInterface;

    /**
     * Decode an image from the given file pointer resource.
     */
    public function decodeStream(mixed $stream): ImageInterface;
}
