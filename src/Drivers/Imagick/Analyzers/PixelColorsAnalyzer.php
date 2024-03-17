<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Analyzers;

use Intervention\Image\Collection;
use Intervention\Image\Interfaces\ImageInterface;

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
