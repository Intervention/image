<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Intervention\Image\Config;
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
     *
     * @return string
     */
    public function id(): string;

    /**
     * Get driver configuration
     *
     * @return Config
     */
    public function config(): Config;

    /**
     * Resolve given object into a specialized version for the current driver
     *
     * @param ModifierInterface|AnalyzerInterface|EncoderInterface|DecoderInterface $object
     * @throws NotSupportedException
     * @throws DriverException
     * @return ModifierInterface|AnalyzerInterface|EncoderInterface|DecoderInterface
     */
    public function specialize(
        ModifierInterface|AnalyzerInterface|EncoderInterface|DecoderInterface $object
    ): ModifierInterface|AnalyzerInterface|EncoderInterface|DecoderInterface;

    /**
     * Resolve array of classnames or objects into their specialized version for the current driver
     *
     * @param array<string|object> $objects
     * @throws NotSupportedException
     * @throws DriverException
     * @return array<object>
     */
    public function specializeMultiple(array $objects): array;

    /**
     * Create new image instance with the current driver in given dimensions
     *
     * @param int $width
     * @param int $height
     * @throws RuntimeException
     * @return ImageInterface
     */
    public function createImage(int $width, int $height): ImageInterface;

    /**
     * Create new animated image
     *
     * @param callable $init
     * @throws RuntimeException
     * @return ImageInterface
     */
    public function createAnimation(callable $init): ImageInterface;

    /**
     * Handle given input by decoding it to ImageInterface or ColorInterface
     *
     * @param mixed $input
     * @param array<string|DecoderInterface> $decoders
     * @throws RuntimeException
     * @return ImageInterface|ColorInterface
     */
    public function handleInput(mixed $input, array $decoders = []): ImageInterface|ColorInterface;

    /**
     * Return color processor for the given colorspace
     *
     * @param ColorspaceInterface $colorspace
     * @return ColorProcessorInterface
     */
    public function colorProcessor(ColorspaceInterface $colorspace): ColorProcessorInterface;

    /**
     * Return font processor of the current driver
     *
     * @return FontProcessorInterface
     */
    public function fontProcessor(): FontProcessorInterface;

    /**
     * Check whether all requirements for operating the driver are met and
     * throw exception if the check fails.
     *
     * @throws DriverException
     * @return void
     */
    public function checkHealth(): void;

    /**
     * Check if the current driver supports the given format and if the
     * underlying PHP extension was built with support for the format.
     *
     * @param string|Format|FileExtension|MediaType $identifier
     * @return bool
     */
    public function supports(string|Format|FileExtension|MediaType $identifier): bool;
}
