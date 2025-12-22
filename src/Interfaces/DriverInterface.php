<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Intervention\Image\Config;
use Intervention\Image\Exceptions\MissingDependencyException;
use Intervention\Image\FileExtension;
use Intervention\Image\Format;
use Intervention\Image\MediaType;

interface DriverInterface
{
    /**
     * Create new driver instance with configuration
     */
    public function __construct(Config $config);

    /**
     * Return drivers unique id
     */
    public function id(): string;

    /**
     * Get driver configuration
     */
    public function config(): Config;

    /**
     * Resolve given modifier into a specialized version for the current driver
     */
    public function specializeModifier(ModifierInterface $modifier): ModifierInterface;

    /**
     * Resolve given analyzer into a specialized version for the current driver
     */
    public function specializeAnalyzer(AnalyzerInterface $analyzer): AnalyzerInterface;

    /**
     * Resolve given encoder into a specialized version for the current driver
     */
    public function specializeEncoder(EncoderInterface $encoder): EncoderInterface;

    /**
     * Resolve given decoder into a specialized version for the current driver
     */
    public function specializeDecoder(DecoderInterface $decoder): DecoderInterface;

    /**
     * Create new image instance with the current driver in given dimensions
     */
    public function createImage(int $width, int $height): ImageInterface;

    /**
     * Create new animated image
     */
    public function createAnimation(callable $init): ImageInterface;

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
     */
    public function handleImageInput(mixed $input, ?array $decoders = null): ImageInterface;

    /**
     * Handle given image source by decoding it to ColorInterface
     */
    public function handleColorInput(mixed $input, ?array $decoders = null): ColorInterface;

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
     * @throws MissingDependencyException
     */
    public function checkHealth(): void;

    /**
     * Check if the current driver supports the given format and if the
     * underlying PHP extension was built with support for the format.
     */
    public function supports(string|Format|FileExtension|MediaType $identifier): bool;

    /**
     * Return the version number of the image driver currently in use.
     */
    public function version(): string;
}
