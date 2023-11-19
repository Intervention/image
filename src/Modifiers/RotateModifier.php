<?php

namespace Intervention\Image\Modifiers;

class RotateModifier extends AbstractModifier
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
