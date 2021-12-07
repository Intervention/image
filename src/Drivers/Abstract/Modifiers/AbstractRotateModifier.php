<?php

namespace Intervention\Image\Drivers\Abstract\Modifiers;

use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Exceptions\TypeException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Traits\CanHandleInput;

abstract class AbstractRotateModifier
{
    use CanHandleInput;

    public function __construct(protected float $angle, protected $background)
    {
        //
    }

    /**
     * Restrict rotations beyond 360 degrees
     * because the end result is the same
     *
     * @return float
     */
    protected function rotationAngle(): float
    {
        return fmod($this->angle, 360);
    }

    protected function backgroundColor(): ColorInterface
    {
        try {
            return $this->handleInput($this->background);
        } catch (DecoderException $e) {
            throw new TypeException("rotate(): Argument #2 must be a color value.");
        }
    }
}
