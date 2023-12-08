<?php

namespace Intervention\Image\Modifiers;

use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\FontInterface;

class TextModifier extends AbstractModifier
{
    public function __construct(
        public string $text,
        public Point $position,
        public FontInterface $font
    ) {
    }
}
