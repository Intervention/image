<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\SpecializableModifier;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DrawableInterface;

abstract class AbstractDrawModifier extends SpecializableModifier
{
    /**
     * Return the drawable object which will be rendered by the modifier
     */
    abstract public function drawable(): DrawableInterface; // TODO: make protected

    /**
     * Return the background color of the object rendered by the modifier
     *
     * @throws StateException
     */
    public function backgroundColor(): ColorInterface // TODO: make protected
    {
        return $this->driver()->handleColorInput($this->drawable()->backgroundColor());
    }

    /**
     * Return the border color of the object rendered by the modifier
     *
     * @throws StateException
     */
    public function borderColor(): ColorInterface // TODO: make protected
    {
        return $this->driver()->handleColorInput($this->drawable()->borderColor());
    }
}
