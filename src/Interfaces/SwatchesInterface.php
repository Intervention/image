<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Traversable;

/**
 * @extends Traversable<ColorInterface>
 */
interface SwatchesInterface extends Traversable
{
    /**
     * Create a filter instance that matches the swatches.
     */
    public function colorFilter(): ColorFilterInterface;

    /**
     * Transform swatches to color palette.
     */
    public function toPalette(): PaletteInterface;
}
