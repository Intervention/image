<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface ThemeInterface
{
    /**
     * Transform theme colors to palette.
     */
    public function toPalette(): PaletteInterface;
}
