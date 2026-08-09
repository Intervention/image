<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Intervention\Image\Colors\PaletteColor;
use Traversable;

/**
 * @extends Traversable<int|string, mixed>
 */
interface PaletteInterface extends Traversable
{
    /**
     * Get first color of palette.
     */
    public function first(): PaletteColor;

    /**
     * Return number of colors in palette.
     */
    public function count(): int;

    /**
     * Get a version of the palette that contains the visually most dominant colors.
     */
    public function dominant(int $maxColors = 16): self;

    /**
     * Determine if the given color is part of the palette.
     */
    public function hasColor(ColorInterface $color): bool;

    // public function toColorspace(string|ColorspaceInterface): self;
    // public function last(): ColorInterface;
}
