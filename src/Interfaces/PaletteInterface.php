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
    public function first(): ?PaletteColor;

    /**
     * Get last color of palette.
     */
    public function last(): ?PaletteColor;

    /**
     * Return number of colors in palette.
     */
    public function count(): int;

    /**
     * Get a version of the palette that contains the visually most dominant colors.
     */
    public function dominant(int $maxColors = 16): self;

    // public function toColorspace(string|ColorspaceInterface): self;
    // public function map(callable $callback): self;
}
