<?php

namespace Intervention\Image\Modifiers;

class ColorizeModifier extends AbstractModifier
{
    public function __construct(
        public int $red = 0,
        public int $green = 0,
        public int $blue = 0
    ) {
    }
}
