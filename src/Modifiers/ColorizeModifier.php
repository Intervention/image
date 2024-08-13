<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\SpecializableModifier;

class ColorizeModifier extends SpecializableModifier
{
    /**
     * Create new modifier object
     *
     * @param int $red
     * @param int $green
     * @param int $blue
     * @return void
     */
    public function __construct(
        public int $red = 0,
        public int $green = 0,
        public int $blue = 0
    ) {
    }
}
