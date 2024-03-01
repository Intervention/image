<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Exceptions\RuntimeException;

interface DriverInterface
{
    /**
     * Return drivers unique id
     *
     * @return string
     */
    public function id(): string;

    /**
     * Resolve given object into a specialized version for the current driver
     *
     * @param object $object
     * @throws NotSupportedException
     * @return ModifierInterface|AnalyzerInterface|EncoderInterface|DecoderInterface
     */
    public function specialize(object $object): ModifierInterface|AnalyzerInterface|EncoderInterface|DecoderInterface;

    /**
     * Resolve array of classnames or objects into their specialized version for the current driver
     *
     * @param array $specializables
     * @return array
     */
    public function specializeMultiple(array $specializables): array;

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
     * @return ImageInterface
     */
    public function createAnimation(callable $init): ImageInterface;

    /**
     * Handle given input by decoding it to ImageInterface or ColorInterface
     *
     * @param mixed $input
     * @param array $decoders
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
}
