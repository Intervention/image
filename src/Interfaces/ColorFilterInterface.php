<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface ColorFilterInterface
{
    /**
     * Filter colors.
     */
    public function filterColors(PaletteInterface $palette): SwatchesInterface;
}
