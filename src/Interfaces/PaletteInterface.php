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
     * Get first color of palette.
     */
    public function first(): ColorInterface;

    /**
     * Return number of colors in palette.
     */
    public function count(): int;

    /**
     * Get a version of the palette that shows colors occupying the most visual area.
     */
    public function dominant(int $size = 10): self;

    // public function toColorspace(string|ColorspaceInterface): self;
    // public function last(): ColorInterface;
}
