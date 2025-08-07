<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Intervention\Image\Config;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\FileExtension;
use Intervention\Image\Format;
use Intervention\Image\MediaType;

interface DriverInterface
{
    /**
     * Return drivers unique id
     */
    public function id(): string;

    /**
     * Get driver configuration
     */
    public function config(): Config;

    /**
     * Resolve given (generic) object into a specialized version for the current driver
     *
     * @throws NotSupportedException
     * @throws DriverException
     */
    public function specialize(
        ModifierInterface|AnalyzerInterface|EncoderInterface|DecoderInterface $object
    ): ModifierInterface|AnalyzerInterface|EncoderInterface|DecoderInterface;

    /**
     * Create new image instance with the current driver in given dimensions
     *
     * @throws RuntimeException
     */
    public function createImage(int $width, int $height): ImageInterface;

    /**
     * Create new animated image
     *
     * @throws RuntimeException
     */
    public function createAnimation(callable $init): ImageInterface;

    /**
     * Handle given input by decoding it to ImageInterface or ColorInterface
     *
     * @param array<string|DecoderInterface> $decoders
     * @throws DecoderException
     */
    public function handleInput(mixed $input, array $decoders = []): ImageInterface|ColorInterface;

    /**
     * Handle given image source by decoding it to ImageInterface
     *
     * Image sources can be as follows:
     *
     * - Path in filesystem
     * - Raw binary image data
     * - Base64 encoded image data
     * - Data Uri
     * - File Pointer resource
     * - SplFileInfo object
     * - Intervention Image Instance (Intervention\Image\Image)
     * - Encoded Intervention Image (Intervention\Image\EncodedImage)
     * - Driver-specific image (instance of GDImage or Imagick)
     *
     * @param array<string|DecoderInterface> $decoders
     * @throws DecoderException
     * @throws RuntimeException
     */
    public function handleImageInput(mixed $input, array $decoders = []): ImageInterface;

    /**
     * Handle given image source by decoding it to ColorInterface
     *
     * @param array<string|DecoderInterface> $decoders
     * @throws DecoderException
     * @throws RuntimeException
     */
    public function handleColorInput(mixed $input, array $decoders = []): ColorInterface;

    /**
     * Return color processor for the given colorspace
     */
    public function colorProcessor(ColorspaceInterface $colorspace): ColorProcessorInterface;

    /**
     * Return font processor of the current driver
     */
    public function fontProcessor(): FontProcessorInterface;

    /**
     * Check whether all requirements for operating the driver are met and
     * throw exception if the check fails.
     *
     * @throws DriverException
     */
    public function checkHealth(): void;

    /**
     * Check if the current driver supports the given format and if the
     * underlying PHP extension was built with support for the format.
     */
    public function supports(string|Format|FileExtension|MediaType $identifier): bool;

    /**
     * Return the version number of the image driver currently in use.
     *
     * @throws DriverException
     */
    public function version(): string;
}
