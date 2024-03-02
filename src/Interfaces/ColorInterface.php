<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Exceptions\RuntimeException;

interface ColorInterface
{
    /**
     * Static color factory method that takes any supported color format
     * and returns a corresponding color object
     *
     * @param mixed $input
     * @throws RuntimeException
     * @return ColorInterface
     */
    public static function create(mixed $input): self;

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
     * @param string $classname
     * @throws ColorException
     * @return ColorChannelInterface
     */
    public function channel(string $classname): ColorChannelInterface;

    /**
     * Convert color to given colorspace
     *
     * @return ColorInterface
     */
    public function convertTo(string|ColorspaceInterface $colorspace): self;

    /**
     * Determine if the current color is gray
     *
     * @return bool
     */
    public function isGreyscale(): bool;

    /**
     * Determine if the current color is (semi) transparent
     *
     * @return bool
     */
    public function isTransparent(): bool;
}
