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
     * @throws RuntimeException
     */
    public function handleInput(mixed $input, array $decoders = []): ImageInterface|ColorInterface;

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
}
