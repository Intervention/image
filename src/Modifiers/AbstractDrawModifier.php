<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\SpecializableModifier;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DrawableInterface;

abstract class AbstractDrawModifier extends SpecializableModifier
{
    /**
     * Return the drawable object which will be rendered by the modifier
     */
    abstract public function drawable(): DrawableInterface;

    public function backgroundColor(): ColorInterface
    {
        try {
            $color = $this->driver()->handleColorInput($this->drawable()->backgroundColor());
        } catch (DecoderException) {
            return $this->driver()->handleColorInput('transparent');
        }

        return $color;
    }

    public function borderColor(): ColorInterface
    {
        try {
            $color = $this->driver()->handleColorInput($this->drawable()->borderColor());
        } catch (DecoderException) {
            return $this->driver()->handleColorInput('transparent');
        }

        return $color;
    }
}
