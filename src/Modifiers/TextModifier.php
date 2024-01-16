<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\FontInterface;

class TextModifier extends SpecializableModifier
{
    public function __construct(
        public string $text,
        public Point $position,
        public FontInterface $font
    ) {
    }
}
