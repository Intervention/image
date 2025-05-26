<?php

declare(strict_types=1);

namespace Intervention\Image\Analyzers;

use Intervention\Image\Drivers\SpecializableAnalyzer;

class PixelColorsAnalyzer extends SpecializableAnalyzer
{
    public function __construct(
        public int $x,
        public int $y
    ) {
        //
    }
}
