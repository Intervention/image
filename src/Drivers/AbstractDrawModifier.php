<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers;

use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DrawableInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\PointInterface;

/**
 * @property DrawableInterface $drawable
 */
abstract class AbstractDrawModifier extends DriverSpecialized implements ModifierInterface
{
    public function position(): PointInterface
    {
        return $this->drawable->position();
    }

    /**
     * @throws RuntimeException
     */
    public function backgroundColor(): ColorInterface
    {
        try {
            $color = $this->driver()->handleInput($this->drawable->backgroundColor());
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
            $color = $this->driver()->handleInput($this->drawable->borderColor());
        } catch (DecoderException) {
            return $this->driver()->handleInput('transparent');
        }

        return $color;
    }
}
