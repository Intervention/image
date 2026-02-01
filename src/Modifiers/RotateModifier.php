<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\SpecializableModifier;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\ColorInterface;

class RotateModifier extends SpecializableModifier
{
    public function __construct(
        public float $angle,
        public null|string|ColorInterface $background = null,
    ) {
        //
    }

    /**
     * Clockwise rotation angle.
     *
     * Restricted beyond 360 degrees because the end result is the same.
     */
    public function rotationAngle(): float
    {
        return fmod($this->angle, 360); // TODO: check rotation direction of all modifiers with rotation
    }

    /**
     * Return color to fill the newly created areas after rotation.
     *
     * @throws StateException
     */
    protected function backgroundColor(): ColorInterface
    {
        return $this->driver()->handleColorInput(
            $this->background ?? $this->driver()->config()->backgroundColor,
        );
    }
}
