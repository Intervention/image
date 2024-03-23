<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\SpecializableModifier;

class RotateModifier extends SpecializableModifier
{
    public function __construct(public float $angle, public mixed $background)
    {
    }

    /**
     * Restrict rotations beyond 360 degrees
     * because the end result is the same
     *
     * @return float
     */
    public function rotationAngle(): float
    {
        return fmod($this->angle, 360);
    }
}
