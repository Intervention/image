<?php

declare(strict_types=1);

namespace Intervention\Image\Analyzers;

class PixelColorsAnalyzer extends SpecializableAnalyzer
{
    public function __construct(
        public int $x,
        public int $y
    ) {
    }
}
