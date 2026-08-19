<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface ThemeDefinitionInterface
{
    /**
     * Extract the colors needed to define the theme.
     */
    public function extractColors(ImageInterface $image): PaletteInterface;

    /**
     * Assign the colors based on the theme definition.
     */
    public function themeColors(PaletteInterface $image): ThemeInterface;
}
