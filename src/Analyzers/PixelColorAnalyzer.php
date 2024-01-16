<?php

declare(strict_types=1);

namespace Intervention\Image\Analyzers;

class PixelColorAnalyzer extends SpecializableAnalyzer
{
    public function __construct(
        public int $x,
        public int $y,
        public int $frame_key = 0
    ) {
    }
}
