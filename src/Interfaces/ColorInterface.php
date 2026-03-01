<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface ColorInterface
{
    /**
     * Static color factory method that takes any supported color format
     * and returns a corresponding color object.
     */
    public static function create(mixed ...$input): self;

    /**
     * Return colorspace of current color.
     */
    public function colorspace(): ColorspaceInterface;

    /**
     * Cast color object to string.
     */
    public function toString(): string;

    /**
     * Cast color object to hex encoded web color.
     */
    public function toHex(string $prefix = ''): string;

    /**
     * Return array of all color channels.
     *
     * @return array<ColorChannelInterface>
     */
    public function channels(): array;

    /**
     * Retrieve the color channel by its classname.
     */
    public function channel(string $classname): ColorChannelInterface;

    /**
     * Get the alpha channel of the color.
     */
    public function alpha(): ColorChannelInterface;

    /**
     * Convert color to given colorspace.
     */
    public function toColorspace(string|ColorspaceInterface $colorspace): self;

    /**
     * Determine if the current color is gray.
     */
    public function isGrayscale(): bool;

    /**
     * Determine if the current color is (semi) transparent.
     */
    public function isTransparent(): bool;

    /**
     * Determine whether the current color is completely transparent.
     */
    public function isClear(): bool;

    /**
     * Return a copy of the current color with the specified transparency value.
     */
    public function withTransparency(float $transparency): self;

    /**
     * Return a copy of the current color with adjusted brightness.
     * Positive values lighten, negative values darken. Range: -100 to 100.
     */
    public function withBrightness(int $level): self;

    /**
     * Return a copy of the current color with adjusted saturation.
     * Positive values saturate, negative values desaturate. Range: -100 to 100.
     */
    public function withSaturation(int $level): self;

    /**
     * Return an inverted copy of the current color.
     */
    public function withInversion(): self;
}
