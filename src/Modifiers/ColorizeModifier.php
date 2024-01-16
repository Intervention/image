<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

class ColorizeModifier extends SpecializableModifier
{
    public function __construct(
        public int $red = 0,
        public int $green = 0,
        public int $blue = 0
    ) {
    }
}
