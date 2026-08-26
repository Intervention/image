<?php

declare(strict_types=1);

namespace Intervention\Image\Colors;

use Intervention\Image\Analyzers\DominantPaletteAnalyzer;
use Intervention\Image\Analyzers\PopularPaletteAnalyzer;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\PaletteInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Interfaces\ThemeDefinitionInterface;
use Intervention\Image\Interfaces\ThemeInterface;

class ColorExtractor
{
    /**
     * Create new instance.
     */
    public function __construct(protected ImageInterface $image)
    {
        //
    }

    /**
     * Extract the colors that appear most frequently in the image, sorted by popularity (highest first).
     *
     * @throws InvalidArgumentException
     */
    public function popular(int $limit = 256, ?SizeInterface $region = null): PaletteInterface
    {
        return $this->image->analyze(new PopularPaletteAnalyzer($limit, $region));
    }

    /**
     * Extract the visually dominant colors in the image, starting with the most dominant ones.
     *
     * @throws InvalidArgumentException
     */
    public function dominant(int $limit = 8, ?SizeInterface $region = null): PaletteInterface
    {
        return $this->image->analyze(new DominantPaletteAnalyzer($limit, $region));
    }

    /**
     * Extract color theme according to given definition.
     */
    public function theme(Theme|ThemeDefinitionInterface $theme = Theme::VIBRANT_MUTED): ThemeInterface
    {
        $definition = $theme instanceof Theme ? $theme->definition() : $theme;

        return $definition->themeColors(
            $definition->collectColors($this->image),
        );
    }
}
