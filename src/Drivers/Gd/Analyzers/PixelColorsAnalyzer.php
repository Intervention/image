<?php

namespace Intervention\Image\Drivers\Gd\Analyzers;

use Intervention\Image\Collection;
use Intervention\Image\Interfaces\ImageInterface;

/**
 * @property int $x
 * @property int $y
 */
class PixelColorsAnalyzer extends PixelColorAnalyzer
{
    public function analyze(ImageInterface $image): mixed
    {
        $colors = new Collection();
        $colorspace = $image->colorspace();

        foreach ($image as $frame) {
            $colors->push(
                parent::colorAt($colorspace, $frame->native())
            );
        }

        return $colors;
    }
}
