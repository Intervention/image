<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\SpecializableModifier;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DrawableInterface;
use RuntimeException;

abstract class AbstractDrawModifier extends SpecializableModifier
{
    /**
     * Return the drawable object which will be rendered by the modifier
     *
     * @return DrawableInterface
     */
    abstract public function drawable(): DrawableInterface;

    /**
     * @throws RuntimeException
     */
    public function backgroundColor(): ColorInterface
    {
        try {
            $color = $this->driver()->handleInput($this->drawable()->backgroundColor());
        } catch (DecoderException) {
            return $this->driver()->handleInput('transparent');
        }

        return $color;
    }

    /**
     * @throws RuntimeException
     */
    public function borderColor(): ColorInterface
    {
        try {
            $color = $this->driver()->handleInput($this->drawable()->borderColor());
        } catch (DecoderException) {
            return $this->driver()->handleInput('transparent');
        }

        return $color;
    }
}
