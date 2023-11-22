<?php

namespace Intervention\Image\Analyzers;

class PixelColorsAnalyzer extends AbstractAnalyzer
{
    public function __construct(
        public int $x,
        public int $y
    ) {
    }
}
