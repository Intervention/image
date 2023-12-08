<?php

namespace Intervention\Image\Analyzers;

class PixelColorAnalyzer extends AbstractAnalyzer
{
    public function __construct(
        public int $x,
        public int $y,
        public int $frame_key = 0
    ) {
    }
}
