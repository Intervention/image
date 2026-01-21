<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\SpecializableModifier;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\PointInterface;

class DrawPixelModifier extends SpecializableModifier
{
    /**
     * Create new modifier object.
     */
    public function __construct(
        public PointInterface $position,
        public string|ColorInterface $color
    ) {
        //
    }

    /**
     * Return color for the new pixel.
     */
    protected function color(): ColorInterface
    {
        return $this->driver()->handleColorInput($this->color);
    }
}
