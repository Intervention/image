<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Traversable;

/**
 * @extends Traversable<int|string, mixed>
 */
interface PaletteInterface extends Traversable
{
    /**
     * Get colors of palette.
     *
     * @return array<ColorInterface>
     */
    public function colors(): array;

    /**
     * Get first color of palette.
     */
    public function first(): ?ColorInterface;

    /**
     * Get last color of palette.
     */
    public function last(): ?ColorInterface;

    /**
     * Return number of colors in palette.
     */
    public function count(): int;

    /**
     * Get a version of the palette that contains the visually most dominant colors.
     */
    public function dominant(int $maxColors = 8): self;

    /**
     * Try to find the best "vibrant" color in the palette.
     */
    public function vibrant(): ?ColorInterface;

    /**
     * Try to find the best "muted" color in the palette.
     */
    public function muted(): ?ColorInterface;

    /**
     * Try to find the best "dark vibrant" color in the palette.
     */
    public function darkVibrant(): ?ColorInterface;

    /**
     * Try to find the best "light vibrant" color in the palette.
     */
    public function lightVibrant(): ?ColorInterface;

    /**
     * Try to find the best "dark muted" color in the palette.
     */
    public function darkMuted(): ?ColorInterface;

    /**
     * Try to find the best "light muted" color in the palette.
     */
    public function lightMuted(): ?ColorInterface;

    /**
     * Transform colors of palette to given color space.
     */
    public function toColorspace(string|ColorspaceInterface $colorspace): self;

    /**
     * Transform collection as array.
     *
     * @return array<ColorInterface>
     */
    public function toArray(): array;
}
