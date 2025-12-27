<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\SpecializableModifier;
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
        return $this->driver()->handleColorInput($this->drawable()->backgroundColor());
    }

    public function borderColor(): ColorInterface
    {
        return $this->driver()->handleColorInput($this->drawable()->borderColor());
    }
}
