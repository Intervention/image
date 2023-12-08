<?php

namespace Intervention\Image\Interfaces;

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
     * @param object $input
     * @return object
     */
    public function resolve(object $input): object;

    /**
     * Create new image instance with the current driver in given dimensions
     *
     * @param int $width
     * @param int $height
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
     * @return ImageInterface|ColorInterface
     */
    public function handleInput(mixed $input): ImageInterface|ColorInterface;

    /**
     * Return color processor for the given colorspace
     *
     * @param ColorspaceInterface $colorspace
     * @return ColorProcessorInterface
     */
    public function colorProcessor(ColorspaceInterface $colorspace): ColorProcessorInterface;
}
