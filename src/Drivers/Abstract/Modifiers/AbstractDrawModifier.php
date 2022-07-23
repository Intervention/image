<?php

namespace Intervention\Image\Drivers\Abstract\Modifiers;

use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DrawableInterface;
use Intervention\Image\Interfaces\PointInterface;
use Intervention\Image\Traits\CanHandleInput;

class AbstractDrawModifier
{
    use CanHandleInput;

    public function __construct(
        protected PointInterface $position,
        protected DrawableInterface $drawable
    ) {
        //
    }

    public function drawable(): DrawableInterface
    {
        return $this->drawable;
    }

    protected function getBackgroundColor(): ?ColorInterface
    {
        try {
            $color = $this->handleInput($this->drawable->getBackgroundColor());
        } catch (DecoderException $e) {
            return $this->handleInput('transparent');
        }

        return $color;
    }

    protected function getBorderColor(): ?ColorInterface
    {
        try {
            $color = $this->handleInput($this->drawable->getBorderColor());
        } catch (DecoderException $e) {
            return $this->handleInput('transparent');
        }

        return $color;
    }
}
