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
     * @throws RuntimeException
     */
    public static function create(mixed $input): self;

    /**
     * Return colorspace of current color
     */
    public function colorspace(): ColorspaceInterface;

    /**
     * Cast color object to string
     */
    public function toString(): string;

    /**
     * Cast color object to array
     *
     * @return array<int>
     */
    public function toArray(): array;

    /**
     * Cast color object to hex encoded web color
     */
    public function toHex(string $prefix = ''): string;

    /**
     * Return array of all color channels
     *
     * @return array<ColorChannelInterface>
     */
    public function channels(): array;

    /**
     * Return array of normalized color channel values
     *
     * @return array<float>
     */
    public function normalize(): array;

    /**
     * Retrieve the color channel by its classname
     *
     * @throws ColorException
     */
    public function channel(string $classname): ColorChannelInterface;

    /**
     * Convert color to given colorspace
     */
    public function convertTo(string|ColorspaceInterface $colorspace): self;

    /**
     * Determine if the current color is gray
     */
    public function isGreyscale(): bool;

    /**
     * Determine if the current color is (semi) transparent
     */
    public function isTransparent(): bool;

    /**
     * Determine whether the current color is completely transparent
     */
    public function isClear(): bool;

    /**
     * Cast color object to string
     */
    public function __toString(): string;
}
