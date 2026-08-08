<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Analyzers;

use Intervention\Image\Analyzers\ColorCountAnalyzer as GenericColorCountAnalyzer;
use Intervention\Image\Interfaces\ImageInterface;

class ColorCountAnalyzer extends GenericColorCountAnalyzer
{
    /**
     * {@inheritdoc}
     *
     * @see AnalyzerInterface::analyze()
     */
    public function analyze(ImageInterface $image): ?int
    {
        $count = imagecolorstotal($image->core()->native());

        return $count === 0 ? null : $count;
    }
}
