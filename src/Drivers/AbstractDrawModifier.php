<?php

namespace Intervention\Image\Drivers;

use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DrawableInterface;
use Intervention\Image\Interfaces\PointInterface;

/**
 * @property DrawableInterface $drawable
 */
abstract class AbstractDrawModifier extends DriverSpecializedModifier
{
    public function position(): PointInterface
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
