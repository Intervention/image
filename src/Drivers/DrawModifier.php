<?php

namespace Intervention\Image\Drivers;

use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DrawableInterface;

/**
 * @property DrawableInterface $drawable
 */
abstract class DrawModifier extends DriverModifier
{
    public function position(): Point
    {
        return $this->drawable->position();
    }

    public function backgroundColor(): ColorInterface
    {
        try {
            $color = $this->driver()->handleInput($this->drawable->backgroundColor());
        } catch (DecoderException $e) {
            return $this->driver()->handleInput('transparent');
        }

        return $color;
    }

    public function borderColor(): ColorInterface
    {
        try {
            $color = $this->driver()->handleInput($this->drawable->borderColor());
        } catch (DecoderException $e) {
            return $this->driver()->handleInput('transparent');
        }

        return $color;
    }
}
