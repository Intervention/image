<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Analyzers;

use Intervention\Image\Collection;
use Intervention\Image\Exceptions\AnalyzerException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\ImageInterface;

class PixelColorsAnalyzer extends PixelColorAnalyzer
{
    /**
     * @throws AnalyzerException
     * @throws StateException
     */
    public function analyze(ImageInterface $image): mixed
    {
        $colors = new Collection();
        $colorProcessor = $this->driver()->colorProcessor($image);

        foreach ($image as $frame) {
            $colors->push(
                parent::colorAt($colorProcessor, $frame)
            );
        }

        return $colors;
    }
}
