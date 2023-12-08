<?php

namespace Intervention\Image\Interfaces;

interface ColorInterface
{
    /**
     * Static color factory method that passed input to color decoding input handler
     *
     * @param mixed $input
     * @return ColorInterface
     * @throws \Intervention\Image\Exceptions\DecoderException
     */
    public static function create(mixed $input): ColorInterface;

    /**
     * Return colorspace of current color
     *
     * @return ColorspaceInterface
     */
    public function colorspace(): ColorspaceInterface;

    /**
     * Cast color object to string
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Cast color object to string
     *
     * @return string
     */
    public function toString(): string;

    /**
     * Cast color object to array
     *
     * @return array
     */
    public function toArray(): array;

    /**
     * Cast color object to hex encoded web color
     *
     * @return string
     */
    public function toHex(string $prefix = ''): string;

    /**
     * Return array of all color channels
     *
     * @return array
     */
    public function channels(): array;

    /**
     * Retrieve the color channel by its classname
     *
     * @param  string $classname
     * @return ColorChannelInterface
     */
    public function channel(string $classname): ColorChannelInterface;

    /**
     * Convert color to given colorspace
     *
     * @return ColorInterface
     */
    public function convertTo(string|ColorspaceInterface $colorspace): ColorInterface;

    /**
     * Determine if the current color is gray
     *
     * @return bool
     */
    public function isGreyscale(): bool;
}
