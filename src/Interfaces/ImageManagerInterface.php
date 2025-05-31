<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Intervention\Image\Exceptions\RuntimeException;

interface ImageManagerInterface
{
    /**
     * Create new image instance with given width & height
     *
     * @link https://image.intervention.io/v3/basics/instantiation#create-new-images
     *
     * @throws RuntimeException
     */
    public function create(int $width, int $height): ImageInterface;

    /**
     * Create new image instance from given input which can be one of the following
     *
     * - Path in filesystem
     * - File Pointer resource
     * - SplFileInfo object
     * - Raw binary image data
     * - Base64 encoded image data
     * - Data Uri
     * - Intervention\Image\Image Instance
     *
     * To decode the raw input data, you can optionally specify a decoding strategy
     * with the second parameter. This can be an array of class names or objects
     * of decoders to be processed in sequence. In this case, the input must be
     * decodedable with one of the decoders passed. It is also possible to pass
     * a single object or class name of a decoder.
     *
     * All decoders that implement the `DecoderInterface::class` can be passed. Usually
     * a selection of classes of the namespace `Intervention\Image\Decoders`
     *
     * If the second parameter is not set, an attempt to decode the input is made
     * with all available decoders of the driver.
     *
     * @link https://image.intervention.io/v3/basics/instantiation#read-image-sources
     *
     * @param string|array<string|DecoderInterface>|DecoderInterface $decoders
     * @throws RuntimeException
     */
    public function read(mixed $input, string|array|DecoderInterface $decoders = []): ImageInterface;

    /**
     * Create new animated image by given callback
     *
     * @link https://image.intervention.io/v3/basics/instantiation#create-animations
     *
     * @throws RuntimeException
     */
    public function animate(callable $init): ImageInterface;

    /**
     * Return currently used driver
     */
    public function driver(): DriverInterface;
}
