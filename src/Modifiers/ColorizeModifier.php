<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\SpecializableModifier;
use Intervention\Image\Exceptions\InvalidArgumentException;

class ColorizeModifier extends SpecializableModifier
{
    /**
     * Create new modifier object.
     *
     * @throws InvalidArgumentException
     */
    public function __construct(
        public int $red = 0,
        public int $green = 0,
        public int $blue = 0
    ) {
        if ($red < -100 || $red > 100) {
            throw new InvalidArgumentException('Color level for argument $red must be in range -100 to 100');
        }

        if ($green < -100 || $green > 100) {
            throw new InvalidArgumentException('Color level for argument $green must be in range -100 to 100');
        }

        if ($blue < -100 || $blue > 100) {
            throw new InvalidArgumentException('Color level for argument $blue must be in range -100 to 100');
        }
    }
}
