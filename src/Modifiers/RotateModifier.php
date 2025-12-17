<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\SpecializableModifier;
use Intervention\Image\Interfaces\ColorInterface;

class RotateModifier extends SpecializableModifier
{
    public function __construct(public float $angle, public mixed $background = null)
    {
        //
    }

    /**
     * Restrict rotations beyond 360 degrees
     * because the end result is the same
     */
    public function rotationAngle(): float
    {
        return fmod($this->angle, 360);
    }

    /**
     * Return color to fill the newly created areas after rotation
     */
    protected function backgroundColor(): ColorInterface
    {
        return $this->driver()->handleColorInput(
            $this->background ?? $this->driver()->config()->backgroundColor,
        );
    }
}
